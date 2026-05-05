<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Role;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        // Get super_admin role ID to exclude it from validation
        $superAdminRole = Role::where('slug', 'super_admin')->first();
        
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'employee_number' => ['required', 'string', 'unique:employee_profiles,employee_number'],
            'department_id' => ['required', 'exists:departments,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'manager_id' => ['nullable', 'exists:users,id'],
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
            'role_id' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($superAdminRole) {
                    if ($superAdminRole && $value == $superAdminRole->id) {
                        $fail('The selected role is not allowed.');
                    }
                },
            ],
        ];
    }
}
