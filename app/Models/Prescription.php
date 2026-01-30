<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Prescription Model
 *
 * Represents a medical prescription issued during a consultation.
 * Contains medicines and instructions.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $consultation_id
 * @property \Illuminate\Support\Carbon $issued_at
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PrescriptionItem[] $items
 * @property-read \App\Models\Consultation $consultation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PatientComplaint[] $complaints
 * @property-read \App\Models\Clinic $clinic
 * @property-read \App\Models\PharmacySale|null $pharmacySale
 */

use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\NotifiesRoles;

class Prescription extends BaseTenantModel
{
    use SoftDeletes, LogsActivity, NotifiesRoles;

    protected static function booted()
    {
        static::created(function ($prescription) {
            $prescription->notifyRole('Pharmacist', 'New Prescription', "New prescription pending for {$prescription->patient->name}.");
        });
    }

    public function getActivityDescription($action)
    {
        return ucfirst($action) . " prescription #{$this->id}";
    }

    /**
     * Get the items in the prescription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    /**
     * Get the consultation associated with the prescription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consultation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Consultation::class, "consultation_id");
    }

    /**
     * Get the complaints associated with the prescription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function complaints(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(PatientComplaint::class, 'prescription_complaint', 'prescription_id', 'complaint_id');
    }

    /**
     * Get the clinic associated with the prescription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the doctors associated with the prescription through consultation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function doctors(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Doctor::class, Consultation::class, 'doctor_id', 'consultation_id');
    }

    /**
     * Get the pharmacy sale associated with the prescription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pharmacySale(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PharmacySale::class);
    }

    protected $casts = [
        'created_at' => 'date',
    ];
}
