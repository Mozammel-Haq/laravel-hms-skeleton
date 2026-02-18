<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmCompliancePolicy extends BaseTenantModel
{
    protected $table = 'hrm_compliance_policies';

    protected $fillable = [
        'clinic_id',
        'title',
        'category',
        'description',
        'status',
        'effective_from',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

