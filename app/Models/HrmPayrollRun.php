<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmPayrollRun extends BaseTenantModel
{
    protected $table = 'hrm_payroll_runs';

    protected $fillable = [
        'clinic_id',
        'period_start',
        'period_end',
        'status',
        'total_gross',
        'total_net',
        'processed_by',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_gross' => 'float',
        'total_net' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}

