<?php

namespace App\Models\student;

use App\Models\City;
use App\Models\Country;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicInformation extends Model
{
     protected $table = 'academic_information';

    protected $fillable = [
        'student_id',
        'highest_degree',
        'highest_institution',
        'highest_grad_date',
        'highest_gpa',
        'highest_max_gpa',
        'education_history',
    ];

    protected $casts = [
        'education_history' => 'array',
    ];

    /**
     * Academic information belongs to a student.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}