<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

/**
 * DoctorScheduleRequest Model
 *
 * Represents a request to change a doctor's schedule.
 * Contains the proposed schedule details in JSON format.
 *
 * @property int $id
 * @property int $doctor_id
 * @property int $clinic_id
 * @property array $schedules
 * @property string $status 'pending', 'approved', 'rejected'
 * @property int $requested_by
 * @property int|null $processed_by
 * @property \Illuminate\Support\Carbon|null $processed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Doctor $doctor
 * @property-read \App\Models\User $requestedBy
 * @property-read \App\Models\User|null $processedBy
 */
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

