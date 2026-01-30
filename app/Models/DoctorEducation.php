<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * DoctorEducation Model
 *
 * Represents the educational background of a doctor.
 * Includes degrees, universities, and graduation years.
 *
 * @property int $id
 * @property int $doctor_id
 * @property string $degree
 * @property string $institution
 * @property string|null $country
 * @property int|null $start_year
 * @property int|null $end_year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Doctor $doctor
 */
class DoctorEducation extends Model
{
    protected $guarded = ['id'];

    protected $table = 'doctor_education';

    /**
     * Get the doctor associated with the education record.
     *
     * Relationship: Belongs to Doctor.
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
