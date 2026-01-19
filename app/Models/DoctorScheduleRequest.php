<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class DoctorScheduleRequest extends BaseTenantModel
{
    protected $guarded = ['id', 'clinic_id'];

    protected $casts = [
        'schedules' => 'array',
        'processed_at' => 'datetime',
    ];

    public $timestamps = true;

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}

