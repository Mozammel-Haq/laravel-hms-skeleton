<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class ActivityLog extends BaseTenantModel
{
    protected $guarded = ['id']; // Allow clinic_id to be set manually

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
