<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * AppointmentRequest Model
 *
 * Represents a request from a patient to cancel or reschedule an appointment.
 * Managed by admins who can approve or reject the request.
 *
 * @property int $id
 * @property int $appointment_id
 * @property int $clinic_id
 * @property string $type 'cancel' or 'reschedule'
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $desired_date
 * @property \Illuminate\Support\Carbon|null $desired_time
 * @property string $status 'pending', 'approved', 'rejected'
 * @property string|null $admin_notes
 * @property int|null $processed_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \App\Models\Appointment $appointment
 * @property-read \App\Models\User|null $processor
 */
class AppointmentRequest extends BaseTenantModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'appointment_id',
        'clinic_id',
        'type', // cancel, reschedule
        'reason',
        'desired_date',
        'desired_time',
        'status', // pending, approved, rejected
        'admin_notes',
        'processed_by'
    ];

    protected $casts = [
        'desired_date' => 'date',
        'desired_time' => 'datetime:H:i', // or just string if preferred, but datetime casting is often safer
    ];

    /**
     * Get the appointment associated with this request.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appointment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the user (admin) who processed this request.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function processor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
