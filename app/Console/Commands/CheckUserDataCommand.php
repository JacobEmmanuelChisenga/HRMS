<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Company;
use App\Models\Department;
use App\Models\Position;
use App\Models\EmployeeProfile;

class CheckUserDataCommand extends Command
{
    protected $signature = 'user:check {name?}';
    protected $description = 'Check what a user (Company Admin) has created';

    public function handle()
    {
        $name = $this->argument('name') ?? 'Jacob Chisenga';
        
        $this->info("Checking data for: {$name}");
        $this->newLine();

        // First, check for CoreAxis LTD company
        $company = Company::where('name', 'like', '%CoreAxis%')
            ->orWhere('name', 'like', '%Core Axis%')
            ->first();
        
        if ($company) {
            $this->info("Found company: {$company->name} (ID: {$company->id})");
            $this->newLine();
            
            // Find Company Admin for this company
            $user = User::where('company_id', $company->id)
                ->whereHas('role', function($q) {
                    $q->where('slug', 'company_admin');
                })
                ->where(function($q) {
                    $q->where('first_name', 'like', '%Jacob%')
                      ->where('last_name', 'like', '%Chisenga%');
                })
                ->first();
            
            if (!$user) {
                // Try to find any company admin for CoreAxis
                $user = User::where('company_id', $company->id)
                    ->whereHas('role', function($q) {
                        $q->where('slug', 'company_admin');
                    })
                    ->first();
            }
        } else {
            // Find user by name
            $user = User::where('first_name', 'like', '%Jacob%')
                ->where('last_name', 'like', '%Chisenga%')
                ->orWhere('email', 'like', '%jacob%')
                ->orWhere('email', 'like', '%chisenga%')
                ->first();
        }

        if (!$user) {
            $this->error("User '{$name}' not found!");
            if ($company) {
                $this->info("But found company: {$company->name}");
                $this->info("Company ID: {$company->id}");
                $this->info("Departments: " . $company->departments()->count());
                $this->info("Positions: " . $company->positions()->count());
            }
            return 1;
        }

        $this->info("=== USER INFORMATION ===");
        $this->line("Name: {$user->first_name} {$user->last_name}");
        $this->line("Email: {$user->email}");
        $this->line("Role: {$user->role->name}");
        $this->line("Company ID: {$user->company_id}");
        
        $company = $user->company;
        if ($company) {
            $this->line("Company: {$company->name}");
        }
        $this->newLine();

        // Check departments
        $departments = Department::where('company_id', $user->company_id)->get();
        $this->info("=== DEPARTMENTS ({$departments->count()}) ===");
        if ($departments->isEmpty()) {
            $this->warn("No departments found!");
        } else {
            $this->table(
                ['ID', 'Name', 'Manager', 'Status', 'Positions', 'Employees'],
                $departments->map(function ($dept) {
                    return [
                        $dept->id,
                        $dept->name,
                        $dept->manager ? $dept->manager->first_name . ' ' . $dept->manager->last_name : 'N/A',
                        $dept->status,
                        $dept->positions()->count(),
                        $dept->employees()->count(),
                    ];
                })->toArray()
            );
        }
        $this->newLine();

        // Check positions
        $positions = Position::where('company_id', $user->company_id)->get();
        $this->info("=== POSITIONS ({$positions->count()}) ===");
        if ($positions->isEmpty()) {
            $this->warn("No positions found!");
        } else {
            $this->table(
                ['ID', 'Title', 'Department', 'Level', 'Status', 'Employees'],
                $positions->map(function ($pos) {
                    return [
                        $pos->id,
                        $pos->title,
                        $pos->department ? $pos->department->name : 'NO DEPARTMENT',
                        $pos->level,
                        $pos->status,
                        $pos->employees()->count(),
                    ];
                })->toArray()
            );
        }
        $this->newLine();

        // Check employees
        $employees = EmployeeProfile::whereHas('user', function($q) use ($user) {
            $q->where('company_id', $user->company_id);
        })->get();
        $this->info("=== EMPLOYEES ({$employees->count()}) ===");
        if ($employees->isEmpty()) {
            $this->warn("No employees found!");
        } else {
            $this->table(
                ['ID', 'Name', 'Email', 'Department', 'Position', 'Status'],
                $employees->take(10)->map(function ($emp) {
                    return [
                        $emp->id,
                        $emp->user->first_name . ' ' . $emp->user->last_name,
                        $emp->user->email,
                        $emp->department ? $emp->department->name : 'N/A',
                        $emp->position ? $emp->position->title : 'N/A',
                        $emp->employment_status,
                    ];
                })->toArray()
            );
            if ($employees->count() > 10) {
                $this->comment("... and " . ($employees->count() - 10) . " more employees");
            }
        }
        $this->newLine();

        // Summary
        $this->info("=== SUMMARY ===");
        $this->line("Departments: {$departments->count()}");
        $this->line("Positions: {$positions->count()}");
        $this->line("Employees: {$employees->count()}");
        
        // Check positions without departments
        $positionsWithoutDept = $positions->filter(fn($p) => $p->department_id === null);
        if ($positionsWithoutDept->isNotEmpty()) {
            $this->warn("⚠️  {$positionsWithoutDept->count()} positions without department_id!");
        }

        return 0;
    }
}
