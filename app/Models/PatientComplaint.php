<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\NotifiesRoles;

/**
 * PatientComplaint Model
 *
 * Represents a complaint or grievance filed by a patient.
 * Used for quality assurance and patient satisfaction tracking.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $patient_id
 * @property string $subject
 * @property string $description
 * @property string $status 'pending', 'resolved', 'dismissed'
 * @property string|null $resolution_notes
 * @property int|null $resolved_by
 * @property \Illuminate\Support\Carbon|null $resolved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\User|null $resolver
 */
class PatientComplaint extends BaseTenantModel
{
    use LogsActivity, NotifiesRoles;

    protected static function booted()
    {
        static::created(function ($complaint) {
            $complaint->notifyRole('Clinic Admin', 'New Patient Complaint', "Complaint filed by {$complaint->patient->name}.");
        });
    }

    protected $table = 'patient_complaints';
    protected $guarded = ['id'];

    public function getActivityDescription($action)
    {
        $patientName = $this->patient ? $this->patient->name : 'Unknown Patient';
        $complaintExcerpt = substr($this->description, 0, 30) . (strlen($this->description) > 30 ? '...' : '');
        return ucfirst($action) . " complaint for {$patientName}: \"{$complaintExcerpt}\"";
    }

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the patient who filed the complaint.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the user who resolved the complaint.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
