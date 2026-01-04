<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class DoctorSchedule extends BaseTenantModel
{
     public function doctor() { return $this->belongsTo(Doctor::class); }
    public function exceptions() { return $this->hasMany(DoctorScheduleException::class); }
}
