<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class DoctorSchedule extends BaseTenantModel
{
    protected $fillable = [
        'doctor_id',
        'clinic_id',
        'department_id',
        'day_of_week',
        'schedule_date',
        'start_time',
        'end_time',
        'slot_duration_minutes',
        'max_patients',
        'status',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function exceptions()
    {
        return $this->hasMany(DoctorScheduleException::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
