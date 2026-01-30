<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * DoctorCertification Model
 *
 * Represents a professional certification held by a doctor.
 * Validates the doctor's qualifications.
 *
 * @property int $id
 * @property int $doctor_id
 * @property string $title
 * @property string|null $issued_by
 * @property \Illuminate\Support\Carbon|null $issued_date
 * @property \Illuminate\Support\Carbon|null $expiry_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Doctor $doctor
 */
class DoctorCertification extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the doctor associated with the certification.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
