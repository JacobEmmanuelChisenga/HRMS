<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            // Make slug unique per company (or globally if company_id is null)
            $table->dropUnique(['slug']);
        });
        
        // Add unique constraint for slug per company
        Schema::table('roles', function (Blueprint $table) {
            $table->unique(['company_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'slug']);
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            $table->unique('slug');
        });
    }
};
