<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmTrainingEvaluation extends BaseTenantModel
{
    protected $table = 'hrm_training_evaluations';

    protected $fillable = [
        'clinic_id',
        'session_id',
        'user_id',
        'rating',
        'feedback',
        'completed_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(HrmTrainingSession::class, 'session_id');
    }
}

