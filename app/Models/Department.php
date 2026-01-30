<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Department Model
 *
 * Represents a medical department within a clinic (e.g., Cardiology, Pediatrics).
 * Associates doctors with their primary department.
 *
 * @property int $id
 * @property int $clinic_id
 * @property string $name
 * @property string|null $description
 * @property string|null $floor_number
 * @property string|null $phone_extension
 * @property string $status 'active', 'inactive'
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \App\Models\Clinic $clinic
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Doctor[] $doctors
 */

use App\Models\Concerns\LogsActivity;

class Department extends BaseTenantModel
{
    use SoftDeletes, LogsActivity;

    public function getActivityDescription($action)
    {
        return ucfirst($action) . " department {$this->name}";
    }

    /**
     * Get the clinic that owns the department.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the doctors belonging to this department.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'primary_department_id');
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
