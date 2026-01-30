<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * PatientAllergy Model
 *
 * Represents an allergy recorded for a patient.
 * Includes severity and notes.
 *
 * @property int $id
 * @property int $patient_id
 * @property string $allergy_name
 * @property string|null $severity 'mild', 'moderate', 'severe'
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Patient $patient
 */

use App\Models\Concerns\LogsActivity;

class PatientAllergy extends Model
{
    use LogsActivity;

    public function getActivityDescription($action)
    {
        $patientName = $this->patient ? $this->patient->name : 'Unknown Patient';
        return ucfirst($action) . " allergy ({$this->allergy_name}) for {$patientName}";
    }

    protected $fillable = [
        'patient_id',
        'allergy_name',
        'severity',
        'notes',
    ];

    /**
     * Get the patient associated with the allergy.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
