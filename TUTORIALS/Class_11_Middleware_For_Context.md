# Class 11: Middleware for Tenant Context

## Introduction
We have the `TenantContext` class to hold the ID. We have the Models to use the ID. Now we need something to **set** the ID when a request comes in. That is the job of Middleware.

## 1. Creating the Middleware
Run the command:
```bash
php artisan make:middleware EnsureClinicContext
```

## 2. Implementing the Logic
Open `app/Http/Middleware/EnsureClinicContext.php`.

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Support\TenantContext;
use Symfony\Component\HttpFoundation\Response;

class EnsureClinicContext
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is logged in
        if (auth()->check()) {
            $user = auth()->user();
            
            // 2. If user has a clinic_id, set the context
            if ($user->clinic_id) {
                TenantContext::setClinicId($user->clinic_id);
            } 
            // 3. Optional: If user is Super Admin and NO clinic_id (Global Admin)
            // We might not set a context, allowing them to see everything 
            // (if we use withoutGlobalScope logic elsewhere).
        }

        return $next($request);
    }
}
```

## 3. Registering the Middleware
Open `app/Http/Kernel.php` (or `bootstrap/app.php` in Laravel 11).
*Assuming Laravel 10 structure for this guide as it's explicit:*

Add it to the `web` middleware group or map it as a route middleware.
Since this is an HMS where almost every route needs context, adding it to the `web` group is often easiest.

**However**, for better control, we usually alias it.

```php
// app/Http/Kernel.php
protected $middlewareAliases = [
    // ...
    'clinic.context' => \App\Http\Middleware\EnsureClinicContext::class,
];
```

## 4. Applying to Routes
In `routes/web.php`:

```php
Route::middleware(['auth', 'clinic.context'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    // All these routes will have the scope applied
});
```

## Summary
The Middleware is the bridge between the Authentication system (who is this?) and the Multi-tenancy system (what data can they see?).

In the next class, we will write a test to prove this actually works.
