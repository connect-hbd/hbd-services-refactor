<?php

namespace App\Models\student;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;

class ImmigrationRefusal extends Model
{
    protected $fillable = [
        'student_id',
        'country',
        'date',
        'grounds',
        'document_path',
        'document_name',
        'sort_order',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
