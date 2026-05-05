<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveRequest;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\EmployeeProfile;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Traits\ScopesDataByRole;

class LeaveController extends Controller
{
    use ScopesDataByRole;
    public function index()
    {
        $user = auth()->user();
        $leaves = LeaveRequest::whereHas('employee', function($q) use ($user) {
            $q->whereHas('user', function($q2) use ($user) {
                $q2->where('company_id', $user->company_id);
            });
        })->with(['employee.user', 'leaveType'])->orderBy('created_at', 'desc')->paginate(15);

        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        // Ensure user has an employee profile - all employees should be able to apply for leave
        $employee = auth()->user()->employeeProfile;
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You must have an employee profile to request leave. Please contact HR to set up your employee profile.');
        }

        $leaveTypes = LeaveType::where('company_id', auth()->user()->company_id)
            ->where('status', 'active')
            ->get();
        
        return view('leaves.create', compact('leaveTypes'));
    }

    public function store(StoreLeaveRequest $request)
    {
        $employee = auth()->user()->employeeProfile;
        if (!$employee) {
            return redirect()->back()->with('error', 'Employee profile not found');
        }

        $leaveType = LeaveType::findOrFail($request->leave_type_id);
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $daysRequested = $startDate->diffInDays($endDate) + 1;

        // Check leave balance
        $currentYear = Carbon::now()->year;
        $balance = LeaveBalance::where('employee_id', $employee->id)
            ->where('leave_type_id', $leaveType->id)
            ->where('year', $currentYear)
            ->first();

        if (!$balance) {
            $balance = LeaveBalance::create([
                'employee_id' => $employee->id,
                'leave_type_id' => $leaveType->id,
                'year' => $currentYear,
                'total_days' => $leaveType->days_allowed,
                'remaining_days' => $leaveType->days_allowed,
            ]);
        }

        if ($balance->remaining_days < $daysRequested) {
            return redirect()->back()->with('error', 'Insufficient leave balance. Available: ' . $balance->remaining_days . ' days');
        }

        // Handle file upload
        $documentPath = null;
        if ($request->hasFile('supporting_document')) {
            $documentPath = $request->file('supporting_document')->store('leave_documents', 'public');
        }

        // Create leave request
        $leaveRequest = LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $leaveType->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'days_requested' => $daysRequested,
            'reason' => $request->reason,
            'supporting_document' => $documentPath,
            'status' => 'pending',
        ]);

        // Update pending days
        $balance->pending_days += $daysRequested;
        $balance->save();

        return redirect()->route('leaves.my-leaves')->with('success', 'Leave request submitted successfully');
    }

    public function myLeaves()
    {
        $employee = auth()->user()->employeeProfile;
        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee profile not found');
        }

        $leaves = LeaveRequest::where('employee_id', $employee->id)
            ->with('leaveType')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('leaves.my-leaves', compact('leaves'));
    }

    public function pending()
    {
        $user = auth()->user();
        
        // Get employee IDs based on role scope
        if ($user->hasRole('hr_manager') || $user->hasRole('company_admin')) {
            // Company-wide
            $employeeIds = EmployeeProfile::whereHas('user', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->pluck('id');
        } elseif ($user->hasRole('department_head')) {
            // Department-scoped
            $employeeProfile = $user->employeeProfile;
            if ($employeeProfile && $employeeProfile->department_id) {
                $employeeIds = EmployeeProfile::where('department_id', $employeeProfile->department_id)->pluck('id');
            } else {
                $employeeIds = collect([]);
            }
        } elseif ($user->hasRole('team_lead')) {
            // Team-scoped (direct reports)
            $employeeIds = EmployeeProfile::where('manager_id', $user->id)->pluck('id');
        } else {
            // Employee: only their own
            $employeeIds = EmployeeProfile::where('user_id', $user->id)->pluck('id');
        }
        
        $leaves = LeaveRequest::whereIn('employee_id', $employeeIds)
            ->where('status', 'pending')
            ->with(['employee.user', 'leaveType'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('leaves.pending', compact('leaves'));
    }

    public function approve(Request $request, LeaveRequest $leave)
    {
        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approver_comments' => $request->comments,
        ]);

        // Update leave balance
        $currentYear = Carbon::now()->year;
        $balance = LeaveBalance::where('employee_id', $leave->employee_id)
            ->where('leave_type_id', $leave->leave_type_id)
            ->where('year', $currentYear)
            ->first();

        if ($balance) {
            $balance->pending_days -= $leave->days_requested;
            $balance->used_days += $leave->days_requested;
            $balance->remaining_days = $balance->total_days - $balance->used_days - $balance->pending_days;
            $balance->save();
        }

        return redirect()->back()->with('success', 'Leave request approved');
    }

    public function reject(Request $request, LeaveRequest $leave)
    {
        $request->validate([
            'comments' => ['required', 'string', 'max:500'],
        ]);

        $leave->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approver_comments' => $request->comments,
        ]);

        // Update leave balance
        $currentYear = Carbon::now()->year;
        $balance = LeaveBalance::where('employee_id', $leave->employee_id)
            ->where('leave_type_id', $leave->leave_type_id)
            ->where('year', $currentYear)
            ->first();

        if ($balance) {
            $balance->pending_days -= $leave->days_requested;
            $balance->remaining_days = $balance->total_days - $balance->used_days - $balance->pending_days;
            $balance->save();
        }

        return redirect()->back()->with('success', 'Leave request rejected');
    }

    public function approved()
    {
        $user = auth()->user();
        
        // Get employee IDs based on role scope
        if ($user->hasRole('hr_manager') || $user->hasRole('company_admin')) {
            $employeeIds = EmployeeProfile::whereHas('user', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->pluck('id');
        } elseif ($user->hasRole('department_head')) {
            $employeeProfile = $user->employeeProfile;
            if ($employeeProfile && $employeeProfile->department_id) {
                $employeeIds = EmployeeProfile::where('department_id', $employeeProfile->department_id)->pluck('id');
            } else {
                $employeeIds = collect([]);
            }
        } elseif ($user->hasRole('team_lead')) {
            $employeeIds = EmployeeProfile::where('manager_id', $user->id)->pluck('id');
        } else {
            $employeeIds = EmployeeProfile::where('user_id', $user->id)->pluck('id');
        }
        
        $leaves = LeaveRequest::whereIn('employee_id', $employeeIds)
            ->where('status', 'approved')
            ->with(['employee.user', 'leaveType', 'approvedBy'])
            ->orderBy('approved_at', 'desc')
            ->paginate(15);

        return view('leaves.approved', compact('leaves'));
    }

    public function rejected()
    {
        $user = auth()->user();
        
        // Get employee IDs based on role scope
        if ($user->hasRole('hr_manager') || $user->hasRole('company_admin')) {
            $employeeIds = EmployeeProfile::whereHas('user', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->pluck('id');
        } elseif ($user->hasRole('department_head')) {
            $employeeProfile = $user->employeeProfile;
            if ($employeeProfile && $employeeProfile->department_id) {
                $employeeIds = EmployeeProfile::where('department_id', $employeeProfile->department_id)->pluck('id');
            } else {
                $employeeIds = collect([]);
            }
        } elseif ($user->hasRole('team_lead')) {
            $employeeIds = EmployeeProfile::where('manager_id', $user->id)->pluck('id');
        } else {
            $employeeIds = EmployeeProfile::where('user_id', $user->id)->pluck('id');
        }
        
        $leaves = LeaveRequest::whereIn('employee_id', $employeeIds)
            ->where('status', 'rejected')
            ->with(['employee.user', 'leaveType', 'approvedBy'])
            ->orderBy('approved_at', 'desc')
            ->paginate(15);

        return view('leaves.rejected', compact('leaves'));
    }

    public function calendar()
    {
        $user = auth()->user();
        $employeeIds = EmployeeProfile::whereHas('user', function($q) use ($user) {
            $q->where('company_id', $user->company_id);
        })->pluck('id');
        
        $leaves = LeaveRequest::whereIn('employee_id', $employeeIds)
            ->whereIn('status', ['approved', 'pending'])
            ->with(['employee.user', 'leaveType'])
            ->get();

        $events = $leaves->map(function($leave) {
            $color = $leave->status === 'approved' ? '#10b981' : '#f59e0b';
            return [
                'title' => $leave->employee->user->first_name . ' ' . $leave->employee->user->last_name . ' - ' . $leave->leaveType->name,
                'start' => $leave->start_date->format('Y-m-d'),
                'end' => $leave->end_date->format('Y-m-d'),
                'color' => $color,
                'url' => route('leaves.index') . '?id=' . $leave->id,
            ];
        });

        return view('leaves.calendar', compact('events'));
    }
}
