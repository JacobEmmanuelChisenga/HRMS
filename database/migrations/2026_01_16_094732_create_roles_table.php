<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed default roles - 8 System Roles
        DB::table('roles')->insert([
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'description' => 'Platform administrator with full system access'],
            ['name' => 'Company Admin', 'slug' => 'company_admin', 'description' => 'Company owner/admin with full company control'],
            ['name' => 'HR Manager', 'slug' => 'hr_manager', 'description' => 'HR department head - manages all employees company-wide'],
            ['name' => 'Department Head', 'slug' => 'department_head', 'description' => 'Department directors/heads - manages their department only'],
            ['name' => 'Team Lead', 'slug' => 'team_lead', 'description' => 'Team manager/supervisor - manages their direct reports only'],
            ['name' => 'Employee', 'slug' => 'employee', 'description' => 'Regular employee with self-service access'],
            ['name' => 'Auditor', 'slug' => 'auditor', 'description' => 'Compliance officer/internal auditor - read-only access for auditing and compliance'],
            ['name' => 'IT Admin', 'slug' => 'it_admin', 'description' => 'IT department staff - technical control and system maintenance'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
