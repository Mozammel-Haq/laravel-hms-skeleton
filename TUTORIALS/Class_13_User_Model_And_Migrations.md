# Class 13: User Model & Migrations

## Introduction
The `User` model is the heart of authentication. In a default Laravel installation, it's quite simple. For our HMS, we need to customize it to support Multi-Tenancy and our specific profile requirements.

## 1. Modifying the Migration
We need to modify the default `create_users_table` migration.
Open `database/migrations/2014_10_12_000000_create_users_table.php` (or the equivalent in `database/migrations`).

```php
public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('clinic_id')->nullable(); // Link to Clinic (Tenant)
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->string('phone')->nullable(); // Added for HMS
        $table->string('profile_photo_path', 2048)->nullable(); // Added for profile photos
        $table->string('role')->default('guest'); // Simple role string (backup to RBAC)
        $table->string('status')->default('active'); // active, inactive, banned
        $table->rememberToken();
        $table->timestamps();
        $table->softDeletes(); // Important!

        // Foreign Key
        $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
    });
}
```

*Note: In a real production app with existing data, you would create a new migration to `add_columns_to_users_table`. Since we are rebuilding, we modify the source.*

## 2. The User Model
Open `app/Models/User.php`.

```php
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'clinic_id',
        'name',
        'email',
        'password',
        'phone',
        'role',
        'status',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
```

## 3. Important Design Decisions
-   **`clinic_id`**: This is nullable. Why? Because a "Super Admin" might not belong to any specific clinic (they manage the whole system). Or a "Global Patient" might be independent.
-   **`role` column**: We added a simple `role` column. While we will implement full RBAC (Role Based Access Control) with separate tables next, having a quick lookup column on the user table is often useful for simple checks (e.g., `if ($user->role === 'super_admin')`).

## Summary
We have prepared the User table to accept our HMS-specific data.

In the next class, we will design the full RBAC system.
