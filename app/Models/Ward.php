<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

/**
 * Ward Model
 *
 * Represents a ward or department section (e.g., General, ICU).
 * Contains rooms and beds.
 *
 * @property int $id
 * @property int $clinic_id
 * @property string $name
 * @property string $type 'general', 'icu', 'cabin'
 * @property int|null $floor
 * @property string|null $description
 * @property string $status 'active', 'inactive'
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Room[] $rooms
 */
class Ward extends BaseTenantModel
{
    /**
     * Get the rooms in the ward.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Room::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
