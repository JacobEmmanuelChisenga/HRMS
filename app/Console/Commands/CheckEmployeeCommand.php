<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeeProfile;
use App\Models\User;

class CheckEmployeeCommand extends Command
{
    protected $signature = 'employee:check {email}';
    protected $description = 'Check employee data including manager';

    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found!");
            return 1;
        }
        
        $employee = EmployeeProfile::where('user_id', $user->id)
            ->with(['user', 'department', 'position', 'manager'])
            ->first();
        
        if (!$employee) {
            $this->error("Employee profile not found for user '{$email}'!");
            return 1;
        }
        
        $this->info("=== EMPLOYEE INFORMATION ===");
        $this->line("Name: {$employee->user->first_name} {$employee->user->last_name}");
        $this->line("Email: {$employee->user->email}");
        $this->line("Role: {$employee->user->role->name}");
        $this->line("Employee Number: {$employee->employee_number}");
        $this->newLine();
        
        $this->info("=== EMPLOYMENT INFORMATION ===");
        $this->line("Department: " . ($employee->department ? $employee->department->name : 'N/A'));
        $this->line("Position: " . ($employee->position ? $employee->position->title : 'N/A'));
        $this->line("Manager ID (in database): " . ($employee->manager_id ?? 'NULL'));
        $this->line("Manager (relationship): " . ($employee->manager ? $employee->manager->first_name . ' ' . $employee->manager->last_name . ' (' . $employee->manager->email . ')' : 'NONE'));
        $this->newLine();
        
        if ($employee->manager_id && !$employee->manager) {
            $this->warn("⚠️  WARNING: manager_id is set ({$employee->manager_id}) but manager relationship is null!");
            $this->warn("This means the user with ID {$employee->manager_id} doesn't exist or was deleted.");
        } elseif (!$employee->manager_id) {
            $this->comment("ℹ️  No manager assigned (manager_id is NULL)");
        } else {
            $this->info("✓ Manager relationship is working correctly");
        }
        
        return 0;
    }
}
