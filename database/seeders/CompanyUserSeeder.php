<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\Position;
use App\Models\EmployeeProfile;
use App\Models\LeaveType;
use App\Models\DocumentCategory;
use App\Models\AttendanceSetting;
use Illuminate\Support\Facades\Hash;

class CompanyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a demo company
        $company = Company::create([
            'name' => 'Demo Company Ltd',
            'subdomain' => 'demo',
            'email' => 'admin@democompany.com',
            'phone' => '+260977123456',
            'address' => '123 Business Street',
            'city' => 'Lusaka',
            'country' => 'Zambia',
            'status' => 'active',
            'subscription_plan' => 'premium',
        ]);

        // Create departments
        $hrDept = Department::create([
            'company_id' => $company->id,
            'name' => 'Human Resources',
            'description' => 'HR Department',
            'status' => 'active',
        ]);

        $itDept = Department::create([
            'company_id' => $company->id,
            'name' => 'Information Technology',
            'description' => 'IT Department',
            'status' => 'active',
        ]);

        $salesDept = Department::create([
            'company_id' => $company->id,
            'name' => 'Sales',
            'description' => 'Sales Department',
            'status' => 'active',
        ]);

        // Create positions
        $positions = [
            ['company_id' => $company->id, 'department_id' => $hrDept->id, 'title' => 'HR Manager', 'level' => 'senior', 'status' => 'active'],
            ['company_id' => $company->id, 'department_id' => $itDept->id, 'title' => 'IT Manager', 'level' => 'senior', 'status' => 'active'],
            ['company_id' => $company->id, 'department_id' => $itDept->id, 'title' => 'Software Developer', 'level' => 'mid', 'status' => 'active'],
            ['company_id' => $company->id, 'department_id' => $salesDept->id, 'title' => 'Sales Manager', 'level' => 'senior', 'status' => 'active'],
            ['company_id' => $company->id, 'department_id' => $salesDept->id, 'title' => 'Sales Representative', 'level' => 'junior', 'status' => 'active'],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }

        // Get roles
        $superAdminRole = Role::where('slug', 'super_admin')->first();
        $companyAdminRole = Role::where('slug', 'company_admin')->first();
        $hrManagerRole = Role::where('slug', 'hr_manager')->first();
        $departmentHeadRole = Role::where('slug', 'department_head')->first();
        $teamLeadRole = Role::where('slug', 'team_lead')->first();
        $employeeRole = Role::where('slug', 'employee')->first();

        // Create Company Admin
        $companyAdmin = User::create([
            'company_id' => $company->id,
            'role_id' => $companyAdminRole->id,
            'first_name' => 'Company',
            'last_name' => 'Admin',
            'email' => 'admin@democompany.com',
            'phone' => '+260977123456',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $hrManager = User::create([
            'company_id' => $company->id,
            'role_id' => $hrManagerRole->id,
            'first_name' => 'HR',
            'last_name' => 'Manager',
            'email' => 'hr@democompany.com',
            'phone' => '+260977123457',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // IT Manager as Department Head (manages IT department)
        $itManager = User::create([
            'company_id' => $company->id,
            'role_id' => $departmentHeadRole->id,
            'first_name' => 'IT',
            'last_name' => 'Manager',
            'email' => 'itmanager@democompany.com',
            'phone' => '+260977123458',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $employee1 = User::create([
            'company_id' => $company->id,
            'role_id' => $employeeRole->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@democompany.com',
            'phone' => '+260977123459',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $employee2 = User::create([
            'company_id' => $company->id,
            'role_id' => $employeeRole->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@democompany.com',
            'phone' => '+260977123460',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Update department managers
        $hrDept->update(['manager_id' => $hrManager->id]);
        $itDept->update(['manager_id' => $itManager->id]);

        // Create employee profiles
        EmployeeProfile::create([
            'user_id' => $hrManager->id,
            'employee_number' => 'EMP001',
            'department_id' => $hrDept->id,
            'position_id' => Position::where('title', 'HR Manager')->first()->id,
            'hire_date' => now()->subYear(),
            'employment_type' => 'permanent',
            'employment_status' => 'active',
        ]);

        EmployeeProfile::create([
            'user_id' => $itManager->id,
            'employee_number' => 'EMP002',
            'department_id' => $itDept->id,
            'position_id' => Position::where('title', 'IT Manager')->first()->id,
            'manager_id' => $hrManager->id,
            'hire_date' => now()->subMonths(6),
            'employment_type' => 'permanent',
            'employment_status' => 'active',
        ]);

        EmployeeProfile::create([
            'user_id' => $employee1->id,
            'employee_number' => 'EMP003',
            'department_id' => $itDept->id,
            'position_id' => Position::where('title', 'Software Developer')->first()->id,
            'manager_id' => $itManager->id,
            'hire_date' => now()->subMonths(3),
            'employment_type' => 'permanent',
            'employment_status' => 'active',
        ]);

        EmployeeProfile::create([
            'user_id' => $employee2->id,
            'employee_number' => 'EMP004',
            'department_id' => $salesDept->id,
            'position_id' => Position::where('title', 'Sales Representative')->first()->id,
            'hire_date' => now()->subMonths(2),
            'employment_type' => 'permanent',
            'employment_status' => 'active',
        ]);

        // Create leave types for the company (Zambian Labor Law)
        $leaveTypes = [
            [
                'name' => 'Annual Leave',
                'description' => 'Paid vacation leave. Employees with 12 consecutive months of service are entitled to at least 2 days per month (24 days per year). This is in addition to public holidays and weekly rest days.',
                'days_allowed' => 24,
                'accrual_rate' => 2.0, // 2 days per month
                'is_paid' => true,
                'carries_forward' => true,
                'max_carry_forward_days' => 5,
                'requires_approval' => true,
                'requires_document' => false,
                'gender_restriction' => 'none',
            ],
            [
                'name' => 'Sick Leave',
                'description' => 'Paid sick leave on production of medical certificate. Short-term contracts (≤12 months): up to 52 working days (first 26 days full pay, next 26 days half pay). Long-term/permanent staff: up to 6 months (first 3 months fully paid, next 3 months half pay).',
                'days_allowed' => 52, // Maximum for short-term, can be adjusted per employee
                'accrual_rate' => null, // Not accrued monthly, entitlement based on contract type
                'is_paid' => true,
                'carries_forward' => false,
                'max_carry_forward_days' => 0,
                'requires_approval' => true,
                'requires_document' => true,
                'gender_restriction' => 'none',
            ],
            [
                'name' => 'Maternity Leave',
                'description' => 'Female employees entitled to at least 14 weeks (98 days) of maternity leave. At least 6 weeks must be taken after childbirth. Extended by 4 weeks for multiple births. Medical certificate and written notice required.',
                'days_allowed' => 98, // 14 weeks
                'accrual_rate' => null,
                'is_paid' => true,
                'carries_forward' => false,
                'max_carry_forward_days' => 0,
                'requires_approval' => true,
                'requires_document' => true,
                'gender_restriction' => 'female',
            ],
            [
                'name' => 'Paternity Leave',
                'description' => 'Male employees with at least 12 months continuous service are entitled to at least 5 working days paid paternity leave, usually to be taken within 7 days of the child\'s birth.',
                'days_allowed' => 5,
                'accrual_rate' => null,
                'is_paid' => true,
                'carries_forward' => false,
                'max_carry_forward_days' => 0,
                'requires_approval' => true,
                'requires_document' => false,
                'gender_restriction' => 'male',
            ],
            [
                'name' => 'Compassionate Leave',
                'description' => 'Bereavement leave with full pay for situations like the death of a spouse, parent, child or dependent. Minimum statutory duration is 12 days per calendar year.',
                'days_allowed' => 12,
                'accrual_rate' => null,
                'is_paid' => true,
                'carries_forward' => false,
                'max_carry_forward_days' => 0,
                'requires_approval' => true,
                'requires_document' => false,
                'gender_restriction' => 'none',
            ],
            [
                'name' => 'Family Responsibility Leave',
                'description' => 'Employees who\'ve worked 6 months or more can take up to 7 days paid leave per year to care for a sick spouse, child, or dependent upon production of a medical certificate.',
                'days_allowed' => 7,
                'accrual_rate' => null,
                'is_paid' => true,
                'carries_forward' => false,
                'max_carry_forward_days' => 0,
                'requires_approval' => true,
                'requires_document' => true,
                'gender_restriction' => 'none',
            ],
            [
                'name' => 'Mother\'s Day Leave',
                'description' => 'Female employees are entitled to 1 day off per month (often referred to as "Mother\'s Day" provision) without a medical certificate or reason.',
                'days_allowed' => 12, // 1 day per month = 12 days per year
                'accrual_rate' => 1.0, // 1 day per month
                'is_paid' => true,
                'carries_forward' => false,
                'max_carry_forward_days' => 0,
                'requires_approval' => false, // No approval needed
                'requires_document' => false,
                'gender_restriction' => 'female',
            ],
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::create(array_merge($leaveType, [
                'company_id' => $company->id,
                'status' => 'active',
            ]));
        }

        // Create document categories
        $categories = [
            'Employment Contract',
            'ID/Passport',
            'Certificates',
            'Medical Certificate',
            'NDA',
            'Performance Review',
            'Warning Letter',
            'Other',
        ];

        foreach ($categories as $category) {
            DocumentCategory::create([
                'company_id' => $company->id,
                'name' => $category,
                'requires_expiry' => in_array($category, ['Employment Contract', 'ID/Passport', 'Medical Certificate']),
                'status' => 'active',
            ]);
        }

        // Create attendance settings
        AttendanceSetting::create([
            'company_id' => $company->id,
            'qr_rotation_minutes' => 5,
            'allow_mobile_clockin' => true,
            'require_location' => false,
            'work_start_time' => '08:00:00',
            'work_end_time' => '17:00:00',
            'late_threshold_minutes' => 15,
            'grace_period_minutes' => 5,
        ]);

        $this->command->info('Company and users created successfully!');
        $this->command->info('Company Admin: admin@democompany.com / password');
        $this->command->info('HR Manager: hr@democompany.com / password');
        $this->command->info('IT Manager: itmanager@democompany.com / password');
        $this->command->info('Employee 1: john.doe@democompany.com / password');
        $this->command->info('Employee 2: jane.smith@democompany.com / password');
    }
}
