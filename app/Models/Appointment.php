<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends BaseTenantModel
{
    use SoftDeletes;
    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function visit() { return $this->hasOne(Visit::class); }
    public function statusLogs() { return $this->hasMany(AppointmentStatusLog::class); }
    public function requests() { return $this->hasMany(AppointmentRequest::class); }

    public $timestamps = true;

    protected $casts = [
        'appointment_date' => 'date',
    ];
}
