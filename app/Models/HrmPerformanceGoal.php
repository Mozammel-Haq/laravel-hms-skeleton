<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmPerformanceGoal extends BaseTenantModel
{
    protected $table = 'hrm_performance_goals';

    protected $fillable = [
        'clinic_id',
        'user_id',
        'kpi_id',
        'period_start',
        'period_end',
        'title',
        'unit',
        'target_value',
        'current_value',
        'status',
        'owner_user_id',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'target_value' => 'float',
        'current_value' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function kpi()
    {
        return $this->belongsTo(HrmPerformanceKpi::class, 'kpi_id');
    }
}

