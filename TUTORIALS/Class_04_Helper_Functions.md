# Class 04: Helper Functions & Support Classes

## Introduction
In a complex application, you often need global helper functions or support classes to manage state that doesn't fit neatly into a Model or Controller. In our HMS, the most critical piece of state is the **Current Clinic** (Tenant).

We will create a `Support` directory and a `TenantContext` class to manage this.

## 1. Creating the Support Directory
Create a new directory `app/Support`.

## 2. Implementing TenantContext
This class will be responsible for setting and getting the current `clinic_id`. It acts as a singleton-like wrapper around the logic of "Who is the current tenant?".

**Action:** Create `app/Support/TenantContext.php`.

```php
<?php

namespace App\Support;

class TenantContext
{
    /**
     * The key used to store the clinic ID in the session/cache/static request.
     * We use a static property for the duration of a single request.
     */
    protected static $clinicId = null;

    /**
     * Set the current clinic ID.
     *
     * @param int $id
     * @return void
     */
    public static function setClinicId(int $id)
    {
        self::$clinicId = $id;
    }

    /**
     * Get the current clinic ID.
     *
     * @return int|null
     */
    public static function getClinicId()
    {
        // 1. If explicitly set for this request (e.g. via Middleware), return it.
        if (self::$clinicId) {
            return self::$clinicId;
        }

        // 2. If the user is logged in, return their clinic_id.
        // This is the most common case.
        if (auth()->check() && auth()->user()->clinic_id) {
            return auth()->user()->clinic_id;
        }

        return null;
    }
    
    /**
     * Check if a tenant context is active.
     */
    public static function check(): bool
    {
        return !is_null(self::getClinicId());
    }
}
```

## 3. Why Static?
We use static properties here because the "Current Tenant" is a global state for the duration of a *single HTTP request*. When the request finishes, the PHP process dies (or cleans up), and the static variable is reset. This is a simple and effective way to share this data across Models, Controllers, and Views without passing `$clinic_id` as a parameter to every single function.

## 4. Usage Example
Later, in our controllers or models, we can simply do:

```php
use App\Support\TenantContext;

$id = TenantContext::getClinicId();
```

## Summary
We have created a dedicated support class to handle our Multi-Tenancy context. This ensures that we have a single source of truth for "Which clinic is currently active?".

In the next class, we will use this `TenantContext` to build our `BaseTenantModel`, which will automate the data filtering process.
