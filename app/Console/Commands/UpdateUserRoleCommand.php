<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class UpdateUserRoleCommand extends Command
{
    protected $signature = 'user:update-role {email} {role}';
    protected $description = 'Update a user\'s role';

    public function handle()
    {
        $email = $this->argument('email');
        $roleSlug = $this->argument('role');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found!");
            return 1;
        }
        
        $role = Role::where('slug', $roleSlug)->first();
        
        if (!$role) {
            $this->error("Role '{$roleSlug}' not found!");
            $this->line("Available roles:");
            Role::all(['slug', 'name'])->each(function($r) {
                $this->line("  - {$r->slug} ({$r->name})");
            });
            return 1;
        }
        
        $oldRole = $user->role->name;
        $user->role_id = $role->id;
        $user->save();
        
        $this->info("✓ Updated {$user->first_name} {$user->last_name} from '{$oldRole}' to '{$role->name}'");
        
        return 0;
    }
}
