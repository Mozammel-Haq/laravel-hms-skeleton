<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class AppointmentStatusLog extends BaseTenantModel
{
    protected $casts = [
        'created_at' => 'date',
    ];
}
