<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leave_types', function (Blueprint $table) {
            $table->decimal('accrual_rate', 5, 2)->nullable()->after('days_allowed')->comment('Days accrued per month (e.g., 2.0 for Annual Leave)');
            $table->enum('gender_restriction', ['none', 'male', 'female'])->default('none')->after('requires_document')->comment('Gender restriction for leave eligibility');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_types', function (Blueprint $table) {
            $table->dropColumn(['accrual_rate', 'gender_restriction']);
        });
    }
};
