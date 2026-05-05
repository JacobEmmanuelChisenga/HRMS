<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('days_allowed')->default(0);
            $table->boolean('requires_approval')->default(true);
            $table->boolean('is_paid')->default(true);
            $table->boolean('carries_forward')->default(false);
            $table->integer('max_carry_forward_days')->default(0);
            $table->boolean('requires_document')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        // Seed common leave types (Note: company_id will be set dynamically in seeder)
        // This is just a template - actual seeding should happen after companies are created
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
