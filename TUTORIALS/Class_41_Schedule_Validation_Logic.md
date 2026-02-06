# Class 41: Schedule Validation Logic

## Introduction
We must prevent invalid schedules.
1.  End time cannot be before Start time.
2.  Shifts cannot overlap (e.g., 09:00-11:00 AND 10:00-12:00 on the same day).

## 1. Validation Rules
In `DoctorScheduleController`:

```php
$request->validate([
    'schedules.*.start' => 'nullable|date_format:H:i',
    'schedules.*.end' => 'nullable|date_format:H:i|after:schedules.*.start',
]);
```

The `after` rule works great for simple start/end checks.

## 2. Advanced Validation (Overlaps)
If we allowed multiple shifts per day (e.g., Morning Shift & Evening Shift), the logic gets harder.

```php
// Custom validation logic
foreach ($shifts as $shift) {
    // Check if this shift overlaps with any other shift on the same day
    // (StartA < EndB) and (EndA > StartB)
}
```

For our tutorial, we will stick to **One Continuous Shift per Day** to keep it simple, but in a real app, you would support split shifts.

## Summary
Valid data is the foundation of a reliable scheduling system. If the schedule is garbage, the appointments will be garbage.
