<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmJobOffer extends BaseTenantModel
{
    protected $table = 'hrm_job_offers';

    protected $fillable = [
        'clinic_id',
        'candidate_id',
        'job_post_id',
        'offered_role',
        'salary_offered',
        'joining_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'salary_offered' => 'float',
        'joining_date' => 'date',
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

