<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmTrainingSession extends BaseTenantModel
{
    protected $table = 'hrm_training_sessions';

    protected $fillable = [
        'clinic_id',
        'course_id',
        'facilitator_user_id',
        'start_date',
        'end_date',
        'location',
        'capacity',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'capacity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(HrmTrainingCourse::class, 'course_id');
    }
}

