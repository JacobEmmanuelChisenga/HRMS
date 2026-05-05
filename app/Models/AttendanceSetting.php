<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceSetting extends Model
{
    protected $fillable = [
        'company_id',
        'qr_rotation_minutes',
        'allow_mobile_clockin',
        'require_location',
        'office_latitude',
        'office_longitude',
        'geofence_radius_meters',
        'work_start_time',
        'work_end_time',
        'late_threshold_minutes',
        'grace_period_minutes',
        'auto_clock_out',
        'auto_clock_out_time',
    ];

    protected function casts(): array
    {
        return [
            'qr_rotation_minutes' => 'integer',
            'allow_mobile_clockin' => 'boolean',
            'require_location' => 'boolean',
            'office_latitude' => 'decimal:7',
            'office_longitude' => 'decimal:7',
            'geofence_radius_meters' => 'integer',
            'work_start_time' => 'datetime',
            'work_end_time' => 'datetime',
            'late_threshold_minutes' => 'integer',
            'grace_period_minutes' => 'integer',
            'auto_clock_out' => 'boolean',
            'auto_clock_out_time' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
