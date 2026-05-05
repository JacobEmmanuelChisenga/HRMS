<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRecord;
use App\Models\QrCode;
use App\Models\AttendanceSetting;
use App\Models\EmployeeProfile;
use Illuminate\Support\Str;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;
use App\Traits\ScopesDataByRole;

class AttendanceController extends Controller
{
    use ScopesDataByRole;
    public function index()
    {
        $user = auth()->user();
        $records = AttendanceRecord::whereHas('employee', function($q) use ($user) {
            $q->whereHas('user', function($q2) use ($user) {
                $q2->where('company_id', $user->company_id);
            });
        })->with('employee.user')
        ->orderBy('date', 'desc')
        ->paginate(15);

        return view('attendance.index', compact('records'));
    }

    public function myAttendance()
    {
        $employee = auth()->user()->employeeProfile;
        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee profile not found');
        }

        $records = AttendanceRecord::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('attendance.my-attendance', compact('records'));
    }

    public function clockIn(Request $request)
    {
        $employee = auth()->user()->employeeProfile;
        if (!$employee) {
            return redirect()->back()->with('error', 'Employee profile not found');
        }

        $today = Carbon::today();
        $record = AttendanceRecord::where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();

        if ($record && $record->clock_in) {
            return redirect()->back()->with('error', 'Already clocked in today');
        }

        $now = Carbon::now();
        $settings = AttendanceSetting::where('company_id', auth()->user()->company_id)->first();
        
        $workStartTime = $settings ? Carbon::parse($settings->work_start_time) : Carbon::parse('08:00:00');
        $lateThreshold = $settings ? $settings->late_threshold_minutes : 15;
        
        $status = 'present';
        if ($now->gt($workStartTime->copy()->addMinutes($lateThreshold))) {
            $status = 'late';
        }

        if (!$record) {
            $record = AttendanceRecord::create([
                'employee_id' => $employee->id,
                'date' => $today,
                'clock_in' => $now->toTimeString(),
                'status' => $status,
                'ip_address' => $request->ip(),
            ]);
        } else {
            $record->update([
                'clock_in' => $now->toTimeString(),
                'status' => $status,
            ]);
        }

        return redirect()->back()->with('success', 'Clocked in successfully at ' . $now->format('h:i A'));
    }

    public function clockOut(Request $request)
    {
        $employee = auth()->user()->employeeProfile;
        if (!$employee) {
            return redirect()->back()->with('error', 'Employee profile not found');
        }

        $today = Carbon::today();
        $record = AttendanceRecord::where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();

        if (!$record || !$record->clock_in) {
            return redirect()->back()->with('error', 'Please clock in first');
        }

        if ($record->clock_out) {
            return redirect()->back()->with('error', 'Already clocked out today');
        }

        $now = Carbon::now();
        $clockIn = Carbon::parse($record->clock_in);
        $hoursWorked = $clockIn->diffInHours($now) + ($clockIn->diffInMinutes($now) % 60) / 60;

        $record->update([
            'clock_out' => $now->toTimeString(),
            'hours_worked' => round($hoursWorked, 2),
        ]);

        return redirect()->back()->with('success', 'Clocked out successfully at ' . $now->format('h:i A'));
    }

    public function qrCode()
    {
        $company = auth()->user()->company;
        
        // Generate or get active QR code
        $qrCode = QrCode::where('company_id', $company->id)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        // If no active QR code or expired, create new one
        if (!$qrCode) {
            // Deactivate old codes
            QrCode::where('company_id', $company->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $settings = AttendanceSetting::where('company_id', $company->id)->first();
            $rotationMinutes = $settings ? $settings->qr_rotation_minutes : 5;

            $qrCode = QrCode::create([
                'company_id' => $company->id,
                'code' => Str::random(32) . '-' . $company->id . '-' . time(),
                'expires_at' => now()->addMinutes($rotationMinutes),
                'is_active' => true,
            ]);
        }

        return view('attendance.qr-code', compact('qrCode'));
    }

    // Daily Attendance Methods
    public function todayAttendance()
    {
        $user = auth()->user();
        $companyId = $user->company_id;
        $today = Carbon::today();

        $records = AttendanceRecord::whereHas('employee', function($q) use ($companyId) {
            $q->whereHas('user', function($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })
        ->where('date', $today)
        ->with('employee.user')
        ->orderBy('clock_in', 'desc')
        ->get();

        $stats = [
            'total' => $records->count(),
            'present' => $records->where('status', 'present')->count(),
            'late' => $records->where('status', 'late')->count(),
            'absent' => EmployeeProfile::whereHas('user', function($q) use ($companyId) {
                $q->where('company_id', $companyId)->where('status', 'active');
            })->count() - $records->count(),
        ];

        return view('attendance.daily.today', compact('records', 'stats'));
    }

    public function markAttendance(Request $request)
    {
        if ($request->isMethod('get')) {
            $user = auth()->user();
            $companyId = $user->company_id;
            
            $employees = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
                $q->where('company_id', $companyId)->where('status', 'active');
            })->with('user')->get();

            return view('attendance.daily.mark', compact('employees'));
        }

        $request->validate([
            'employee_id' => 'required|exists:employee_profiles,id',
            'date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after:clock_in',
            'status' => 'required|in:present,late,absent,half_day,on_leave',
            'notes' => 'nullable|string|max:500',
        ]);

        $employee = EmployeeProfile::findOrFail($request->employee_id);
        
        // Verify employee belongs to same company
        if ($employee->user->company_id !== auth()->user()->company_id) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        $date = Carbon::parse($request->date);
        $record = AttendanceRecord::where('employee_id', $employee->id)
            ->where('date', $date)
            ->first();

        $data = [
            'employee_id' => $employee->id,
            'date' => $date,
            'status' => $request->status,
            'notes' => $request->notes,
        ];

        if ($request->clock_in) {
            $data['clock_in'] = Carbon::parse($request->clock_in)->toTimeString();
        }

        if ($request->clock_out) {
            $data['clock_out'] = Carbon::parse($request->clock_out)->toTimeString();
            if ($request->clock_in) {
                $clockIn = Carbon::parse($request->clock_in);
                $clockOut = Carbon::parse($request->clock_out);
                $data['hours_worked'] = round($clockIn->diffInHours($clockOut) + ($clockIn->diffInMinutes($clockOut) % 60) / 60, 2);
            }
        }

        if ($record) {
            $record->update($data);
            $message = 'Attendance record updated successfully';
        } else {
            AttendanceRecord::create($data);
            $message = 'Attendance record created successfully';
        }

        return redirect()->route('attendance.today')->with('success', $message);
    }

    public function attendanceDashboard()
    {
        $user = auth()->user();
        $companyId = $user->company_id;
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Today's stats
        $todayRecords = AttendanceRecord::whereHas('employee', function($q) use ($companyId) {
            $q->whereHas('user', function($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->where('date', $today)->get();

        $totalEmployees = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
            $q->where('company_id', $companyId)->where('status', 'active');
        })->count();

        $todayStats = [
            'present' => $todayRecords->where('status', 'present')->count(),
            'late' => $todayRecords->where('status', 'late')->count(),
            'absent' => $totalEmployees - $todayRecords->count(),
            'on_leave' => $todayRecords->where('status', 'on_leave')->count(),
        ];

        // Monthly stats
        $monthlyRecords = AttendanceRecord::whereHas('employee', function($q) use ($companyId) {
            $q->whereHas('user', function($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->get();

        $monthlyStats = [
            'total_days' => $startOfMonth->diffInDays($endOfMonth) + 1,
            'average_attendance' => $monthlyRecords->count() > 0 
                ? round(($monthlyRecords->where('status', '!=', 'absent')->count() / ($totalEmployees * ($startOfMonth->diffInDays($endOfMonth) + 1))) * 100, 2)
                : 0,
            'total_late' => $monthlyRecords->where('status', 'late')->count(),
            'total_absent' => $monthlyRecords->where('status', 'absent')->count(),
        ];

        // Recent records
        $recentRecords = AttendanceRecord::whereHas('employee', function($q) use ($companyId) {
            $q->whereHas('user', function($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })
        ->with('employee.user')
        ->orderBy('date', 'desc')
        ->orderBy('clock_in', 'desc')
        ->limit(10)
        ->get();

        return view('attendance.daily.dashboard', compact('todayStats', 'monthlyStats', 'recentRecords', 'totalEmployees'));
    }

    // QR Code Management Methods
    public function generateQrCode()
    {
        $company = auth()->user()->company;
        
        // Deactivate old codes
        QrCode::where('company_id', $company->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $settings = AttendanceSetting::where('company_id', $company->id)->first();
        $rotationMinutes = $settings ? $settings->qr_rotation_minutes : 5;

        $qrCode = QrCode::create([
            'company_id' => $company->id,
            'code' => Str::random(32) . '-' . $company->id . '-' . time(),
            'expires_at' => now()->addMinutes($rotationMinutes),
            'is_active' => true,
        ]);

        return redirect()->route('attendance.qr.display')->with('success', 'QR Code generated successfully');
    }

    public function qrCodeDisplay()
    {
        $company = auth()->user()->company;
        
        $qrCode = QrCode::where('company_id', $company->id)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        // Don't redirect - just pass null and let the view handle it
        return view('attendance.qr.display', compact('qrCode'));
    }

    public function qrSettings()
    {
        $company = auth()->user()->company;
        $settings = AttendanceSetting::where('company_id', $company->id)->first();

        return view('attendance.qr.settings', compact('settings'));
    }

    public function updateQrSettings(Request $request)
    {
        $request->validate([
            'qr_rotation_minutes' => 'required|integer|min:1|max:60',
        ]);

        $company = auth()->user()->company;
        $settings = AttendanceSetting::firstOrNew(['company_id' => $company->id]);
        $settings->qr_rotation_minutes = $request->qr_rotation_minutes;
        $settings->save();

        return redirect()->back()->with('success', 'QR settings updated successfully');
    }

    // Attendance Records Methods
    public function viewAllRecords(Request $request)
    {
        $query = AttendanceRecord::query();
        $this->scopeAttendanceRecords($query);
        $query->with('employee.user');

        // Filters
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->orderBy('date', 'desc')
            ->orderBy('clock_in', 'desc')
            ->paginate(20);

        // Get employees (scoped)
        $employeeQuery = EmployeeProfile::query();
        $this->scopeEmployees($employeeQuery);
        $employees = $employeeQuery
            ->whereHas('user', function($q) {
                $q->where('status', 'active');
            })
            ->with('user')
            ->get();

        return view('attendance.records.index', compact('records', 'employees'));
    }

    public function lateArrivals(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $query = AttendanceRecord::whereHas('employee', function($q) use ($companyId) {
            $q->whereHas('user', function($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })
        ->where('status', 'late')
        ->with('employee.user');

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $records = $query->orderBy('date', 'desc')
            ->orderBy('clock_in', 'desc')
            ->paginate(20);

        return view('attendance.records.late', compact('records'));
    }

    public function absences(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $dateFrom = $request->date_from ?? Carbon::now()->startOfMonth()->toDateString();
        $dateTo = $request->date_to ?? Carbon::now()->endOfMonth()->toDateString();

        // Get all active employees
        $employees = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
            $q->where('company_id', $companyId)->where('status', 'active');
        })->with('user')->get();

        // Get all attendance records in the date range
        $attendanceRecords = AttendanceRecord::whereHas('employee', function($q) use ($companyId) {
            $q->whereHas('user', function($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })
        ->whereBetween('date', [$dateFrom, $dateTo])
        ->get()
        ->groupBy('employee_id');

        // Find absences
        $absences = [];
        $dateRange = Carbon::parse($dateFrom)->daysUntil($dateTo);

        foreach ($employees as $employee) {
            $employeeRecords = $attendanceRecords->get($employee->id, collect());
            $employeeAbsences = [];

            foreach ($dateRange as $date) {
                $record = $employeeRecords->firstWhere('date', $date->toDateString());
                if (!$record || $record->status === 'absent') {
                    $employeeAbsences[] = $date->toDateString();
                }
            }

            if (count($employeeAbsences) > 0) {
                $absences[] = [
                    'employee' => $employee,
                    'dates' => $employeeAbsences,
                    'count' => count($employeeAbsences),
                ];
            }
        }

        return view('attendance.records.absences', compact('absences', 'dateFrom', 'dateTo'));
    }

    public function editRecords($id)
    {
        $record = AttendanceRecord::with('employee.user')->findOrFail($id);
        
        // Verify record belongs to same company
        if ($record->employee->user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized');
        }

        return view('attendance.records.edit', compact('record'));
    }

    public function updateRecord(Request $request, $id)
    {
        $record = AttendanceRecord::findOrFail($id);
        
        // Verify record belongs to same company
        if ($record->employee->user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after:clock_in',
            'status' => 'required|in:present,late,absent,half_day,on_leave',
            'notes' => 'nullable|string|max:500',
        ]);

        $data = [
            'status' => $request->status,
            'notes' => $request->notes,
        ];

        if ($request->clock_in) {
            $data['clock_in'] = Carbon::parse($request->clock_in)->toTimeString();
        }

        if ($request->clock_out) {
            $data['clock_out'] = Carbon::parse($request->clock_out)->toTimeString();
            if ($request->clock_in) {
                $clockIn = Carbon::parse($request->clock_in);
                $clockOut = Carbon::parse($request->clock_out);
                $data['hours_worked'] = round($clockIn->diffInHours($clockOut) + ($clockIn->diffInMinutes($clockOut) % 60) / 60, 2);
            }
        }

        $record->update($data);

        return redirect()->route('attendance.records.index')->with('success', 'Attendance record updated successfully');
    }

    // Attendance Reports Methods
    public function dailyReport(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;
        $date = $request->date ?? Carbon::today()->toDateString();

        $records = AttendanceRecord::whereHas('employee', function($q) use ($companyId) {
            $q->whereHas('user', function($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })
        ->where('date', $date)
        ->with('employee.user', 'employee.department', 'employee.position')
        ->orderBy('clock_in', 'asc')
        ->get();

        $stats = [
            'total' => $records->count(),
            'present' => $records->where('status', 'present')->count(),
            'late' => $records->where('status', 'late')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'on_leave' => $records->where('status', 'on_leave')->count(),
        ];

        return view('attendance.reports.daily', compact('records', 'stats', 'date'));
    }

    public function monthlyReport(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;
        $month = $request->month ?? Carbon::now()->format('Y-m');
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        $records = AttendanceRecord::whereHas('employee', function($q) use ($companyId) {
            $q->whereHas('user', function($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->with('employee.user', 'employee.department')
        ->get()
        ->groupBy('employee_id');

        $employees = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
            $q->where('company_id', $companyId)->where('status', 'active');
        })->with('user')->get();

        $reportData = [];
        foreach ($employees as $employee) {
            $employeeRecords = $records->get($employee->id, collect());
            $reportData[] = [
                'employee' => $employee,
                'total_days' => $startOfMonth->diffInDays($endOfMonth) + 1,
                'present' => $employeeRecords->where('status', 'present')->count(),
                'late' => $employeeRecords->where('status', 'late')->count(),
                'absent' => $employeeRecords->where('status', 'absent')->count(),
                'on_leave' => $employeeRecords->where('status', 'on_leave')->count(),
                'total_hours' => $employeeRecords->sum('hours_worked'),
            ];
        }

        return view('attendance.reports.monthly', compact('reportData', 'month'));
    }

    public function employeeSummary(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;
        $employeeId = $request->employee_id;

        if (!$employeeId) {
            $employees = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
                $q->where('company_id', $companyId)->where('status', 'active');
            })->with('user')->get();
            return view('attendance.reports.employee-summary', compact('employees'));
        }

        $employee = EmployeeProfile::with('user')->findOrFail($employeeId);
        
        if ($employee->user->company_id !== $companyId) {
            abort(403, 'Unauthorized');
        }

        $dateFrom = $request->date_from ?? Carbon::now()->startOfMonth()->toDateString();
        $dateTo = $request->date_to ?? Carbon::now()->endOfMonth()->toDateString();

        $records = AttendanceRecord::where('employee_id', $employeeId)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->orderBy('date', 'desc')
            ->get();

        $stats = [
            'total_days' => Carbon::parse($dateFrom)->diffInDays($dateTo) + 1,
            'present' => $records->where('status', 'present')->count(),
            'late' => $records->where('status', 'late')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'on_leave' => $records->where('status', 'on_leave')->count(),
            'total_hours' => $records->sum('hours_worked'),
            'average_hours' => $records->whereNotNull('hours_worked')->count() > 0 
                ? round($records->sum('hours_worked') / $records->whereNotNull('hours_worked')->count(), 2)
                : 0,
        ];

        return view('attendance.reports.employee-detail', compact('employee', 'records', 'stats', 'dateFrom', 'dateTo'));
    }

    public function exportData(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $query = AttendanceRecord::whereHas('employee', function($q) use ($companyId) {
            $q->whereHas('user', function($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })->with('employee.user', 'employee.department', 'employee.position');

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->orderBy('date', 'desc')->get();

        $format = $request->format ?? 'excel';

        if ($format === 'pdf') {
            return view('attendance.reports.export-pdf', compact('records'));
        }

        // Excel export would typically use a package like Maatwebsite\Excel
        // For now, return CSV
        $filename = 'attendance_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($records) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Employee', 'Department', 'Position', 'Clock In', 'Clock Out', 'Hours Worked', 'Status', 'Notes']);

            foreach ($records as $record) {
                fputcsv($file, [
                    $record->date->format('Y-m-d'),
                    $record->employee->user->first_name . ' ' . $record->employee->user->last_name,
                    $record->employee->department->name ?? 'N/A',
                    $record->employee->position->name ?? 'N/A',
                    $record->clock_in ? Carbon::parse($record->clock_in)->format('H:i') : 'N/A',
                    $record->clock_out ? Carbon::parse($record->clock_out)->format('H:i') : 'N/A',
                    $record->hours_worked ?? '0',
                    ucfirst($record->status),
                    $record->notes ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
