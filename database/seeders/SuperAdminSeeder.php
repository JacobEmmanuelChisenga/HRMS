<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Seed a platform-level Super Admin user.
     */
    public function run(): void
    {
        $email = config('app.super_admin_email', env('SUPER_ADMIN_EMAIL', 'superadmin@hrms.test'));
        $password = config('app.super_admin_password', env('SUPER_ADMIN_PASSWORD', 'password'));

        $role = Role::where('slug', 'super_admin')->first();
        if (!$role) {
            $this->command?->warn('Super Admin role not found; skipping Super Admin seeding.');
            return;
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make($password),
                'company_id' => null,
                'role_id' => $role->id,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Ensure role assignment in case user existed already
        if ($user->role_id !== $role->id) {
            $user->role_id = $role->id;
            $user->save();
        }

        $this->command?->info("Super Admin seeded: {$email}");
    }
}
