<?php

namespace App\Models;

use App\Models\Profile\Address;
use App\Models\Profile\AcademicInformation;
use App\Models\Profile\ImmigrationRefusal;
use App\Models\Profile\StudentDocument;
use App\Models\Profile\StudyPlan;
use App\Models\Profile\WorkExperience;
use App\Models\student\StudentEmail;
use App\Models\student\StudentPhone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

   protected $fillable = [
        'profile_step',
        'user_id',
        'student_number',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'marital_status',
        'passport_number',
        'passport_issue_date',
        'passport_expiry_date',
        'service_type',
        'created_by',
        'owner_id',
        'owner_team_id',
    ];

    protected function casts(): array
    {
         return [
            'date_of_birth' => 'date',
            'passport_issue_date' => 'date',
            'passport_expiry_date' => 'date',
            'profile_step' => 'integer',
        ];
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function phones()
    {
        return $this->hasMany(StudentPhone::class);
    }

    public function emails()
    {
        return $this->hasMany(StudentEmail::class);
    }

    public function destinations()
    {
        return $this->belongsToMany(
            Country::class,
            'student_destinations'
        )->withTimestamps();
    }

    public function academicInformation()
    {
        return $this->hasOne(AcademicInformation::class);
    }

    public function studyPlan()
    {
        return $this->hasOne(StudyPlan::class);
    }

    public function workExperiences()
    {
        return $this->hasMany(WorkExperience::class)->orderBy('sort_order');
    }

    public function immigrationRefusals()
    {
        return $this->hasMany(ImmigrationRefusal::class)->orderBy('sort_order');
    }

    public function documents()
    {
        return $this->hasMany(StudentDocument::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Advance profile_step if the completed step is the current highest.
     */
    public function advanceStepTo(int $completedStep): void
    {
        if ($completedStep >= $this->profile_step) {
            $next = min($completedStep + 1, 6);
            $this->update(['profile_step' => $next]);
        }
    }
     public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('student_number', 'like', "%{$search}%")
              ->orWhere('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%");
        });
    }
}
