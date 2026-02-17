<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmTrainingCourse extends BaseTenantModel
{
    protected $table = 'hrm_training_courses';

    protected $fillable = [
        'clinic_id',
        'title',
        'code',
        'category',
        'target_role',
        'mode',
        'duration_hours',
        'description',
        'status',
    ];

    protected $casts = [
        'duration_hours' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

