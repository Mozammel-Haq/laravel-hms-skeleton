<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

/**
 * PrescriptionItem Model
 *
 * Represents a single medication item within a prescription.
 * Details dosage, frequency, and duration.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $prescription_id
 * @property int $medicine_id
 * @property string $dosage
 * @property string $frequency
 * @property int $duration_days
 * @property string|null $instructions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Medicine $medicine
 */
class PrescriptionItem extends BaseTenantModel
{
    /**
     * Get the medicine associated with the prescription item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medicine(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
