<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmPerformanceKpi extends BaseTenantModel
{
    protected $table = 'hrm_performance_kpis';

    protected $fillable = [
        'clinic_id',
        'name',
        'code',
        'category',
        'frequency',
        'weight',
        'target_role',
        'target_department_id',
        'target_user_id',
        'description',
        'status',
    ];

    protected $casts = [
        'weight' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

