<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'days_allowed',
        'accrual_rate',
        'requires_approval',
        'is_paid',
        'carries_forward',
        'max_carry_forward_days',
        'requires_document',
        'gender_restriction',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'requires_approval' => 'boolean',
            'is_paid' => 'boolean',
            'carries_forward' => 'boolean',
            'requires_document' => 'boolean',
            'accrual_rate' => 'decimal:2',
        ];
    }

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }
}
