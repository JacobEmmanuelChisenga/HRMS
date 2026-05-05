<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function foreignKeyExists($table, $keyName)
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();
        
        $result = DB::select(
            "SELECT CONSTRAINT_NAME 
             FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
             WHERE TABLE_SCHEMA = ? 
             AND TABLE_NAME = ? 
             AND CONSTRAINT_NAME = ?",
            [$database, $table, $keyName]
        );
        
        return count($result) > 0;
    }
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if 'name' column exists and drop it
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }

            // Add new columns if they don't exist
            if (!Schema::hasColumn('users', 'company_id')) {
                // First add as nullable to handle existing data
                $table->foreignId('company_id')->nullable()->after('id');
            }
            
            if (!Schema::hasColumn('users', 'role_id')) {
                // First add as nullable to handle existing data
                $table->foreignId('role_id')->nullable()->after('company_id');
            }
            
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->after('role_id');
            }
            
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->after('first_name');
            }
            
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('password');
            }
            
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('profile_photo');
            }
        });

        // Add soft deletes if not exists
        if (!Schema::hasColumn('users', 'deleted_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Update existing users to have default values if needed
        // This handles the case where there might be existing users
        // Only update if companies table exists and has data
        if (Schema::hasTable('companies')) {
            $firstCompany = DB::table('companies')->first();
            if ($firstCompany) {
                DB::statement("UPDATE users SET company_id = {$firstCompany->id} WHERE company_id IS NULL");
            }
        }
        
        if (Schema::hasTable('roles')) {
            $employeeRole = DB::table('roles')->where('slug', 'employee')->first();
            if ($employeeRole) {
                DB::statement("UPDATE users SET role_id = {$employeeRole->id} WHERE role_id IS NULL");
            }
        }
        
        DB::statement("UPDATE users SET first_name = COALESCE(first_name, 'User') WHERE first_name IS NULL OR first_name = ''");
        DB::statement("UPDATE users SET last_name = COALESCE(last_name, 'Name') WHERE last_name IS NULL OR last_name = ''");
        
        // Foreign keys will be added in a separate migration after companies and roles tables exist
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove foreign keys first
            if (Schema::hasColumn('users', 'company_id')) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            }
            
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            }
            
            // Remove other columns
            $columns = ['first_name', 'last_name', 'phone', 'profile_photo', 'status'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Remove soft deletes
            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            
            // Add back the name column
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->after('id');
            }
        });
    }
};
