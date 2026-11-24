<?php

namespace App\Models\student;

use App\Models\Country;
use App\Models\Student;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentDestination extends Pivot
{
    protected $table = 'student_destinations';

    public $incrementing = true;

    protected $fillable = [
        'student_id',
        'country_id',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}