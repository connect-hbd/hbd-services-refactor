<?php

namespace App\Models\student;

use Illuminate\Database\Eloquent\Model;

class StudyPlanChoice extends Model
{
    protected $fillable = [
        'study_plan_id',
        'sort_order',
        'program',
        'country',
        'start_date',
        'summary',
    ];

    public function studyPlan()
    {
        return $this->belongsTo(StudyPlan::class);
    }
}
