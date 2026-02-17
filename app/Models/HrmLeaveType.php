<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmLeaveType extends BaseTenantModel
{
    protected $table = 'hrm_leave_types';

    protected $fillable = [
        'clinic_id',
        'name',
        'code',
        'default_days',
        'carry_forward',
        'status',
    ];

    protected $casts = [
        'default_days' => 'float',
        'carry_forward' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

