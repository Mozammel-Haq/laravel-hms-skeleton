<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Role Model
 *
 * Represents a user role (e.g., Super Admin, Clinic Admin, Doctor).
 * Defines the set of permissions assigned to users with this role.
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 */

use App\Models\Concerns\LogsActivity;

class Role extends Model
{
    use HasFactory, LogsActivity;

    public function getActivityDescription($action)
    {
        return ucfirst($action) . " role {$this->name}";
    }

    protected $fillable = ['name', 'slug', 'description'];

    /**
     * Get the permissions associated with the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    /**
     * Get the users assigned to this role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role');
    }

    /**
     * Grant a permission to the role.
     *
     * @param string|\App\Models\Permission $permission
     * @return void
     */
    public function givePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }

        $this->permissions()->syncWithoutDetaching($permission);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
