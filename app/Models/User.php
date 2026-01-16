<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Role;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends BaseTenantModel implements AuthenticatableContract, MustVerifyEmailContract, CanResetPasswordContract
{
    use HasFactory, Notifiable, AuthenticatableTrait, MustVerifyEmailTrait, CanResetPasswordTrait, SoftDeletes;

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

    /**
     * Relationships
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    /**
     * Role / Permission helpers
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
        return $this->roles->contains($role);
    }

    public function hasPermission($permission)
    {
        return $this->roles->flatMap->permissions->contains('name', $permission);
    }

    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }
        $this->roles()->syncWithoutDetaching($role);
    }

    /**
     * Set password attribute automatically hashed
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::needsRehash($value)
                ? Hash::make($value)
                : $value;
        }
    }
}
