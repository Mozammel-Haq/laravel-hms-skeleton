<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class HrmComplianceDocument extends BaseTenantModel
{
    protected $table = 'hrm_compliance_documents';

    protected $fillable = [
        'clinic_id',
        'title',
        'category',
        'document_type',
        'storage_path',
        'description',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

