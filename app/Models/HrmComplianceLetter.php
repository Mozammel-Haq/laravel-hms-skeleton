<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmComplianceLetter extends BaseTenantModel
{
    protected $table = 'hrm_compliance_letters';

    protected $fillable = [
        'clinic_id',
        'name',
        'code',
        'category',
        'subject',
        'body',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

