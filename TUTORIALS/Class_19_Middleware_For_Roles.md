# Class 19: Middleware for Roles

## Introduction
While Policies protect specific actions on specific models, sometimes we want to protect entire routes based on a Role. For example, the entire `/admin` group should only be accessible to Admins.

## 1. Creating the Middleware
```bash
php artisan make:middleware CheckRole
```

## 2. Implementing Logic
Open `app/Http/Middleware/CheckRole.php`.

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  // Variable number of arguments
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        // Check if user has ANY of the required roles
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
```

## 3. Registering Alias
In `app/Http/Kernel.php`:

```php
protected $middlewareAliases = [
    // ...
    'role' => \App\Http\Middleware\CheckRole::class,
];
```

## 4. Usage in Routes
In `routes/web.php`:

```php
Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});

Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/doctor/patients', [DoctorController::class, 'patients']);
});
```

## Summary
Middleware allows us to act as a "Gatekeeper" at the route level, stopping unauthorized users before the request even reaches the controller.

In the next class, we will finalize Module 3 by looking at the Views.
