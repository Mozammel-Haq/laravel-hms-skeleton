<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * PatientVital Model
 *
 * Represents a vital signs record for a patient (BP, Heart Rate, etc.).
 * Can be linked to a visit, admission, or round.
 *
 * @property int $id
 * @property int $patient_id
 * @property int|null $visit_id
 * @property int|null $admission_id
 * @property int|null $inpatient_round_id
 * @property string|null $blood_pressure
 * @property int|null $heart_rate
 * @property float|null $temperature
 * @property float|null $spo2
 * @property int|null $respiratory_rate
 * @property float|null $weight
 * @property float|null $height
 * @property float|null $bmi
 * @property int $recorded_by
 * @property \Illuminate\Support\Carbon $recorded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\Visit|null $visit
 * @property-read \App\Models\Admission|null $admission
 * @property-read \App\Models\InpatientRound|null $round
 * @property-read \App\Models\User $recorder
 */

use App\Models\Concerns\LogsActivity;

class PatientVital extends Model
{
    use LogsActivity;

    public function getActivityDescription($action)
    {
        $patientName = $this->patient ? $this->patient->name : 'Unknown Patient';
        return ucfirst($action) . " vitals for {$patientName} (BP: {$this->blood_pressure}, Temp: {$this->temperature})";
    }

    protected $table = 'patient_vitals';

    protected $fillable = [
        'patient_id',
        'visit_id',
        'admission_id',
        'inpatient_round_id',
        'blood_pressure',
        'heart_rate',
        'temperature',
        'spo2',
        'respiratory_rate',
        'weight',
        'height',
        'bmi',
        'recorded_by',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    /**
     * Get the patient associated with the vitals.
     *
     * Relationship: Belongs to Patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the visit associated with the vitals.
     *
     * Relationship: Belongs to Visit.
     * Optional link if vitals were taken during an outpatient visit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function visit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get the admission associated with the vitals.
     *
     * Relationship: Belongs to Admission.
     * Optional link if vitals were taken during an admission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admission(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    /**
     * Get the inpatient round associated with the vitals.
     *
     * Relationship: Belongs to InpatientRound.
     * Optional link if vitals were taken during a doctor's round.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function round(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\InpatientRound::class, 'inpatient_round_id');
    }

    /**
     * Get the user who recorded the vitals.
     *
     * Relationship: Belongs to User.
     * Tracks the staff member who took the measurements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recorder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
