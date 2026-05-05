<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::where('company_id', auth()->user()->company_id)
            ->with('manager')
            ->paginate(15);
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        // Get all active users who could be managers (have team_lead, department_head, hr_manager, or company_admin role)
        $users = \App\Models\User::where('company_id', auth()->user()->company_id)
            ->where('status', 'active')
            ->whereHas('role', function($q) {
                $q->whereIn('slug', ['team_lead', 'department_head', 'hr_manager', 'company_admin']);
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
        
        return view('departments.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'manager_id' => ['nullable', 'exists:users,id'],
        ]);

        Department::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'description' => $request->description,
            'manager_id' => $request->manager_id,
            'status' => 'active',
        ]);

        return redirect()->route('departments.index')->with('success', 'Department created successfully');
    }

    public function show(Department $department)
    {
        $department->load('manager', 'positions', 'employees.user');
        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        // Get all active users who could be managers
        // Also include employees who belong to this department (they can be promoted to manager)
        $users = \App\Models\User::where('company_id', auth()->user()->company_id)
            ->where('status', 'active')
            ->where(function($q) use ($department) {
                // Users with manager roles
                $q->whereHas('role', function($query) {
                    $query->whereIn('slug', ['team_lead', 'department_head', 'hr_manager', 'company_admin']);
                })
                // OR employees who belong to this department
                ->orWhereHas('employeeProfile', function($query) use ($department) {
                    $query->where('department_id', $department->id);
                });
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
        
        return view('departments.edit', compact('department', 'users'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'manager_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $department->update($request->only(['name', 'description', 'manager_id', 'status']));

        return redirect()->route('departments.index')->with('success', 'Department updated successfully');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully');
    }

    public function heads()
    {
        // Show all departments; indicate when a manager is missing
        $departments = Department::where('company_id', auth()->user()->company_id)
            ->with([
                'manager',
                'manager.role',
                'manager.employeeProfile',
                'manager.employeeProfile.position'
            ])
            ->get();

        return view('departments.heads', compact('departments'));
    }
}
