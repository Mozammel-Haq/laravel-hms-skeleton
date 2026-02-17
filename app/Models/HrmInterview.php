<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmInterview extends BaseTenantModel
{
    protected $table = 'hrm_interviews';

    protected $fillable = [
        'clinic_id',
        'candidate_id',
        'job_post_id',
        'scheduled_at',
        'mode',
        'location',
        'interviewer_name',
        'interviewer_user_id',
        'result',
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function candidate()
    {
        return $this->belongsTo(HrmCandidate::class, 'candidate_id');
    }

    public function jobPost()
    {
        return $this->belongsTo(HrmJobPost::class, 'job_post_id');
    }
}

