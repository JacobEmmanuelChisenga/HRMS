<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_number',
        'department_id',
        'position_id',
        'manager_id',
        'date_of_birth',
        'gender',
        'national_id',
        'passport_number',
        'address',
        'city',
        'province',
        'postal_code',
        'country',
        'hire_date',
        'contract_end_date',
        'employment_type',
        'employment_status',
        'termination_date',
        'termination_reason',
        'bank_name',
        'bank_account_number',
        'bank_branch',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'hire_date' => 'date',
            'contract_end_date' => 'date',
            'termination_date' => 'date',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(EmergencyContact::class, 'employee_id');
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class, 'employee_id');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'employee_id');
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'employee_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'employee_id');
    }
}
