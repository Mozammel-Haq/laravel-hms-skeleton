<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class DoctorScheduleException extends BaseTenantModel
{
    protected $guarded = ['id'];

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
