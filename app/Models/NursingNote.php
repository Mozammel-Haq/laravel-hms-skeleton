<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

/**
 * NursingNote Model
 *
 * Represents a note recorded by a nurse for an admitted patient.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $admission_id
 * @property int $nurse_id
 * @property string $notes
 * @property \Illuminate\Support\Carbon $recorded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Admission $admission
 * @property-read \App\Models\User $nurse
 */
class NursingNote extends BaseTenantModel
{
    //
    protected $casts = [
        'created_at' => 'date',
        'recorded_at' => 'datetime',
    ];

    /**
     * Get the admission associated with the note.
     */
    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    /**
     * Get the nurse who recorded the note.
     */
    public function nurse()
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }
}
