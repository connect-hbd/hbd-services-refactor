<?php

namespace App\Models\student;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPhone extends Model
{
    protected $fillable = [
        'student_id',
        'phone',
        'is_whatsapp',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_whatsapp' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}