<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if a foreign key constraint exists by name
     */
    private function constraintExists($constraintName)
    {
        $result = DB::select(
            "SELECT constraint_name 
             FROM information_schema.table_constraints 
             WHERE table_schema = 'public' 
             AND constraint_name = ?",
            [$constraintName]
        );
        
        return count($result) > 0;
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add foreign key constraints after companies and roles tables exist
        if (Schema::hasColumn('users', 'company_id') && Schema::hasTable('companies')) {
            // Drop existing foreign key if it exists
            if ($this->constraintExists('users_company_id_foreign')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropForeign(['company_id']);
                });
            }
            
            // Add the foreign key
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            });
        }
        
        if (Schema::hasColumn('users', 'role_id') && Schema::hasTable('roles')) {
            // Drop existing foreign key if it exists
            if ($this->constraintExists('users_role_id_foreign')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropForeign(['role_id']);
                });
            }
            
            // Add the foreign key
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('role_id')->references('id')->on('roles');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->constraintExists('users_company_id_foreign')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['company_id']);
            });
        }
        
        if ($this->constraintExists('users_role_id_foreign')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['role_id']);
            });
        }
    }
};
