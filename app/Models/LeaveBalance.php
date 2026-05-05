<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveBalance extends Model
{
    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'year',
        'total_days',
        'used_days',
        'pending_days',
        'remaining_days',
        'carried_forward',
    ];

    protected function casts(): array
    {
        return [
            'total_days' => 'decimal:1',
            'used_days' => 'decimal:1',
            'pending_days' => 'decimal:1',
            'remaining_days' => 'decimal:1',
            'carried_forward' => 'decimal:1',
        ];
    }

    // Relationships
    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeeProfile::class, 'employee_id');
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }
}
