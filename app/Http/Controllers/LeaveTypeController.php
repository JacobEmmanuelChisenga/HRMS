<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveType;
use Illuminate\Validation\Rule;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::where('company_id', auth()->user()->company_id)
            ->orderBy('name')
            ->paginate(15);

        return view('leave-types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('leave-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'days_allowed' => ['required', 'numeric', 'min:0', 'max:365'],
            'accrual_rate' => ['nullable', 'numeric', 'min:0', 'max:31'],
            'requires_approval' => ['boolean'],
            'is_paid' => ['boolean'],
            'carries_forward' => ['boolean'],
            'max_carry_forward_days' => ['nullable', 'numeric', 'min:0'],
            'requires_document' => ['boolean'],
            'gender_restriction' => ['nullable', 'in:none,male,female'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        LeaveType::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'description' => $request->description,
            'days_allowed' => $request->days_allowed,
            'accrual_rate' => $request->accrual_rate,
            'requires_approval' => $request->has('requires_approval'),
            'is_paid' => $request->has('is_paid'),
            'carries_forward' => $request->has('carries_forward'),
            'max_carry_forward_days' => $request->max_carry_forward_days ?? 0,
            'requires_document' => $request->has('requires_document'),
            'gender_restriction' => $request->gender_restriction ?? 'none',
            'status' => $request->status,
        ]);

        return redirect()->route('leave-types.index')->with('success', 'Leave type created successfully');
    }

    public function show(LeaveType $leaveType)
    {
        if ($leaveType->company_id !== auth()->user()->company_id) {
            abort(403);
        }
        $leaveType->load(['leaveRequests.employee.user', 'leaveBalances.employee.user']);
        return view('leave-types.show', compact('leaveType'));
    }

    public function edit(LeaveType $leaveType)
    {
        if ($leaveType->company_id !== auth()->user()->company_id) {
            abort(403);
        }
        return view('leave-types.edit', compact('leaveType'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        if ($leaveType->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'days_allowed' => ['required', 'numeric', 'min:0', 'max:365'],
            'accrual_rate' => ['nullable', 'numeric', 'min:0', 'max:31'],
            'requires_approval' => ['boolean'],
            'is_paid' => ['boolean'],
            'carries_forward' => ['boolean'],
            'max_carry_forward_days' => ['nullable', 'numeric', 'min:0'],
            'requires_document' => ['boolean'],
            'gender_restriction' => ['nullable', 'in:none,male,female'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $leaveType->update([
            'name' => $request->name,
            'description' => $request->description,
            'days_allowed' => $request->days_allowed,
            'accrual_rate' => $request->accrual_rate,
            'requires_approval' => $request->has('requires_approval'),
            'is_paid' => $request->has('is_paid'),
            'carries_forward' => $request->has('carries_forward'),
            'max_carry_forward_days' => $request->max_carry_forward_days ?? 0,
            'requires_document' => $request->has('requires_document'),
            'gender_restriction' => $request->gender_restriction ?? 'none',
            'status' => $request->status,
        ]);

        return redirect()->route('leave-types.index')->with('success', 'Leave type updated successfully');
    }

    public function destroy(LeaveType $leaveType)
    {
        if ($leaveType->company_id !== auth()->user()->company_id) {
            abort(403);
        }
        $leaveType->delete();
        return redirect()->route('leave-types.index')->with('success', 'Leave type deleted successfully');
    }

    public function policies(LeaveType $leaveType)
    {
        if ($leaveType->company_id !== auth()->user()->company_id) {
            abort(403);
        }
        return view('leave-types.policies', compact('leaveType'));
    }

    public function updatePolicies(Request $request, LeaveType $leaveType)
    {
        if ($leaveType->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $request->validate([
            'requires_approval' => ['boolean'],
            'is_paid' => ['boolean'],
            'carries_forward' => ['boolean'],
            'max_carry_forward_days' => ['nullable', 'numeric', 'min:0'],
            'requires_document' => ['boolean'],
            'approval_workflow' => ['nullable', 'string'],
            'minimum_notice_days' => ['nullable', 'numeric', 'min:0'],
            'maximum_consecutive_days' => ['nullable', 'numeric', 'min:0'],
        ]);

        $leaveType->update([
            'requires_approval' => $request->has('requires_approval'),
            'is_paid' => $request->has('is_paid'),
            'carries_forward' => $request->has('carries_forward'),
            'max_carry_forward_days' => $request->max_carry_forward_days,
            'requires_document' => $request->has('requires_document'),
        ]);

        return redirect()->route('leave-types.policies', $leaveType->id)->with('success', 'Policies updated successfully');
    }
}
