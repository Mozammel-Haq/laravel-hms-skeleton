<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Concerns\LogsActivity;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
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
 * @property bool $is_two_factor_enabled
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Clinic|null $clinic
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read \App\Models\Doctor|null $doctor
 * @property-read string $profile_photo_url
 */

use Illuminate\Support\Facades\Hash;

class User extends BaseTenantModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, MustVerifyEmailContract
{
    use HasApiTokens, AuthenticatableTrait, Authorizable, CanResetPasswordTrait, HasFactory, LogsActivity, MustVerifyEmailTrait, Notifiable, SoftDeletes;

    public function getActivityDescription($action)
    {
        $role = $this->roles->first() ? $this->roles->first()->name : 'No Role';

        return ucfirst($action)." user {$this->name} ({$role})";
    }

    /**
     * Fillable attributes
     */
    protected $fillable = [
        'clinic_id',
        'department_id',
        'designation_id',
        'join_date',
        'salary_structure_id',
        'basic_salary_override',
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
        'last_login_at' => 'datetime',
        'password' => 'hashed',
        'is_two_factor_enabled' => 'boolean',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? asset('storage/'.$this->profile_photo_path)
            : asset('assets/img/users/user-01.jpg'); // Default image
    }

    public function clinic(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function salaryStructure(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(HrmSalaryStructure::class, 'salary_structure_id');
    }

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function doctor(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Doctor::class);
    }

    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function designation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * Role / Permission helpers
     */
    /**
     * Check if the user has a specific role.
     *
     * @param  string|\App\Models\Role  $role
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
     * @param  array|string  $roles
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
     * @param  string  $permission
     */
    public function hasPermission($permission): bool
    {
        return $this->roles->flatMap->permissions->contains('name', $permission);
    }

    /**
     * Assign a role to the user.
     *
     * @param  string|\App\Models\Role  $role
     */
    public function assignRole($role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }
        $this->roles()->syncWithoutDetaching($role);
        $this->unsetRelation('roles');
    }

    /**
     * Set password attribute automatically hashed.
     *
     * @param  string  $value
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
