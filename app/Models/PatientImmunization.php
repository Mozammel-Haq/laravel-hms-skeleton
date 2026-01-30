<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * PatientImmunization Model
 *
 * Represents an immunization/vaccine record for a patient.
 * Tracks vaccine name, date, and provider.
 *
 * @property int $id
 * @property int $patient_id
 * @property string $vaccine_name
 * @property \Illuminate\Support\Carbon|null $immunization_date
 * @property string|null $provider_name
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Patient $patient
 */

use App\Models\Concerns\LogsActivity;

class PatientImmunization extends Model
{
    use LogsActivity;

    public function getActivityDescription($action)
    {
        $patientName = $this->patient ? $this->patient->name : 'Unknown Patient';
        return ucfirst($action) . " immunization ({$this->vaccine_name}) for {$patientName}";
    }

    protected $fillable = [
        'patient_id',
        'vaccine_name',
        'immunization_date',
        'provider_name',
        'notes',
    ];

    protected $casts = [
        'immunization_date' => 'date',
    ];

    /**
     * Get the patient associated with the immunization.
     *
     * Relationship: Belongs to Patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
