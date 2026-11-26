<?php

namespace App\Models\student;

use App\Models\City;
use App\Models\Country;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referrals extends Model
{
    protected $table = 'student_referrals';

    protected $fillable = [
        'student_id',
        'known_by',
        'known_by_details',
        'employee',
        'expo',
        'permission',
    ];

    protected $casts = [
        'permission' => 'boolean',
    ];

    /**
     * Student referral belongs to a student.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}