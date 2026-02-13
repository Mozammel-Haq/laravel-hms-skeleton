<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
/**
 * PatientSurgery Model
 *
 * Represents a surgical history record for a patient.
 * Details the surgery type, date, and surgeon.
 *
 * @property int $id
 * @property int $patient_id
 * @property string $surgery_name
 * @property \Illuminate\Support\Carbon|null $surgery_date
 * @property string|null $hospital_name
 * @property string|null $surgeon_name
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Patient $patient
 */

use Illuminate\Database\Eloquent\Model;

class PatientSurgery extends Model
{
    use LogsActivity;

    public function getActivityDescription($action)
    {
        $patientName = $this->patient ? $this->patient->name : 'Unknown Patient';

        return ucfirst($action)." surgery ({$this->surgery_name}) for {$patientName}";
    }

    protected $fillable = [
        'patient_id',
        'surgery_name',
        'surgery_date',
        'hospital_name',
        'surgeon_name',
        'notes',
    ];

    protected $casts = [
        'surgery_date' => 'date',
    ];

    /**
     * Get the patient associated with the surgery.
     *
     * Relationship: Belongs to Patient.
     */
    public function patient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
