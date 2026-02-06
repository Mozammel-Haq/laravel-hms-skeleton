# Class 08: Deep Dive - TenantContext

## Introduction
In Class 04, we created `app/Support/TenantContext.php`. Now, let's dive deeper into *why* we built it this way and how to use it effectively.

## 1. The Code Review
Let's look at the `getClinicId` method again:

```php
public static function getClinicId()
{
    // Priority 1: Explicit Override
    if (self::$clinicId) {
        return self::$clinicId;
    }

    // Priority 2: Authenticated User
    if (auth()->check() && auth()->user()->clinic_id) {
        return auth()->user()->clinic_id;
    }

    return null;
}
```

## 2. Why Priority Levels?
### Priority 1: Explicit Override (`self::$clinicId`)
This is used by **Middleware** or **Jobs**.
-   **Middleware**: When a request comes to `api.hms.com/v1/clinic/{id}/...`, the middleware might verify the ID and call `TenantContext::setClinicId($id)`.
-   **Jobs (Queues)**: When a background job runs (e.g., sending a monthly report), there is no logged-in user. The job *must* manually set the context before running queries:
    ```php
    TenantContext::setClinicId($this->clinicId);
    // Now run the report logic...
    ```

### Priority 2: Authenticated User
This is the default for 99% of web requests. A doctor logs in. Their user record says `clinic_id = 5`. The `TenantContext` automatically picks this up.

## 3. Helper Accessor
To make our code cleaner, let's add a helper function in `app/helpers.php` (if you haven't created it, you can create it now, or just use the class directly).

If you want to create a global helper:
1.  Create `app/helpers.php`.
2.  Add:
    ```php
    if (!function_exists('current_clinic_id')) {
        function current_clinic_id() {
            return \App\Support\TenantContext::getClinicId();
        }
    }
    ```
3.  Add `"files": ["app/helpers.php"]` to `composer.json` inside `"autoload"`.
4.  Run `composer dump-autoload`.

*Note: We will strictly use `TenantContext::getClinicId()` in these tutorials for clarity, but helpers are a valid choice.*

## Summary
The `TenantContext` class is a **Singleton State Manager** for the current request. It abstracts away the logic of finding *who* the current tenant is, so our Models don't have to guess.

In the next class, we will examine the `BelongsToClinic` trait in detail.
