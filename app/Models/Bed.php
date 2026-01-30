<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\LogsActivity;

/**
 * Bed Model
 *
 * Represents a hospital bed in a room.
 * Tracks bed status and assignments.
 *
 * @property int $id
 * @property int $room_id
 * @property int $clinic_id
 * @property string $bed_number
 * @property string $status 'available', 'occupied', 'maintenance'
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Room $room
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BedAssignment[] $assignments
 */

class Bed extends BaseTenantModel
{
    use LogsActivity;

    public function getActivityDescription($action)
    {
        $roomNumber = $this->room ? $this->room->room_number : 'Unknown Room';
        return ucfirst($action) . " bed {$this->bed_number} in room {$roomNumber}";
    }

    /**
     * Get the room associated with the bed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the assignments associated with the bed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignments()
    {
        return $this->hasMany(BedAssignment::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
