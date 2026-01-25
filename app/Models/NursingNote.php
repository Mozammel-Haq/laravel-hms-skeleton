<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class NursingNote extends BaseTenantModel
{
    //
    protected $casts = [
        'created_at' => 'date',
    ];
}
