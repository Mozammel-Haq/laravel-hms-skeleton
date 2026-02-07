<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Concerns\NotifiesRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Admission Model
 *
 * Represents an IPD (In-Patient Department) admission.
 * Tracks patient stay, assigned bed, and attending doctor.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $patient_id
 * @property int $admitting_doctor_id
 * @property string $admission_date
 * @property string|null $discharge_date
 * @property string $admission_reason
 * @property string|null $discharge_reason
 * @property string $status 'admitted', 'discharged'
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\Doctor $doctor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BedAssignment[] $bedAssignments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AdmissionDeposit[] $deposits
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InpatientService[] $services
 */
class Admission extends BaseTenantModel
{
    use HasFactory, SoftDeletes, NotifiesRoles;

    protected $guarded = ['id'];

    /**
     * Get the patient associated with the admission.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class)->withTrashed();
    }

    /**
     * Get the doctor attending the admission.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'admitting_doctor_id')->withTrashed();
    }

    /**
     * Get the bed assignments for the admission.
     */
    public function bedAssignments()
    {
        return $this->hasMany(BedAssignment::class);
    }

    /**
     * Get the deposits for the admission.
     */
    public function deposits()
    {
        return $this->hasMany(AdmissionDeposit::class);
    }

    public function rounds()
    {
        return $this->hasMany(InpatientRound::class);
    }

    /**
     * Get the services for the admission.
     */
    public function services()
    {
        return $this->hasMany(InpatientService::class);
    }

    /**
     * Get the vitals for the admission.
     */
    public function vitals()
    {
        return $this->hasMany(PatientVital::class);
    }

    /**
     * Get the final invoice for the admission.
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function getCurrentBedAttribute()
    {
        return $this->bedAssignments()
            ->whereNull('released_at')
            ->with(['bed.room.ward'])
            ->first()?->bed;
    }
}
