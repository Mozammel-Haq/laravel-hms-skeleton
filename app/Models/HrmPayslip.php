<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmPayslip extends BaseTenantModel
{
    protected $table = 'hrm_payslips';

    protected $fillable = [
        'clinic_id',
        'payroll_run_id',
        'user_id',
        'period_start',
        'period_end',
        'basic',
        'total_allowances',
        'total_deductions',
        'gross',
        'net',
        'status',
        'meta',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'basic' => 'float',
        'total_allowances' => 'float',
        'total_deductions' => 'float',
        'gross' => 'float',
        'net' => 'float',
        'meta' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function run()
    {
        return $this->belongsTo(HrmPayrollRun::class, 'payroll_run_id');
    }
}

