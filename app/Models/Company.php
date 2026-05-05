<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'subdomain',
        'logo',
        'primary_color',
        'secondary_color',
        'accent_color',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'status',
        'subscription_plan',
        'subscription_expires_at',
        'landing_page_enabled',
        'landing_page_title',
        'landing_page_content',
        'landing_page_image',
        'landing_page_primary_cta_text',
        'landing_page_primary_cta_link',
        'landing_page_secondary_cta_text',
        'landing_page_secondary_cta_link',
        'login_page_title',
        'login_page_subtitle',
        'login_page_image',
        'registration_page_title',
        'registration_page_subtitle',
        'registration_page_image',
    ];

    protected function casts(): array
    {
        return [
            'subscription_expires_at' => 'datetime',
        ];
    }

    /**
     * Boot the model.
     * Handle cascading deletes when company is deleted.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($company) {
            // Delete all related files before deleting the company
            // Database foreign keys will handle the data deletion, but we need to clean up files
            
            // Eager load relationships to avoid N+1 queries
            $company->load([
                'documents.versions',
                'users.employeeProfile.leaveRequests'
            ]);
            
            // Delete company logo
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            
            // Delete landing page image
            if ($company->landing_page_image) {
                Storage::disk('public')->delete($company->landing_page_image);
            }
            
            // Delete login page image
            if ($company->login_page_image) {
                Storage::disk('public')->delete($company->login_page_image);
            }
            
            // Delete registration page image
            if ($company->registration_page_image) {
                Storage::disk('public')->delete($company->registration_page_image);
            }

            // Delete all documents and their files
            foreach ($company->documents as $document) {
                if ($document->file_path) {
                    Storage::disk('public')->delete($document->file_path);
                }
                // Delete document versions
                foreach ($document->versions as $version) {
                    if ($version->file_path) {
                        Storage::disk('public')->delete($version->file_path);
                    }
                }
            }

            // Delete all user profile photos and leave request documents
            foreach ($company->users as $user) {
                // Delete profile photo
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                
                // Delete leave request supporting documents
                if ($user->employeeProfile) {
                    foreach ($user->employeeProfile->leaveRequests as $leaveRequest) {
                        if ($leaveRequest->supporting_document) {
                            Storage::disk('public')->delete($leaveRequest->supporting_document);
                        }
                    }
                }
            }
        });
    }

    // Relationships
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function leaveTypes(): HasMany
    {
        return $this->hasMany(LeaveType::class);
    }

    public function attendanceSettings(): HasOne
    {
        return $this->hasOne(AttendanceSetting::class);
    }

    public function qrCodes(): HasMany
    {
        return $this->hasMany(QrCode::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function holidays(): HasMany
    {
        return $this->hasMany(Holiday::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(CompanySetting::class);
    }
}
