<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_record_id')->constrained()->onDelete('cascade');
            $table->enum('action', ['clock_in', 'clock_out', 'edit', 'delete']);
            $table->foreignId('performed_by')->constrained('users')->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
