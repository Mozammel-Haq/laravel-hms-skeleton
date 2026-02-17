<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmPayrollDeduction extends BaseTenantModel
{
    protected $table = 'hrm_payroll_deductions';

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

