<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'hours_worked',
        'location_latitude',
        'location_longitude',
        'ip_address',
        'status',
        'qr_code_id',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'clock_in' => 'datetime',
            'clock_out' => 'datetime',
            'hours_worked' => 'decimal:2',
            'location_latitude' => 'decimal:7',
            'location_longitude' => 'decimal:7',
        ];
    }

    // Relationships
    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeeProfile::class, 'employee_id');
    }

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }
}
