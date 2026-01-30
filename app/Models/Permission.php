<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Permission Model
 *
 * Represents a specific access right (e.g., view_patients, edit_invoices).
 * Assigned to roles to control user access.
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 */
class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    protected $casts = [
        'created_at' => 'date',
    ];

    /**
     * Get the roles that have this permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}
