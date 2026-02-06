# Class 42: Slot Generation Service

## Introduction
This is the core algorithm of the module. Given a date and a doctor, generate a list of available 15-minute slots.

## 1. The Service
Create `app/Services/AppointmentService.php`.

```php
public function generateSlots(Doctor $doctor, Carbon $date)
{
    $dayName = strtolower($date->format('l')); // "monday"
    
    // 1. Check for Exceptions (Day Off)
    $exception = $doctor->exceptions()->where('date', $date->toDateString())->first();
    if ($exception && $exception->type === 'off') {
        return []; // No slots
    }
    
    // 2. Determine Working Hours
    if ($exception && $exception->type === 'extra') {
        $start = Carbon::parse($date->toDateString() . ' ' . $exception->start_time);
        $end = Carbon::parse($date->toDateString() . ' ' . $exception->end_time);
    } else {
        // Standard Schedule
        $schedule = $doctor->schedules()->where('day_of_week', $dayName)->first();
        if (!$schedule) return [];
        
        $start = Carbon::parse($date->toDateString() . ' ' . $schedule->start_time);
        $end = Carbon::parse($date->toDateString() . ' ' . $schedule->end_time);
    }
    
    // 3. Generate Slots
    $slots = [];
    $duration = $doctor->schedules()->first()->avg_consultation_time ?? 15;
    
    while ($start->copy()->addMinutes($duration)->lte($end)) {
        $slotEnd = $start->copy()->addMinutes($duration);
        
        // 4. Check if slot is already booked (We will implement this in Module 7)
        // if (!$this->isBooked($doctor, $start, $slotEnd)) {
            $slots[] = [
                'start' => $start->format('H:i'),
                'end' => $slotEnd->format('H:i'),
                'available' => true
            ];
        // }
        
        $start->addMinutes($duration);
    }
    
    return $slots;
}
```

## Summary
This algorithm converts the abstract "Schedule" into concrete "Bookable Items".
