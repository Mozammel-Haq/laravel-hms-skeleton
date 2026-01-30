<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

/**
 * DoctorClinic Model
 *
 * Represents the association between a doctor and a clinic.
 * Allows doctors to be assigned to multiple clinics.
 *
 * @property int $id
 * @property int $doctor_id
 * @property int $clinic_id
 * @property string|null $consultation_fee
 * @property bool $display_on_booking
 * @property \Illuminate\Support\Carbon|null $joining_date
 * @property string $status 'active', 'inactive'
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Doctor $doctor
 * @property-read \App\Models\Clinic $clinic
 */
class DoctorClinic extends BaseTenantModel
{
    /**
     * Get the doctor associated with this link.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the clinic associated with this link.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
