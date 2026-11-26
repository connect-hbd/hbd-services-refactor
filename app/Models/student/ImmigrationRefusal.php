<?php

namespace App\Models\student;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;

class ImmigrationRefusal extends Model
{
    protected $fillable = [
        'student_id',
        'country',
        'status',
        'refusal_date',
        'grounds',
        'document_path',
        'document_name',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'refusal_date' => 'date',
            'sort_order'   => 'integer',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
