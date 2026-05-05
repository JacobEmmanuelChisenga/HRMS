<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_number')->unique();
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('position_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Personal Information
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable();
            $table->string('national_id')->nullable();
            $table->string('passport_number')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Zambia');
            
            // Employment Details
            $table->date('hire_date');
            $table->date('contract_end_date')->nullable();
            $table->enum('employment_type', ['permanent', 'contract', 'internship', 'part_time'])->default('permanent');
            $table->enum('employment_status', ['active', 'on_leave', 'suspended', 'terminated', 'resigned'])->default('active');
            $table->date('termination_date')->nullable();
            $table->text('termination_reason')->nullable();
            
            // Banking (for future payroll integration)
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_branch')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_profiles');
    }
};
