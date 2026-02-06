# Class 07: The Clinic Model

## Introduction
The `Clinic` model represents the tenant in our system. It is one of the few models that does **NOT** extend `BaseTenantModel` (because a Clinic doesn't belong to a Clinic; it *is* the Clinic).

## 1. Creating the Model
Run the artisan command to create the model (we already designed the migration in Class 02).

```bash
php artisan make:model Clinic
```

## 2. Implementing the Model Logic
Open `app/Models/Clinic.php` and implement the following.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinic extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * We explicitly list fields to allow for mass assignment safe-guarding.
     */
    protected $fillable = [
        'name',
        'code',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country',
        'postal_code',
        'phone',
        'email',
        'website',
        'logo',
        'timezone',
        'currency',
        'status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'status' => 'string',
        // 'created_at' => 'datetime', // Default
    ];

    /**
     * Relationship: A Clinic has many Users.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relationship: A Clinic has many Departments.
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
```

## 3. Understanding the Code
-   **SoftDeletes**: We use this trait so that if we delete a clinic, it's not permanently removed from the database immediately. It just sets a `deleted_at` timestamp. This is crucial for data safety.
-   **$fillable**: We list `name`, `code`, etc., to allow `Clinic::create([...])` to work.
-   **Relationships**: We define `users()` and `departments()` so we can easily access `$clinic->users`.

## 4. The 'Code' Column
The `code` column (e.g., 'MAYO001', 'CITY002') is important. It's a unique human-readable identifier. We will often use this in URLs or for login contexts (e.g., `clinic.hms.com/login`).

## Summary
We have created the `Clinic` model. This is the "parent" of all data in our system.

In the next class, we will revisit the `TenantContext` class we created earlier and explain its advanced usage.
