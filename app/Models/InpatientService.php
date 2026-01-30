<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\LogsActivity;

/**
 * InpatientService Model
 *
 * Represents a service provided to an inpatient during their admission.
 * Can include procedures, nursing care, or other billable services.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $admission_id
 * @property string $service_name
 * @property \Illuminate\Support\Carbon $service_date
 * @property int $quantity
 * @property float $unit_price
 * @property float $total_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Admission $admission
 */
class InpatientService extends BaseTenantModel
{
    use \App\Models\Concerns\LogsActivity;

    protected $guarded = ['id'];

    public function getActivityDescription($action)
    {
        return ucfirst($action) . " service '{$this->service_name}' for Admission #{$this->admission_id} (Cost: {$this->total_price})";
    }

    /**
     * Get the admission record associated with this service.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
