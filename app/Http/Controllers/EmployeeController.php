<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use Illuminate\Http\Request;
use App\Models\EmployeeProfile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Traits\ScopesDataByRole;

class EmployeeController extends Controller
{
    use ScopesDataByRole;

    public function index()
    {
        $query = EmployeeProfile::query();
        $this->scopeEmployees($query);
        $employees = $query->with(['user', 'department', 'position'])->paginate(15);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $companyId = auth()->user()->company_id;
        // Get all departments (not just active) so user can see what exists
        $departments = \App\Models\Department::where('company_id', $companyId)
            ->orderBy('name')
            ->get();
        $positions = \App\Models\Position::where('company_id', $companyId)
            ->whereNotNull('department_id')
            ->get();
        
        return view('employees.create', compact('departments', 'positions'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        DB::transaction(function() use ($request) {
            // Create user
            $user = User::create([
                'company_id' => auth()->user()->company_id,
                'role_id' => $request->role_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'status' => 'active',
            ]);

            // Create employee profile
            EmployeeProfile::create([
                'user_id' => $user->id,
                'employee_number' => $request->employee_number,
                'department_id' => $request->department_id,
                'position_id' => $request->position_id,
                'manager_id' => $request->manager_id,
                'hire_date' => $request->hire_date,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'national_id' => $request->national_id,
                'passport_number' => $request->passport_number,
                'address' => $request->address,
                'city' => $request->city,
                'province' => $request->province,
                'postal_code' => $request->postal_code,
                'country' => $request->country ?? 'Zambia',
                'contract_end_date' => $request->contract_end_date,
                'employment_type' => $request->employment_type,
                'employment_status' => 'active',
            ]);
        });

        return redirect()->route('employees.index')->with('success', 'Employee created successfully');
    }

    public function show(string $id)
    {
        $employee = EmployeeProfile::with(['user', 'department', 'position', 'manager', 'emergencyContacts'])->findOrFail($id);
        return view('employees.show', compact('employee'));
    }

    public function edit(string $id)
    {
        $employee = EmployeeProfile::with(['user'])->findOrFail($id);
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, string $id)
    {
        $employee = EmployeeProfile::with('user')->findOrFail($id);
        
        // Get super_admin role ID to exclude it from validation
        $superAdminRole = \App\Models\Role::where('slug', 'super_admin')->first();
        
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $employee->user_id],
            'phone' => ['nullable', 'string', 'max:20'],
            'employee_number' => ['required', 'string', 'unique:employee_profiles,employee_number,' . $employee->id],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'manager_id' => ['nullable', 'exists:users,id'],
            'role_id' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($superAdminRole) {
                    if ($superAdminRole && $value == $superAdminRole->id) {
                        $fail('The selected role is not allowed.');
                    }
                },
            ],
            'hire_date' => ['required', 'date'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other,prefer_not_to_say'],
            'national_id' => ['nullable', 'string', 'max:255'],
            'passport_number' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'contract_end_date' => ['nullable', 'date', 'after:hire_date'],
            'employment_type' => ['required', 'in:permanent,contract,internship,part_time'],
            'employment_status' => ['required', 'in:active,on_leave,suspended,terminated,resigned'],
        ]);

        DB::transaction(function() use ($request, $employee) {
            // Update user
            $employee->user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role_id' => $request->role_id,
            ]);

            // Update employee profile with all fields
            $employee->update([
                'employee_number' => $request->employee_number,
                'department_id' => $request->department_id,
                'position_id' => $request->position_id,
                'manager_id' => $request->manager_id,
                'hire_date' => $request->hire_date,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'national_id' => $request->national_id,
                'passport_number' => $request->passport_number,
                'address' => $request->address,
                'city' => $request->city,
                'province' => $request->province,
                'postal_code' => $request->postal_code,
                'country' => $request->country ?? 'Zambia',
                'contract_end_date' => $request->contract_end_date,
                'employment_type' => $request->employment_type,
                'employment_status' => $request->employment_status,
            ]);
        });

        return redirect()->route('employees.show', $employee->id)->with('success', 'Employee updated successfully');
    }

    public function destroy(string $id)
    {
        $employee = EmployeeProfile::findOrFail($id);
        $employee->user->delete(); // This will cascade delete employee profile
        
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully');
    }

    public function directory()
    {
        $query = EmployeeProfile::query();
        $this->scopeEmployees($query);
        $employees = $query
            ->whereHas('user', function($q) {
                $q->where('status', 'active');
            })
            ->join('users', 'employee_profiles.user_id', '=', 'users.id')
            ->select('employee_profiles.*')
            ->with(['user.role', 'department', 'position'])
            ->orderBy('users.last_name', 'asc')
            ->orderBy('users.first_name', 'asc')
            ->get();

        return view('employees.directory', compact('employees'));
    }

    public function terminated()
    {
        $query = EmployeeProfile::query();
        $this->scopeEmployees($query);
        $employees = $query->where('employment_status', 'terminated')
          ->with(['user', 'department', 'position'])
          ->latest()
          ->paginate(15);

        return view('employees.terminated', compact('employees'));
    }
}
