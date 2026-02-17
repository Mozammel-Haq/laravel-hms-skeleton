<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmPayrollTax extends BaseTenantModel
{
    protected $table = 'hrm_payroll_taxes';

    protected $fillable = [
        'clinic_id',
        'name',
        'code',
        'calculation_type',
        'rate',
        'threshold',
        'status',
    ];

    protected $casts = [
        'rate' => 'float',
        'threshold' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

