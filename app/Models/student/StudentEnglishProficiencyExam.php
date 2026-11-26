<?php

namespace App\Models\student;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentEnglishProficiencyExam extends Model
{
    protected $table = 'student_english_proficiency_exams';

    protected $fillable = [
        'english_proficiency_id',
        'exam_name',
        'year',
        'overall_score',
        'listening_score',
        'reading_score',
        'writing_score',
        'speaking_score',
    ];

    protected $casts = [
        'year' => 'integer',
        'overall_score' => 'decimal:2',
        'listening_score' => 'decimal:2',
        'reading_score' => 'decimal:2',
        'writing_score' => 'decimal:2',
        'speaking_score' => 'decimal:2',
    ];

    // Main English Proficiency Relationship
    public function englishProficiency(): BelongsTo
    {
        return $this->belongsTo(
            StudentEnglishProficiency::class,
            'english_proficiency_id'
        );
    }

}