# Class 43: Schedule Exceptions

## Introduction
Doctors get sick, take holidays, or work extra shifts. We need to handle this.

## 1. Controller
`DoctorScheduleExceptionController`.

```php
public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date|after:today',
        'type' => 'required|in:off,extra',
        'reason' => 'nullable|string',
        // if extra, require times
    ]);

    auth()->user()->doctor->exceptions()->create($request->all());
    
    return back();
}
```

## 2. Priority Logic
In our `AppointmentService` (Class 42), we already implemented the logic:
-   **Exception** > **Standard Schedule**.
-   If Exception is 'off', return empty slots.
-   If Exception is 'extra', use exception times instead of standard times.

## Summary
Exceptions allow the system to be flexible and reflect real-world chaos.
