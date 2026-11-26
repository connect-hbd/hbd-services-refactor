<?php

namespace App\Models\student;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentEnglishProficiency extends Model
{
    protected $table = 'student_english_proficiencies';

    protected $fillable = [
        'student_id',
        'status',
        'exam_name',
        'exam_date',
        'course_name',
        'mode',
        'target_score',
        'note',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'target_score' => 'decimal:2',
    ];

    // Student Relationship
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // Multiple Exam History
    public function exams(): HasMany
    {
        return $this->hasMany(
            StudentEnglishProficiencyExam::class,'english_proficiency_id'
        );
    }
}