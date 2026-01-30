<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

/**
 * AppointmentStatusLog Model
 *
 * Logs changes to an appointment's status (e.g., scheduled -> completed).
 * Used for auditing and tracking appointment history.
 *
 * @property int $id
 * @property int $appointment_id
 * @property string $old_status
 * @property string $new_status
 * @property int $changed_by
 * @property string|null $change_reason
 * @property \Illuminate\Support\Carbon $changed_at
 *
 * @property-read \App\Models\Appointment $appointment
 * @property-read \App\Models\User $changer
 */
class AppointmentStatusLog extends BaseTenantModel
{
    protected $casts = [
        'created_at' => 'date',
    ];
}
