<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Role;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * User Model
 *
 * Represents a system user (Admin, Doctor, Staff, etc.).
 * Handles authentication, roles, and clinic association.
 *
 * @property int $id
 * @property int|null $clinic_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $phone
 * @property string $status 'active', 'inactive', 'banned'
 * @property boolean $is_two_factor_enabled
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \App\Models\Clinic|null $clinic
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read \App\Models\Doctor|null $doctor
 * @property-read string $profile_photo_url
 */

use App\Models\Concerns\LogsActivity;

class User extends BaseTenantModel implements AuthenticatableContract, MustVerifyEmailContract, CanResetPasswordContract, AuthorizableContract
{
    use HasFactory, Notifiable, AuthenticatableTrait, MustVerifyEmailTrait, CanResetPasswordTrait, SoftDeletes, Authorizable, LogsActivity;

    public function getActivityDescription($action)
    {
        $role = $this->roles->first() ? $this->roles->first()->name : 'No Role';
        return ucfirst($action) . " user {$this->name} ({$role})";
    }

    /**
     * Fillable attributes
     */
    protected $fillable = [
        'clinic_id',
        'name',
        'email',
        'password',
        'phone',
        'status',
        'is_two_factor_enabled',
        'last_login_at',
        'email_verified_at',
        'profile_photo_path',
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casts
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'    => 'datetime',
        'password'         => 'hashed',
        'is_two_factor_enabled' => 'boolean',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the URL to the user's profile photo.
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : asset('assets/img/users/user-01.jpg'); // Default image
    }

    /**
     * Relationships
     */
    /**
     * Get the clinic associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the roles assigned to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    /**
     * Get the doctor profile associated with the user (if any).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function doctor(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Doctor::class);
    }

    /**
     * Role / Permission helpers
     */
    /**
     * Check if the user has a specific role.
     *
     * @param string|\App\Models\Role $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
        return $this->roles->contains($role);
    }

    /**
     * Check if the user has any of the given roles.
     *
     * @param array|string $roles
     * @return bool
     */
    public function hasAnyRole($roles): bool
    {
        if (is_array($roles)) {
            return $this->roles->whereIn('name', $roles)->isNotEmpty();
        }
        return $this->hasRole($roles);
    }

    /**
     * Check if the user has a specific permission via their roles.
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        return $this->roles->flatMap->permissions->contains('name', $permission);
    }

    /**
     * Assign a role to the user.
     *
     * @param string|\App\Models\Role $role
     * @return void
     */
    public function assignRole($role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }
        $this->roles()->syncWithoutDetaching($role);
    }

    /**
     * Set password attribute automatically hashed.
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute($value): void
    {
        if ($value) {
            $this->attributes['password'] = Hash::needsRehash($value)
                ? Hash::make($value)
                : $value;
        }
    }
}
