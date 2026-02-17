<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmOnboarding extends BaseTenantModel
{
    protected $table = 'hrm_onboardings';

    protected $fillable = [
        'clinic_id',
        'candidate_id',
        'user_id',
        'start_date',
        'completion_date',
        'status',
        'checklist',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'completion_date' => 'date',
        'checklist' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function candidate()
    {
        return $this->belongsTo(HrmCandidate::class, 'candidate_id');
    }
}

