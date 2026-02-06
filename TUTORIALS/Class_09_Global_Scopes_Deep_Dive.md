# Class 09: Deep Dive - Global Scopes & Traits

## Introduction
In Class 05, we implemented the `BelongsToClinic` trait. This is the implementation of the "Single-Database" logic. Let's break down the `bootBelongsToClinic` method line-by-line.

## 1. The Magic of "boot{TraitName}"
Laravel has a naming convention. If you have a trait named `BelongsToClinic`, Laravel will look for a method named `bootBelongsToClinic` and execute it when the model boots. This allows traits to register observers and scopes without touching the model's `boot()` method.

## 2. Line-by-Line Analysis

```php
protected static function bootBelongsToClinic()
{
    // Part A: The Query Scope
    static::addGlobalScope('clinic', function (Builder $builder) {
        if ($clinicId = TenantContext::getClinicId()) {
            $builder->where($builder->qualifyColumn('clinic_id'), $clinicId);
        }
    });

    // Part B: The Saving Observer
    static::creating(function ($model) {
        if (!$model->clinic_id && $clinicId = TenantContext::getClinicId()) {
            $model->clinic_id = $clinicId;
        }
    });
}
```

### Part A: The Query Scope
-   `static::addGlobalScope(...)`: Adds a rule to *every* select query.
-   `$builder->qualifyColumn('clinic_id')`: This is smart. It turns `clinic_id` into `patients.clinic_id` (assuming the table is patients). This prevents "Column 'clinic_id' in where clause is ambiguous" errors when doing Joins.
-   **Effect**: `Patient::all()` becomes `SELECT * FROM patients WHERE patients.clinic_id = 1`.

### Part B: The Saving Observer
-   `static::creating(...)`: Runs right before the INSERT query.
-   `if (!$model->clinic_id ...)`: Checks if the developer manually set the ID. If they did (e.g., for a Super Admin creating a record for a specific clinic), we don't overwrite it.
-   **Effect**: `Patient::create(['name' => 'John'])` automatically gets `clinic_id` injected.

## 3. Bypassing the Scope
Sometimes (e.g., for Super Admin dashboards) you *want* to see data from all clinics.
Laravel provides a method for this:

```php
$allPatients = Patient::withoutGlobalScope('clinic')->get();
```

Or, using our custom wrapper (if we added one, but standard Laravel is fine):

```php
$allPatients = Patient::withoutGlobalScopes()->get();
```

## Summary
The `BelongsToClinic` trait is a self-contained module of logic that enforces multi-tenancy. By using a Trait, we can apply this to 50+ models without rewriting a single line of code.

In the next class, we will look at the `BaseTenantModel` which brings this all together.
