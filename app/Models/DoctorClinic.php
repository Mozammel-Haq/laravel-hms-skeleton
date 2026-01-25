<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class DoctorClinic extends BaseTenantModel
{
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
