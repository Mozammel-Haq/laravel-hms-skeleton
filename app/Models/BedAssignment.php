<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

/**
 * BedAssignment Model
 *
 * Represents the assignment of a bed to an admission.
 * Tracks when a bed was assigned and to which admission.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $admission_id
 * @property int $bed_id
 * @property \Illuminate\Support\Carbon $assigned_at
 * @property \Illuminate\Support\Carbon|null $released_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Bed $bed
 * @property-read \App\Models\Admission $admission
 */

use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\NotifiesRoles;

class BedAssignment extends BaseTenantModel
{
    use LogsActivity, NotifiesRoles;

    protected $guarded = ['id'];
    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::created(function ($assignment) {
            $assignment->notifyRole('Nurse', 'Bed Assigned', "Bed {$assignment->bed?->bed_number} assigned to admission #{$assignment->admission_id}.");
        });
    }

    public function getActivityDescription($action)
    {
        $bedNumber = $this->bed ? $this->bed->bed_number : 'Unknown Bed';
        $patientName = $this->admission && $this->admission->patient ? $this->admission->patient->name : 'Unknown Patient';
        return ucfirst($action) . " bed {$bedNumber} to {$patientName}";
    }

    /**
     * Get the bed associated with the assignment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bed()
    {
        return $this->belongsTo(Bed::class);
    }

    /**
     * Get the admission associated with the assignment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }
}
