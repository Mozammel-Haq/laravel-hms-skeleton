<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmSalaryStructure extends BaseTenantModel
{
    protected $table = 'hrm_salary_structures';

    protected $fillable = [
        'clinic_id',
        'name',
        'code',
        'basic_amount',
        'is_default',
        'status',
    ];

    protected $casts = [
        'basic_amount' => 'float',
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

