<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->integer('qr_rotation_minutes')->default(5);
            $table->boolean('allow_mobile_clockin')->default(true);
            $table->boolean('require_location')->default(false);
            $table->decimal('office_latitude', 10, 7)->nullable();
            $table->decimal('office_longitude', 10, 7)->nullable();
            $table->integer('geofence_radius_meters')->default(100);
            $table->time('work_start_time')->default('08:00:00');
            $table->time('work_end_time')->default('17:00:00');
            $table->integer('late_threshold_minutes')->default(15);
            $table->integer('grace_period_minutes')->default(5);
            $table->boolean('auto_clock_out')->default(false);
            $table->time('auto_clock_out_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_settings');
    }
};
