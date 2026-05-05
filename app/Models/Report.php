<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'company_id',
        'created_by',
        'name',
        'type',
        'parameters',
        'filters',
        'is_scheduled',
        'schedule_frequency',
        'last_generated_at',
    ];

    protected function casts(): array
    {
        return [
            'parameters' => 'array',
            'filters' => 'array',
            'is_scheduled' => 'boolean',
            'last_generated_at' => 'datetime',
        ];
    }

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
