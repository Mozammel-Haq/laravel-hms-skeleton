<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmPerformanceAppraisal extends BaseTenantModel
{
    protected $table = 'hrm_performance_appraisals';

    protected $fillable = [
        'clinic_id',
        'user_id',
        'review_id',
        'effective_date',
        'current_salary',
        'new_salary',
        'salary_change_amount',
        'salary_change_percent',
        'promotion_to_designation_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'current_salary' => 'float',
        'new_salary' => 'float',
        'salary_change_amount' => 'float',
        'salary_change_percent' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'promotion_designation',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
        return $this->belongsTo(HrmPerformanceReview::class, 'review_id');
    }

    public function promotionToDesignation()
    {
        return $this->belongsTo(Designation::class, 'promotion_to_designation_id');
    }

    public function getPromotionDesignationAttribute()
    {
        return $this->promotionToDesignation;
    }
}
