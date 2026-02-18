<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmPerformanceReview extends BaseTenantModel
{
    protected $table = 'hrm_performance_reviews';

    protected $fillable = [
        'clinic_id',
        'user_id',
        'reviewer_user_id',
        'period_start',
        'period_end',
        'overall_rating',
        'summary',
        'status',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'overall_rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_user_id');
    }
}

