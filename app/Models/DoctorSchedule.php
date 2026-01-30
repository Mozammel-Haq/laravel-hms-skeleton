<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

/**
 * DoctorSchedule Model
 *
 * Represents a doctor's availability schedule.
 * Can be a recurring weekly schedule (day_of_week) or a specific date.
 *
 * @property int $id
 * @property int $doctor_id
 * @property int $clinic_id
 * @property int $department_id
 * @property int|null $day_of_week 0=Sunday, 6=Saturday
 * @property \Illuminate\Support\Carbon|null $schedule_date
 * @property string $start_time
 * @property string $end_time
 * @property int $slot_duration_minutes
 * @property int|null $max_patients
 * @property string $status 'active', 'inactive'
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Doctor $doctor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DoctorScheduleException[] $exceptions
 */

use App\Models\Concerns\LogsActivity;

class DoctorSchedule extends BaseTenantModel
{
    use LogsActivity;

    public function getActivityDescription($action)
    {
        $doctorName = $this->doctor && $this->doctor->user ? $this->doctor->user->name : 'Unknown Doctor';
        return ucfirst($action) . " schedule for Dr. {$doctorName}";
    }

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

    /**
     * Get the doctor associated with the schedule.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the exceptions for this schedule.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exceptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DoctorScheduleException::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
