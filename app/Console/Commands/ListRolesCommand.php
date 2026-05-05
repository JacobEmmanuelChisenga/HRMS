<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\Permission;

class ListRolesCommand extends Command
{
    protected $signature = 'roles:list';
    protected $description = 'List all roles and their permissions';

    public function handle()
    {
        $this->info('=== CURRENT ROLES IN THE SYSTEM ===');
        $this->newLine();
        
        $roles = Role::orderByRaw('company_id IS NULL DESC')
            ->orderBy('name')
            ->with('permissions')
            ->get();
        
        foreach ($roles as $role) {
            $type = $role->company_id ? 'Custom (Company-specific)' : 'System (Built-in)';
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->info("Role: {$role->name} ({$role->slug})");
            $this->line("Type: {$type}");
            $this->line("Description: " . ($role->description ?? 'N/A'));
            $this->line("Users with this role: " . $role->users()->count());
            
            $permissions = $role->permissions;
            if ($permissions->count() > 0) {
                $this->line("Permissions ({$permissions->count()}):");
                $grouped = $permissions->groupBy('category');
                foreach ($grouped as $category => $perms) {
                    $this->line("  • {$category}:");
                    foreach ($perms as $perm) {
                        $this->line("    - {$perm->name}");
                    }
                }
            } else {
                $this->warn("  No permissions assigned");
            }
            $this->newLine();
        }
        
        $this->info('=== ROLE CREATION ===');
        $this->line('• System Roles: Created automatically via database migrations');
        $this->line('• Custom Roles: Created by Company Admins via Settings → Roles');
        $this->line('  - Company Admins can create, edit, and delete custom roles');
        $this->line('  - Custom roles are scoped to the company (company_id is set)');
        $this->line('  - System roles cannot be edited or deleted');
        
        return 0;
    }
}
