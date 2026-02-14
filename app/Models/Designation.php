<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class Designation extends BaseTenantModel
{
    protected $fillable = [
        'clinic_id',
        'name',
        'slug',
        'code',
        'grade',
        'description',
        'status',
    ];
}
