<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * PatientMedicalHistory Model
 *
 * Represents a medical condition or history record for a patient.
 *
 * @property int $id
 * @property int $patient_id
 * @property string $condition_name
 * @property \Illuminate\Support\Carbon|null $diagnosed_date
 * @property string|null $status
 * @property string|null $doctor_name
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Patient $patient
 */

use App\Models\Concerns\LogsActivity;

class PatientMedicalHistory extends Model
{
    use LogsActivity;

    public function getActivityDescription($action)
    {
        $patientName = $this->patient ? $this->patient->name : 'Unknown Patient';
        return ucfirst($action) . " medical history ({$this->condition_name}) for {$patientName}";
    }

    protected $table = 'patient_medical_history';

    protected $fillable = [
        'patient_id',
        'condition_name',
        'diagnosed_date',
        'status',
        'doctor_name',
        'notes',
    ];

    protected $casts = [
        'diagnosed_date' => 'date',
    ];

    /**
     * Get the patient associated with the medical history.
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
