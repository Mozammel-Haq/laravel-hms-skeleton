<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\LogsActivity;

/**
 * Admission Model
 *
 * Represents an IPD admission.
 * Tracks patient stay, bed assignments, and services.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $patient_id
 * @property int $admitting_doctor_id
 * @property \Illuminate\Support\Carbon $admission_date
 * @property string $admission_reason
 * @property int|null $current_bed_id
 * @property string $status 'admitted', 'transferred', 'discharged'
 * @property bool $discharge_recommended
 * @property int|null $discharge_recommended_by
 * @property int|null $discharged_by
 * @property \Illuminate\Support\Carbon|null $discharge_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\Doctor $doctor
 * @property-read \App\Models\Bed|null $currentBed
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BedAssignment[] $bedAssignments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InpatientService[] $services
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InpatientRound[] $rounds
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PatientVital[] $vitals
 */

use App\Models\Concerns\NotifiesRoles;

class Admission extends BaseTenantModel
{
    use SoftDeletes, LogsActivity, NotifiesRoles;

    protected static function booted()
    {
        static::created(function ($admission) {
            $admission->notifyRole('Nurse', 'New Admission', "Patient {$admission->patient->name} admitted.");
        });
    }

    public function getActivityDescription($action)
    {
        $patientName = $this->patient ? $this->patient->name : 'Unknown Patient';
        $doctorName = $this->doctor ? $this->doctor->user->name : 'Unknown Doctor';
        return ucfirst($action) . " admission for {$patientName} (Dr. {$doctorName})";
    }

    /**
     * Get the patient associated with the admission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor who admitted the patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'admitting_doctor_id');
    }

    /**
     * Get the current bed assigned to the admission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentBed()
    {
        return $this->belongsTo(Bed::class, 'current_bed_id');
    }

    /**
     * Get the bed assignments for this admission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bedAssignments()
    {
        return $this->hasMany(BedAssignment::class);
    }

    /**
     * Get the services provided during this admission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services()
    {
        return $this->hasMany(InpatientService::class);
    }

    /**
     * Get the doctor rounds for this admission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rounds()
    {
        return $this->hasMany(InpatientRound::class);
    }

    /**
     * Get the vitals recorded during this admission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vitals()
    {
        return $this->hasMany(PatientVital::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
