<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 *
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\Doctor $doctor
 * @property-read \App\Models\Visit|null $visit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AppointmentStatusLog[] $statusLogs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AppointmentRequest[] $requests
 */

use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\NotifiesRoles;

class Appointment extends BaseTenantModel
{
    use SoftDeletes, LogsActivity, NotifiesRoles;

    protected static function booted()
    {
        static::created(function ($appointment) {
            // Notify Doctor
            if ($appointment->doctor && $appointment->doctor->user) {
                $appointment->doctor->user->notify(new \App\Notifications\GeneralNotification(
                    'New Appointment',
                    "New appointment with {$appointment->patient->name} on {$appointment->appointment_date->format('Y-m-d')}",
                    route('appointments.show', $appointment->id) // Assuming route exists
                ));
            }

            // Notify Receptionist
            $appointment->notifyRole('Receptionist', 'New Appointment', "New appointment scheduled for {$appointment->patient->name}.");
        });
    }

    public function getActivityDescription($action)
    {
        $patientName = $this->patient ? $this->patient->name : 'Unknown Patient';
        $doctorName = $this->doctor ? $this->doctor->user->name : 'Unknown Doctor';
        return ucfirst($action) . " appointment for {$patientName} with Dr. {$doctorName} on {$this->appointment_date->format('Y-m-d')}";
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

    protected $casts = [
        'appointment_date' => 'date',
    ];
}
