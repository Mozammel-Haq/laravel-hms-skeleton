<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

/**
 * DoctorScheduleException Model
 *
 * Represents an exception to a doctor's regular schedule.
 * Used for leave, time off, or extra availability.
 *
 * @property int $id
 * @property int $doctor_id
 * @property int $clinic_id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property bool $is_available
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string|null $reason
 * @property string $status 'pending', 'approved', 'rejected'
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Doctor $doctor
 * @property-read \App\Models\Clinic $clinic
 */

use App\Models\Concerns\LogsActivity;

class DoctorScheduleException extends BaseTenantModel
{
    use LogsActivity;

    public function getActivityDescription($action)
    {
        $doctorName = $this->doctor && $this->doctor->user ? $this->doctor->user->name : 'Unknown Doctor';
        return ucfirst($action) . " schedule exception for Dr. {$doctorName}";
    }

    protected $guarded = ['id'];

    /**
     * Get the doctor associated with the exception.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the clinic associated with the exception.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
