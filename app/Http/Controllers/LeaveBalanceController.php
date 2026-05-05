<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\EmployeeProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveBalanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $currentYear = Carbon::now()->year;
        
        $balances = LeaveBalance::whereHas('employee', function($q) use ($user) {
            $q->whereHas('user', function($q2) use ($user) {
                $q2->where('company_id', $user->company_id);
            });
        })
        ->where('year', $currentYear)
        ->with(['employee.user', 'leaveType'])
        ->orderBy('employee_id')
        ->paginate(20);

        return view('leave-balances.index', compact('balances', 'currentYear'));
    }

    public function show(EmployeeProfile $employee)
    {
        $user = auth()->user();
        if ($employee->user->company_id !== $user->company_id) {
            abort(403);
        }

        $currentYear = Carbon::now()->year;
        $balances = LeaveBalance::where('employee_id', $employee->id)
            ->where('year', $currentYear)
            ->with('leaveType')
            ->get();

        return view('leave-balances.show', compact('employee', 'balances', 'currentYear'));
    }

    public function bulkAdjustments()
    {
        $user = auth()->user();
        $leaveTypes = LeaveType::where('company_id', $user->company_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        
        $employees = EmployeeProfile::whereHas('user', function($q) use ($user) {
            $q->where('company_id', $user->company_id)
              ->where('status', 'active');
        })
        ->with('user')
        ->orderBy('user_id')
        ->get();

        return view('leave-balances.bulk-adjustments', compact('leaveTypes', 'employees'));
    }

    public function processBulkAdjustments(Request $request)
    {
        $request->validate([
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'adjustment_type' => ['required', 'in:add,subtract,set'],
            'days' => ['required', 'numeric', 'min:0'],
            'employee_ids' => ['required', 'array'],
            'employee_ids.*' => ['exists:employee_profiles,id'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $user = auth()->user();
        $currentYear = Carbon::now()->year;
        $leaveType = LeaveType::findOrFail($request->leave_type_id);

        // Verify leave type belongs to company
        if ($leaveType->company_id !== $user->company_id) {
            return redirect()->back()->with('error', 'Invalid leave type');
        }

        DB::transaction(function() use ($request, $currentYear, $leaveType, $user) {
            foreach ($request->employee_ids as $employeeId) {
                $employee = EmployeeProfile::findOrFail($employeeId);
                
                // Verify employee belongs to company
                if ($employee->user->company_id !== $user->company_id) {
                    continue;
                }

                $balance = LeaveBalance::where('employee_id', $employeeId)
                    ->where('leave_type_id', $leaveType->id)
                    ->where('year', $currentYear)
                    ->first();

                if (!$balance) {
                    $balance = LeaveBalance::create([
                        'employee_id' => $employeeId,
                        'leave_type_id' => $leaveType->id,
                        'year' => $currentYear,
                        'total_days' => $leaveType->days_allowed,
                        'used_days' => 0,
                        'pending_days' => 0,
                        'remaining_days' => $leaveType->days_allowed,
                    ]);
                }

                $adjustmentDays = $request->days;
                switch ($request->adjustment_type) {
                    case 'add':
                        $balance->total_days += $adjustmentDays;
                        $balance->remaining_days += $adjustmentDays;
                        break;
                    case 'subtract':
                        $balance->total_days = max(0, $balance->total_days - $adjustmentDays);
                        $balance->remaining_days = max(0, $balance->remaining_days - $adjustmentDays);
                        break;
                    case 'set':
                        $balance->total_days = $adjustmentDays;
                        $balance->remaining_days = $adjustmentDays - $balance->used_days - $balance->pending_days;
                        break;
                }

                $balance->save();
            }
        });

        return redirect()->route('leave-balances.index')->with('success', 'Bulk adjustments processed successfully');
    }

    public function adjust(Request $request, EmployeeProfile $employee)
    {
        $user = auth()->user();
        if ($employee->user->company_id !== $user->company_id) {
            abort(403);
        }

        $request->validate([
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'adjustment_type' => ['required', 'in:add,subtract,set'],
            'days' => ['required', 'numeric', 'min:0'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $currentYear = Carbon::now()->year;
        $leaveType = LeaveType::findOrFail($request->leave_type_id);

        if ($leaveType->company_id !== $user->company_id) {
            return redirect()->back()->with('error', 'Invalid leave type');
        }

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
                'used_days' => 0,
                'pending_days' => 0,
                'remaining_days' => $leaveType->days_allowed,
            ]);
        }

        $adjustmentDays = $request->days;
        switch ($request->adjustment_type) {
            case 'add':
                $balance->total_days += $adjustmentDays;
                $balance->remaining_days += $adjustmentDays;
                break;
            case 'subtract':
                $balance->total_days = max(0, $balance->total_days - $adjustmentDays);
                $balance->remaining_days = max(0, $balance->remaining_days - $adjustmentDays);
                break;
            case 'set':
                $balance->total_days = $adjustmentDays;
                $balance->remaining_days = $adjustmentDays - $balance->used_days - $balance->pending_days;
                break;
        }

        $balance->save();

        return redirect()->route('leave-balances.show', $employee->id)->with('success', 'Leave balance adjusted successfully');
    }
}
