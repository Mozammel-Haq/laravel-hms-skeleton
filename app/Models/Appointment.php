<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\NotifiesRoles;
/**
 * Appointment Model
 *
 * Represents a scheduled meeting between a patient and a doctor.
 * Tracks status, timing, and related visit/consultation.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $patient_id
 * @property int $doctor_id
 * @property int $department_id
 * @property \Illuminate\Support\Carbon $appointment_date
 * @property string $start_time
 * @property string $end_time
 * @property string $appointment_type 'online', 'in_person'
 * @property string|null $reason_for_visit
 * @property string $booking_source 'reception', 'patient_portal'
 * @property string $status 'pending', 'confirmed', 'cancelled', 'completed'
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\Doctor $doctor
 * @property-read \App\Models\Visit|null $visit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AppointmentStatusLog[] $statusLogs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AppointmentRequest[] $requests
 */

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends BaseTenantModel
{
    use LogsActivity, NotifiesRoles, SoftDeletes;

    protected static function booted()
    {
        static::saving(function ($appointment) {
            if ($appointment->appointment_type === 'online' && empty($appointment->meeting_link)) {
                $appointment->meeting_link = 'https://meet.jit.si/HMS-'.\Illuminate\Support\Str::random(10);
            }
        });

        static::created(function ($appointment) {
            // Notify Doctor
            if ($appointment->doctor && $appointment->doctor->user) {
                $appointment->doctor->user->notify(new \App\Notifications\GeneralNotification(
                    'New Appointment',
                    "New appointment with {$appointment->patient?->name} on {$appointment->appointment_date?->format('Y-m-d')}",
                    route('appointments.show', $appointment->id) // Assuming route exists
                ));
            }

            // Notify Receptionist
            $appointment->notifyRole('Receptionist', 'New Appointment', "New appointment scheduled for {$appointment->patient?->name}.");
        });
    }

    public function getActivityDescription($action)
    {
        $patientName = $this->patient ? $this->patient->name : 'Unknown Patient';
        $doctorName = $this->doctor ? $this->doctor->user->name : 'Unknown Doctor';

        return ucfirst($action)." appointment for {$patientName} with Dr. {$doctorName} on {$this->appointment_date->format('Y-m-d')}";
    }

    /**
     * Get the patient associated with the appointment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor associated with the appointment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the department associated with the appointment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the visit associated with the appointment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function visit()
    {
        return $this->hasOne(Visit::class);
    }

    /**
     * Get the status logs for this appointment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statusLogs()
    {
        return $this->hasMany(AppointmentStatusLog::class);
    }

    /**
     * Get the requests associated with this appointment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requests()
    {
        return $this->hasMany(AppointmentRequest::class);
    }

    public $timestamps = true;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'doctor_id',
        'department_id',
        'appointment_date',
        'start_time',
        'end_time',
        'appointment_type',
        'reason_for_visit',
        'meeting_link',
        'booking_source',
        'status',
        'fee',
        'visit_type',
        'created_by',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public function getStartTimeAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        $valueStr = trim(is_string($value) ? $value : (string) $value);
        if (preg_match('/^\d{2}:\d{2}$/', $valueStr) === 1) {
            $valueStr .= ':00';
        }

        $date = $this->appointment_date instanceof Carbon
            ? $this->appointment_date
            : ($this->appointment_date ? Carbon::parse($this->appointment_date) : Carbon::today());

        return Carbon::parse($date->format('Y-m-d').' '.$valueStr);
    }

    public function setStartTimeAttribute($value): void
    {
        if ($value === null) {
            $this->attributes['start_time'] = null;

            return;
        }

        if ($value instanceof Carbon) {
            $this->attributes['start_time'] = $value->format('H:i:s');

            return;
        }

        $valueStr = trim((string) $value);

        if (preg_match('/^\d{2}:\d{2}$/', $valueStr) === 1) {
            $this->attributes['start_time'] = $valueStr.':00';

            return;
        }

        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $valueStr) === 1) {
            $this->attributes['start_time'] = $valueStr;

            return;
        }

        $this->attributes['start_time'] = Carbon::parse($valueStr)->format('H:i:s');
    }

    public function getEndTimeAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        $valueStr = trim(is_string($value) ? $value : (string) $value);
        if (preg_match('/^\d{2}:\d{2}$/', $valueStr) === 1) {
            $valueStr .= ':00';
        }

        $date = $this->appointment_date instanceof Carbon
            ? $this->appointment_date
            : ($this->appointment_date ? Carbon::parse($this->appointment_date) : Carbon::today());

        return Carbon::parse($date->format('Y-m-d').' '.$valueStr);
    }

    public function setEndTimeAttribute($value): void
    {
        if ($value === null) {
            $this->attributes['end_time'] = null;

            return;
        }

        if ($value instanceof Carbon) {
            $this->attributes['end_time'] = $value->format('H:i:s');

            return;
        }

        $valueStr = trim((string) $value);

        if (preg_match('/^\d{2}:\d{2}$/', $valueStr) === 1) {
            $this->attributes['end_time'] = $valueStr.':00';

            return;
        }

        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $valueStr) === 1) {
            $this->attributes['end_time'] = $valueStr;

            return;
        }

        $this->attributes['end_time'] = Carbon::parse($valueStr)->format('H:i:s');
    }

    protected function normalizeTimeString(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $v = trim($value);
        if ($v === '') {
            return null;
        }
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $v) === 1) {
            return $v;
        }
        if (preg_match('/^\d{1,2}:\d{2}\s*(AM|PM)$/i', $v) === 1) {
            return Carbon::createFromFormat('h:i A', strtoupper($v))->format('H:i:s');
        }
        if (preg_match('/^\d{2}:\d{2}$/', $v) === 1) {
            return $v.':00';
        }
        return Carbon::parse($v)->format('H:i:s');
    }

    public function startDateTimeTz(): ?Carbon
    {
        $date = $this->appointment_date instanceof Carbon
            ? $this->appointment_date
            : ($this->appointment_date ? Carbon::parse($this->appointment_date) : null);
        if (! $date) {
            return null;
        }
        $tz = optional($this->clinic)->timezone ?? config('app.timezone');
        $startRaw = $this->attributes['start_time'] ?? null;
        $startStr = $this->normalizeTimeString($startRaw);
        if (! $startStr) {
            return null;
        }
        return Carbon::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d').' '.$startStr, $tz);
    }

    public function endDateTimeTz(): ?Carbon
    {
        $date = $this->appointment_date instanceof Carbon
            ? $this->appointment_date
            : ($this->appointment_date ? Carbon::parse($this->appointment_date) : null);
        if (! $date) {
            return null;
        }
        $tz = optional($this->clinic)->timezone ?? config('app.timezone');
        $endRaw = $this->attributes['end_time'] ?? null;
        $endStr = $this->normalizeTimeString($endRaw);
        if (! $endStr) {
            return null;
        }
        return Carbon::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d').' '.$endStr, $tz);
    }

    public function isUpcomingNow(): ?bool
    {
        $date = $this->appointment_date instanceof Carbon
            ? $this->appointment_date
            : ($this->appointment_date ? Carbon::parse($this->appointment_date) : null);
        if (! $date) {
            return null;
        }
        $tz = optional($this->clinic)->timezone ?? config('app.timezone');
        $now = Carbon::now($tz);
        if ($date->isFuture() && ! $date->isToday()) {
            return true;
        }
        if ($date->isPast() && ! $date->isToday()) {
            return false;
        }
        $start = $this->startDateTimeTz();
        if (! $start) {
            return null;
        }
        return $now->lt($start);
    }

    public function isPassedNow(): ?bool
    {
        $date = $this->appointment_date instanceof Carbon
            ? $this->appointment_date
            : ($this->appointment_date ? Carbon::parse($this->appointment_date) : null);
        if (! $date) {
            return null;
        }
        $tz = optional($this->clinic)->timezone ?? config('app.timezone');
        $now = Carbon::now($tz);
        if ($date->isPast() && ! $date->isToday()) {
            return true;
        }
        if ($date->isFuture() && ! $date->isToday()) {
            return false;
        }
        $end = $this->endDateTimeTz() ?: $this->startDateTimeTz();
        if (! $end) {
            return null;
        }
        return $now->gte($end);
    }

    public function isOngoingNow(): ?bool
    {
        $up = $this->isUpcomingNow();
        $passed = $this->isPassedNow();
        if ($up === null || $passed === null) {
            return null;
        }
        return $up === false && $passed === false;
    }
}
