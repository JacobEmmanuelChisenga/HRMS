<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'title',
        'content',
        'posted_by',
        'is_active',
        'publish_date',
        'expiry_date',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'publish_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
