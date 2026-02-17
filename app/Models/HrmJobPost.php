<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmJobPost extends BaseTenantModel
{
    protected $table = 'hrm_job_posts';

    protected $fillable = [
        'clinic_id',
        'title',
        'department_id',
        'employment_type',
        'location',
        'description',
        'requirements',
        'openings',
        'status',
        'posted_at',
        'closes_at',
    ];

    protected $casts = [
        'openings' => 'integer',
        'posted_at' => 'datetime',
        'closes_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

