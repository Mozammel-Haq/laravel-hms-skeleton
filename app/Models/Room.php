<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

/**
 * Room Model
 *
 * Represents a room within a ward.
 * Contains beds and tracks availability status.
 *
 * @property int $id
 * @property int|null $clinic_id
 * @property int $ward_id
 * @property string $room_number
 * @property string $room_type
 * @property string $daily_rate
 * @property string $status 'available', 'occupied', 'maintenance'
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Ward $ward
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bed[] $beds
 */
class Room extends BaseTenantModel
{
    /**
     * Get the ward associated with the room.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ward(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    /**
     * Get the beds in the room.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function beds(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bed::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
