<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * DoctorAward Model
 *
 * Represents an award or recognition received by a doctor.
 * Displayed on the doctor's profile.
 *
 * @property int $id
 * @property int $doctor_id
 * @property string $title
 * @property int|null $year
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Doctor $doctor
 */
class DoctorAward extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the doctor associated with the award.
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
