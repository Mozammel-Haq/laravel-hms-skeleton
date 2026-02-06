# Class 29: Dashboard Architecture

## Introduction
When a user logs in, where do they go?
-   **Super Admin**: System overview.
-   **Doctor**: Upcoming appointments.
-   **Receptionist**: Waiting room status.

We need a smart `DashboardController` to route them.

## 1. The Controller
Run: `php artisan make:controller DashboardController`

```php
public function index()
{
    $user = auth()->user();

    if ($user->hasRole('super_admin')) {
        return view('dashboards.super_admin');
    }
    
    if ($user->hasRole('doctor')) {
        // Load specific data for doctor
        $appointments = $user->doctor->appointments()->today()->get();
        return view('dashboards.doctor', compact('appointments'));
    }
    
    if ($user->hasRole('receptionist')) {
        return view('dashboards.receptionist');
    }
    
    if ($user->hasRole('patient')) {
        return view('dashboards.patient');
    }

    return view('dashboard'); // Fallback
}
```

## 2. The Views
Create `resources/views/dashboards/` folder.
-   `super_admin.blade.php`
-   `doctor.blade.php`
-   `receptionist.blade.php`

Example `doctor.blade.php`:
```html
<x-app-layout>
    <x-slot name="header">
        <h2>Doctor Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3>Welcome Dr. {{ auth()->user()->name }}</h3>
                
                <h4>Today's Appointments</h4>
                <!-- Table of appointments -->
            </div>
        </div>
    </div>
</x-app-layout>
```

## 3. Route Setup
In `routes/web.php`:

```php
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
```

## Summary
We created a polymorphic dashboard system. A single URL `/dashboard` serves different content based on who is looking at it.
