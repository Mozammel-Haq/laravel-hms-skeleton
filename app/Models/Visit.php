<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Concerns\LogsActivity;
/**
 * Visit Model
 *
 * Represents a patient's visit to the clinic (usually linked to an appointment).
 * Tracks check-in/out times and visit status.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $appointment_id
 * @property \Illuminate\Support\Carbon|null $check_in_time
 * @property \Illuminate\Support\Carbon|null $check_out_time
 * @property string $visit_status 'waiting', 'in_progress', 'completed', 'cancelled'
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Appointment $appointment
 * @property-read \App\Models\Consultation|null $consultation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invoice[] $invoices
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PatientVital[] $vitals
 */

use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends BaseTenantModel
{
    use LogsActivity, SoftDeletes;

    public function getActivityDescription($action)
    {
        $patientName = $this->appointment && $this->appointment->patient ? $this->appointment->patient->name : 'Unknown Patient';

        return ucfirst($action)." visit for {$patientName} ({$this->visit_status})";
    }

    /**
     * Get the appointment associated with the visit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the consultation associated with the visit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

    /**
     * Get the invoices associated with the visit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the vitals recorded during the visit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vitals()
    {
        return $this->hasMany(PatientVital::class, 'visit_id');
    }

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'created_at' => 'datetime',
    ];
}
