<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmCandidate extends BaseTenantModel
{
    protected $table = 'hrm_candidates';

    protected $fillable = [
        'clinic_id',
        'job_post_id',
        'name',
        'email',
        'phone',
        'source',
        'resume_url',
        'notes',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function jobPost()
    {
        return $this->belongsTo(HrmJobPost::class, 'job_post_id');
    }
}

