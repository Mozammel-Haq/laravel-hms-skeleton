<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

/**
 * ActivityLog Model
 *
 * Represents a log of user activities in the system.
 * Tracks who performed an action, what action was performed, and when.
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $clinic_id
 * @property string $action
 * @property string|null $description
 * @property string $entity_type
 * @property int $entity_id
 * @property string|null $ip_address
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User|null $user
 */
class ActivityLog extends BaseTenantModel
{
    const UPDATED_AT = null;

    protected $guarded = ['id']; // Allow clinic_id to be set manually

    /**
     * Get the user who performed the activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject of the activity (polymorphic).
     */
    public function subject()
    {
        return $this->morphTo('entity', 'entity_type', 'entity_id');
    }

    /**
     * Get the name of the actor (User or Patient or System).
     */
    public function getActorNameAttribute()
    {
        if ($this->user) {
            // For staff/admins, show Name + Role
            $role = $this->user->roles->first()->name ?? 'User';
            return $this->user->name . " ({$role})";
        }

        // For patient portal actions (where user_id is null but entity_type is Patient)
        if ($this->entity_type === 'App\Models\Patient' && in_array($this->action, ['login', 'logout'])) {
            $patient = $this->subject;
            return $patient ? $patient->name . ' (Patient)' : 'Patient';
        }

        return 'System';
    }

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
