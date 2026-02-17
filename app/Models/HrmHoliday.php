<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmHoliday extends BaseTenantModel
{
    protected $table = 'hrm_holidays';

    protected $fillable = [
        'clinic_id',
        'date',
        'name',
        'type',
        'is_full_day',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'is_full_day' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

