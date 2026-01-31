<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * LeaveRequest Model
 *
 * Represents a leave request submitted by a staff member or doctor.
 * Note: Currently unused in the main application flow (DoctorScheduleException is used for doctors).
 * Kept for potential future staff leave management.
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $leave_type
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property string|null $reason
 * @property string $status 'pending', 'approved', 'rejected'
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User|null $user
 */

use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\NotifiesRoles;

class LeaveRequest extends Model
{
    use LogsActivity, NotifiesRoles;

    protected static function booted()
    {
        static::created(function ($request) {
            $request->notifyRole('Clinic Admin', 'New Leave Request', "New leave request from {$request->user?->name}.");
        });
    }

    public function getActivityDescription($action)
    {
        $userName = $this->user ? $this->user->name : 'Unknown User';
        return ucfirst($action) . " leave request for {$userName}";
    }

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
