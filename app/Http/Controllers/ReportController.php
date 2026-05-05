<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\EmployeeProfile;
use App\Models\AttendanceRecord;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Department;
use App\Models\Report as ReportModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function leaveSummary(Request $request)
    {
        $user = auth()->user();
        $year = $request->get('year', Carbon::now()->year);
        $leaveTypeId = $request->get('leave_type_id');
        $departmentId = $request->get('department_id');

        $query = LeaveRequest::whereHas('employee', function($q) use ($user) {
            $q->whereHas('user', function($q2) use ($user) {
                $q2->where('company_id', $user->company_id);
            });
        })
        ->whereYear('start_date', $year);

        if ($leaveTypeId) {
            $query->where('leave_type_id', $leaveTypeId);
        }

        if ($departmentId) {
            $query->whereHas('employee', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $leaves = $query->with(['employee.user', 'leaveType'])->get();

        $summary = [
            'total_requests' => $leaves->count(),
            'approved' => $leaves->where('status', 'approved')->count(),
            'pending' => $leaves->where('status', 'pending')->count(),
            'rejected' => $leaves->where('status', 'rejected')->count(),
            'total_days' => $leaves->where('status', 'approved')->sum('days_requested'),
            'by_type' => $leaves->groupBy('leave_type_id')->map(function($group) {
                return [
                    'name' => $group->first()->leaveType->name,
                    'count' => $group->count(),
                    'days' => $group->where('status', 'approved')->sum('days_requested'),
                ];
            }),
        ];

        $leaveTypes = LeaveType::where('company_id', $user->company_id)->get();
        
        return view('reports.leave-summary', compact('summary', 'year', 'leaveTypes', 'leaveTypeId'));
    }

    public function leaveDetailed(Request $request)
    {
        $user = auth()->user();
        $year = $request->get('year', Carbon::now()->year);
        $leaveTypeId = $request->get('leave_type_id');
        $status = $request->get('status');

        $query = LeaveRequest::whereHas('employee', function($q) use ($user) {
            $q->whereHas('user', function($q2) use ($user) {
                $q2->where('company_id', $user->company_id);
            });
        })
        ->whereYear('start_date', $year);

        if ($leaveTypeId) {
            $query->where('leave_type_id', $leaveTypeId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $leaves = $query->with(['employee.user', 'leaveType', 'approvedBy'])
            ->orderBy('start_date', 'desc')
            ->paginate(20);

        $leaveTypes = LeaveType::where('company_id', $user->company_id)->get();
        
        return view('reports.leave-detailed', compact('leaves', 'year', 'leaveTypes', 'leaveTypeId', 'status'));
    }

    public function leaveExport(Request $request)
    {
        $user = auth()->user();
        $year = $request->get('year', Carbon::now()->year);
        $format = $request->get('format', 'excel');

        $leaves = LeaveRequest::whereHas('employee', function($q) use ($user) {
            $q->whereHas('user', function($q2) use ($user) {
                $q2->where('company_id', $user->company_id);
            });
        })
        ->whereYear('start_date', $year)
        ->with(['employee.user', 'leaveType', 'approvedBy'])
        ->get();

        if ($format === 'pdf') {
            // PDF export logic would go here
            return view('reports.leave-export-pdf', compact('leaves', 'year'));
        } else {
            // Excel export logic would go here
            return view('reports.leave-export-excel', compact('leaves', 'year'));
        }
    }

    // Employee Reports
    public function employeeList(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $query = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->with(['user.role', 'department', 'position']);

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $employees = $query->orderBy('created_at', 'desc')->paginate(20);
        $departments = Department::where('company_id', $companyId)->get();

        return view('reports.employees.list', compact('employees', 'departments'));
    }

    public function headcountAnalysis(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        // Total headcount
        $totalHeadcount = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
            $q->where('company_id', $companyId)->where('status', 'active');
        })->count();

        // By department
        $byDepartment = Department::where('company_id', $companyId)
            ->withCount(['employees' => function($q) use ($companyId) {
                $q->whereHas('user', function($q2) use ($companyId) {
                    $q2->where('company_id', $companyId)->where('status', 'active');
                });
            }])
            ->get();

        // By position
        $byPosition = DB::table('employee_profiles')
            ->join('users', 'employee_profiles.user_id', '=', 'users.id')
            ->join('positions', 'employee_profiles.position_id', '=', 'positions.id')
            ->where('users.company_id', $companyId)
            ->where('users.status', 'active')
            ->select('positions.title as name', DB::raw('count(*) as count'))
            ->groupBy('positions.title')
            ->get();

        // By employment type
        $byEmploymentType = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
            $q->where('company_id', $companyId)->where('status', 'active');
        })
        ->select('employment_type', DB::raw('count(*) as count'))
        ->groupBy('employment_type')
        ->get();

        // Growth over time (last 12 months)
        $growthData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
                $q->where('company_id', $companyId)->where('status', 'active');
            })
            ->where('hire_date', '<=', $date->endOfMonth())
            ->count();
            $growthData[] = [
                'month' => $date->format('M Y'),
                'count' => $count,
            ];
        }

        return view('reports.employees.headcount', compact('totalHeadcount', 'byDepartment', 'byPosition', 'byEmploymentType', 'growthData'));
    }

    public function demographics(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        // By gender
        $byGender = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
            $q->where('company_id', $companyId)->where('status', 'active');
        })
        ->select('gender', DB::raw('count(*) as count'))
        ->groupBy('gender')
        ->get();

        // By age groups
        $ageGroups = [
            '18-25' => 0,
            '26-35' => 0,
            '36-45' => 0,
            '46-55' => 0,
            '56+' => 0,
        ];

        $employees = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
            $q->where('company_id', $companyId)->where('status', 'active');
        })->whereNotNull('date_of_birth')->get();

        foreach ($employees as $employee) {
            $age = Carbon::parse($employee->date_of_birth)->age;
            if ($age >= 18 && $age <= 25) $ageGroups['18-25']++;
            elseif ($age >= 26 && $age <= 35) $ageGroups['26-35']++;
            elseif ($age >= 36 && $age <= 45) $ageGroups['36-45']++;
            elseif ($age >= 46 && $age <= 55) $ageGroups['46-55']++;
            else $ageGroups['56+']++;
        }

        // By tenure
        $tenureGroups = [
            '0-1 years' => 0,
            '1-3 years' => 0,
            '3-5 years' => 0,
            '5-10 years' => 0,
            '10+ years' => 0,
        ];

        foreach ($employees as $employee) {
            if ($employee->hire_date) {
                $years = Carbon::parse($employee->hire_date)->diffInYears(Carbon::now());
                if ($years < 1) $tenureGroups['0-1 years']++;
                elseif ($years < 3) $tenureGroups['1-3 years']++;
                elseif ($years < 5) $tenureGroups['3-5 years']++;
                elseif ($years < 10) $tenureGroups['5-10 years']++;
                else $tenureGroups['10+ years']++;
            }
        }

        return view('reports.employees.demographics', compact('byGender', 'ageGroups', 'tenureGroups'));
    }

    // Attendance Analytics
    public function attendanceTrends(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;
        $months = $request->get('months', 6);

        $trends = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $records = AttendanceRecord::whereHas('employee', function($q) use ($companyId) {
                $q->whereHas('user', function($q2) use ($companyId) {
                    $q2->where('company_id', $companyId);
                });
            })
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

            $trends[] = [
                'month' => $date->format('M Y'),
                'present' => $records->where('status', 'present')->count(),
                'late' => $records->where('status', 'late')->count(),
                'absent' => $records->where('status', 'absent')->count(),
                'total_hours' => $records->sum('hours_worked'),
            ];
        }

        return view('reports.attendance.trends', compact('trends', 'months'));
    }

    public function departmentComparison(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        $departments = Department::where('company_id', $companyId)->get();
        $comparison = [];

        foreach ($departments as $department) {
            $employees = $department->employees()->whereHas('user', function($q) use ($companyId) {
                $q->where('company_id', $companyId)->where('status', 'active');
            })->pluck('id');

            $records = AttendanceRecord::whereIn('employee_id', $employees)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->get();

            $totalEmployees = $employees->count();
            $workingDays = $startOfMonth->diffInDays($endOfMonth) + 1;

            $comparison[] = [
                'department' => $department->name,
                'total_employees' => $totalEmployees,
                'total_records' => $records->count(),
                'present' => $records->where('status', 'present')->count(),
                'late' => $records->where('status', 'late')->count(),
                'absent' => $records->where('status', 'absent')->count(),
                'attendance_rate' => $totalEmployees > 0 && $workingDays > 0 
                    ? round(($records->where('status', '!=', 'absent')->count() / ($totalEmployees * $workingDays)) * 100, 2)
                    : 0,
                'average_hours' => $records->whereNotNull('hours_worked')->count() > 0
                    ? round($records->sum('hours_worked') / $records->whereNotNull('hours_worked')->count(), 2)
                    : 0,
            ];
        }

        return view('reports.attendance.department-comparison', compact('comparison', 'month'));
    }

    public function timeAnalysis(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        $records = AttendanceRecord::whereHas('employee', function($q) use ($companyId) {
            $q->whereHas('user', function($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->whereNotNull('clock_in')
        ->whereNotNull('clock_out')
        ->get();

        // Average clock in/out times
        $clockInTimes = [];
        $clockOutTimes = [];
        foreach ($records as $record) {
            $clockIn = Carbon::parse($record->clock_in);
            $clockOut = Carbon::parse($record->clock_out);
            $clockInTimes[] = $clockIn->hour * 60 + $clockIn->minute;
            $clockOutTimes[] = $clockOut->hour * 60 + $clockOut->minute;
        }

        $avgClockIn = count($clockInTimes) > 0 
            ? Carbon::createFromTime(0, 0, 0)->addMinutes(array_sum($clockInTimes) / count($clockInTimes))
            : null;
        $avgClockOut = count($clockOutTimes) > 0
            ? Carbon::createFromTime(0, 0, 0)->addMinutes(array_sum($clockOutTimes) / count($clockOutTimes))
            : null;

        // Hours distribution
        $hoursDistribution = [
            '0-4' => 0,
            '4-6' => 0,
            '6-8' => 0,
            '8-10' => 0,
            '10+' => 0,
        ];

        foreach ($records as $record) {
            $hours = $record->hours_worked ?? 0;
            if ($hours < 4) $hoursDistribution['0-4']++;
            elseif ($hours < 6) $hoursDistribution['4-6']++;
            elseif ($hours < 8) $hoursDistribution['6-8']++;
            elseif ($hours < 10) $hoursDistribution['8-10']++;
            else $hoursDistribution['10+']++;
        }

        // Total hours
        $totalHours = $records->sum('hours_worked');
        $averageHours = $records->count() > 0 ? round($totalHours / $records->count(), 2) : 0;

        return view('reports.attendance.time-analysis', compact('avgClockIn', 'avgClockOut', 'hoursDistribution', 'totalHours', 'averageHours', 'month'));
    }

    // Leave Analytics
    public function leaveUtilization(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;
        $year = $request->get('year', Carbon::now()->year);

        $leaveTypes = LeaveType::where('company_id', $companyId)->get();
        $utilization = [];

        foreach ($leaveTypes as $type) {
            $balances = LeaveBalance::whereHas('employee', function($q) use ($companyId) {
                $q->whereHas('user', function($q2) use ($companyId) {
                    $q2->where('company_id', $companyId);
                });
            })
            ->where('leave_type_id', $type->id)
            ->get();

            $requests = LeaveRequest::whereHas('employee', function($q) use ($companyId) {
                $q->whereHas('user', function($q2) use ($companyId) {
                    $q2->where('company_id', $companyId);
                });
            })
            ->where('leave_type_id', $type->id)
            ->whereYear('start_date', $year)
            ->where('status', 'approved')
            ->get();

            $utilization[] = [
                'type' => $type->name,
                'total_allocated' => $balances->sum('allocated_days'),
                'total_used' => $requests->sum('days_requested'),
                'total_remaining' => $balances->sum('remaining_days'),
                'utilization_rate' => $balances->sum('allocated_days') > 0
                    ? round(($requests->sum('days_requested') / $balances->sum('allocated_days')) * 100, 2)
                    : 0,
            ];
        }

        return view('reports.leaves.utilization', compact('utilization', 'year'));
    }

    public function leaveTrends(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;
        $months = $request->get('months', 12);

        $trends = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $requests = LeaveRequest::whereHas('employee', function($q) use ($companyId) {
                $q->whereHas('user', function($q2) use ($companyId) {
                    $q2->where('company_id', $companyId);
                });
            })
            ->whereBetween('start_date', [$startOfMonth, $endOfMonth])
            ->get();

            $trends[] = [
                'month' => $date->format('M Y'),
                'total_requests' => $requests->count(),
                'approved' => $requests->where('status', 'approved')->count(),
                'pending' => $requests->where('status', 'pending')->count(),
                'rejected' => $requests->where('status', 'rejected')->count(),
                'total_days' => $requests->where('status', 'approved')->sum('days_requested'),
            ];
        }

        return view('reports.leaves.trends', compact('trends', 'months'));
    }

    public function leaveCostAnalysis(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;
        $year = $request->get('year', Carbon::now()->year);

        // This would typically calculate cost based on employee salaries and leave days
        // For now, we'll provide a basic structure
        $leaves = LeaveRequest::whereHas('employee', function($q) use ($companyId) {
            $q->whereHas('user', function($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        })
        ->whereYear('start_date', $year)
        ->where('status', 'approved')
        ->with(['employee.user', 'leaveType'])
        ->get();

        $costByType = [];
        foreach ($leaves->groupBy('leave_type_id') as $typeId => $group) {
            $type = $group->first()->leaveType;
            $totalDays = $group->sum('days_requested');
            // Cost calculation would go here (would need salary data)
            $costByType[] = [
                'type' => $type->name,
                'total_days' => $totalDays,
                'estimated_cost' => 0, // Placeholder
            ];
        }

        return view('reports.leaves.cost-analysis', compact('costByType', 'year'));
    }

    // Document Reports
    public function documentInventory(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $documents = Document::where('company_id', $companyId)
            ->with(['category', 'employee.user'])
            ->get();

        $inventory = [
            'total' => $documents->count(),
            'by_category' => $documents->groupBy('category_id')->map(function($group) {
                return [
                    'name' => $group->first()->category->name,
                    'count' => $group->count(),
                ];
            }),
            'expiring_soon' => $documents->filter(function($doc) {
                return $doc->expires_at && 
                       $doc->expires_at >= Carbon::today() && 
                       $doc->expires_at <= Carbon::today()->addDays(30);
            })->count(),
            'expired' => $documents->filter(function($doc) {
                return $doc->expires_at && $doc->expires_at < Carbon::today();
            })->count(),
            'verified' => $documents->where('is_verified', true)->count(),
            'unverified' => $documents->where('is_verified', false)->count(),
        ];

        return view('reports.documents.inventory', compact('inventory', 'documents'));
    }

    public function complianceStatus(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $categories = DocumentCategory::where('company_id', $companyId)
            ->where('requires_expiry', true)
            ->get();

        $compliance = [];
        foreach ($categories as $category) {
            $requiredDocs = Document::where('company_id', $companyId)
                ->where('category_id', $category->id)
                ->get();

            $activeEmployees = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
                $q->where('company_id', $companyId)->where('status', 'active');
            })->count();

            $employeesWithDocs = $requiredDocs->pluck('employee_id')->unique()->count();
            $complianceRate = $activeEmployees > 0 
                ? round(($employeesWithDocs / $activeEmployees) * 100, 2)
                : 0;

            $expiring = $requiredDocs->filter(function($doc) {
                return $doc->expires_at && 
                       $doc->expires_at >= Carbon::today() && 
                       $doc->expires_at <= Carbon::today()->addDays(30);
            })->count();

            $expired = $requiredDocs->filter(function($doc) {
                return $doc->expires_at && $doc->expires_at < Carbon::today();
            })->count();

            $compliance[] = [
                'category' => $category->name,
                'required' => $activeEmployees,
                'compliant' => $employeesWithDocs,
                'compliance_rate' => $complianceRate,
                'expiring' => $expiring,
                'expired' => $expired,
            ];
        }

        return view('reports.documents.compliance', compact('compliance'));
    }

    // Custom Reports
    public function reportBuilder()
    {
        $user = auth()->user();
        $reportTypes = ['employee', 'attendance', 'leave', 'document'];
        
        return view('reports.custom.builder', compact('reportTypes'));
    }

    public function savedReports()
    {
        $user = auth()->user();
        $reports = ReportModel::where('company_id', $user->company_id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('reports.custom.saved', compact('reports'));
    }

    public function scheduledReports()
    {
        $user = auth()->user();
        $reports = ReportModel::where('company_id', $user->company_id)
            ->where('is_scheduled', true)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('reports.custom.scheduled', compact('reports'));
    }

    // Export Center
    public function exportToExcel(Request $request)
    {
        $user = auth()->user();
        $type = $request->get('type', 'employee');
        
        // This would use a package like Maatwebsite\Excel
        // For now, return a view with instructions
        return view('reports.exports.excel', compact('type'));
    }

    public function exportToPdf(Request $request)
    {
        $user = auth()->user();
        $type = $request->get('type', 'employee');
        
        // This would use a package like barryvdh/laravel-dompdf
        // For now, return a view with instructions
        return view('reports.exports.pdf', compact('type'));
    }

    public function bulkExports(Request $request)
    {
        $user = auth()->user();
        
        return view('reports.exports.bulk');
    }
}
