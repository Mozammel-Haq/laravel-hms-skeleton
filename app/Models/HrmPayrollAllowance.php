<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmPayrollAllowance extends BaseTenantModel
{
    protected $table = 'hrm_payroll_allowances';

    protected $fillable = [
        'clinic_id',
        'name',
        'code',
        'calculation_type',
        'amount',
        'status',
    ];

    protected $casts = [
        'amount' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

