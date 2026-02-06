# Class 48: Double Booking Prevention

## Introduction
Two patients cannot book the same doctor at the same time. This is a critical race condition.

## 1. Database Level (Optional but Recommended)
We can't easily add a UNIQUE constraint because start/end times might vary (e.g., 09:00-09:15 vs 09:10-09:20). Overlaps are hard to constrain in SQL.

## 2. Application Level Logic
In `AppointmentService` or `AppointmentController`.

```php
public function isSlotAvailable(Doctor $doctor, $date, $startTime, $endTime)
{
    return ! Appointment::where('doctor_id', $doctor->id)
        ->where('date', $date)
        ->where(function ($query) use ($startTime, $endTime) {
            $query->where(function ($q) use ($startTime, $endTime) {
                // Check for overlap
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            });
        })
        ->where('status', '!=', 'cancelled')
        ->exists();
}
```

## 3. Atomic Locking
To prevent two users clicking "Book" at the exact same millisecond:

```php
DB::transaction(function () use ($request) {
    // Lock the doctor row? Or just use 'lockForUpdate' on a dummy row?
    // Better: explicit check inside transaction.
    
    $exists = Appointment::where(...)
        ->lockForUpdate() // Pessimistic locking
        ->exists();
        
    if ($exists) throw new Exception("Slot taken");
    
    Appointment::create(...);
});
```

## Summary
Preventing double bookings maintains the integrity of the schedule and prevents angry patients.
