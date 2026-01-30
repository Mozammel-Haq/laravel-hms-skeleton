<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Prescription;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * PharmacySale Model
 *
 * Represents a sale transaction in the pharmacy.
 * Tracks the patient, items sold, and payment status.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $prescription_id
 * @property int $patient_id
 * @property \Illuminate\Support\Carbon $sale_date
 * @property string $total_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \App\Models\Patient $patient
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PharmacySaleItem[] $items
 * @property-read \App\Models\Prescription|null $prescription
 */

use App\Models\Concerns\LogsActivity;

class PharmacySale extends BaseTenantModel
{
    use SoftDeletes, LogsActivity;

    public function getActivityDescription($action)
    {
        $patientName = $this->patient ? $this->patient->name : 'Unknown Patient';
        return ucfirst($action) . " pharmacy sale for {$patientName} (Total: {$this->total_amount})";
    }

    /**
     * Get the patient associated with the sale.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the items included in the sale.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(PharmacySaleItem::class);
    }

    /**
     * Get the prescription associated with the sale (if any).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
