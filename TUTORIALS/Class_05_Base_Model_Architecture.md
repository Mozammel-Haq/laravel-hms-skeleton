# Class 05: Base Model Architecture

## Introduction
One of the most powerful features of our HMS architecture is the **BaseTenantModel**. Instead of manually adding `where('clinic_id', $id)` to every single database query, we will create a base model that handles this automatically using Laravel's **Global Scopes**.

## 1. The Concept of Global Scopes
A Global Scope allows you to add constraints to all queries for a given model.
We want every query (select, update, delete) to be scoped to the current clinic.

## 2. Creating the BelongsToClinic Trait
First, we define a Trait that contains the scope logic. This allows us to apply it to any model easily.

**Action:** Create `app/Models/Concerns/BelongsToClinic.php`.

```php
<?php

namespace App\Models\Concerns;

use App\Models\Clinic;
use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToClinic
{
    /**
     * The "booted" method of the model.
     * Laravel automatically calls this when the model is booted.
     */
    protected static function bootBelongsToClinic()
    {
        // 1. Add Global Scope for querying
        static::addGlobalScope('clinic', function (Builder $builder) {
            if ($clinicId = TenantContext::getClinicId()) {
                $builder->where($builder->qualifyColumn('clinic_id'), $clinicId);
            }
        });

        // 2. Add Observer for saving
        // Automatically set clinic_id when creating a new record
        static::creating(function ($model) {
            if (!$model->clinic_id && $clinicId = TenantContext::getClinicId()) {
                $model->clinic_id = $clinicId;
            }
        });
    }

    /**
     * Define the relationship to the Clinic.
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
```

## 3. Creating the BaseTenantModel
Now we create an abstract class that uses this trait. All our tenant-specific models (Patient, Appointment, Doctor, etc.) will extend this class instead of the standard `Model`.

**Action:** Create `app/Models/Base/BaseTenantModel.php`.

```php
<?php

namespace App\Models\Base;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Model;

/**
 * BaseTenantModel
 *
 * Base class for all models that belong to a specific clinic.
 * Automatically applies the clinic scope.
 */
abstract class BaseTenantModel extends Model
{
    use BelongsToClinic;
    
    // Prevent mass-assignment errors for the clinic_id
    // We will typically use specific $fillable in child classes, 
    // but this ensures clinic_id is always safe if we switch to guarded.
    // Note: Child classes should merge their fillables if needed, 
    // or we rely on the creating event to set it.
}
```

## 4. How It Works
1.  **Querying**: When you run `Patient::all()`, Laravel sees the Global Scope. It checks `TenantContext`. If a clinic ID is found (e.g., 1), it effectively runs `select * from patients where clinic_id = 1`.
2.  **Saving**: When you run `Patient::create(['name' => 'John'])`, the `creating` event fires. It checks `TenantContext` and automatically adds `'clinic_id' => 1` to the data before it hits the database.

## Summary
We have built the automated multi-tenancy engine of our application.
- **Trait**: Encapsulates the logic.
- **Global Scope**: Automates filtering.
- **Model Event**: Automates assignment.
- **Base Class**: Standardizes usage.

In Module 2, we will start building the actual Core Entities, starting with the `Clinic` model itself.
