# Class 45: Testing Scheduling Logic

## Introduction
Scheduling bugs are critical. If a patient books a slot that doesn't exist, it causes real-world conflict. We must test this rigorously.

## 1. Unit Test
`tests/Unit/AppointmentServiceTest.php`

```php
public function test_generates_slots_for_standard_schedule()
{
    // 1. Setup
    $doctor = Doctor::factory()->create();
    $doctor->schedules()->create([
        'day_of_week' => 'monday',
        'start_time' => '09:00',
        'end_time' => '10:00',
        'avg_consultation_time' => 30
    ]);
    
    $service = new AppointmentService();
    $monday = Carbon::parse('next monday');
    
    // 2. Act
    $slots = $service->generateSlots($doctor, $monday);
    
    // 3. Assert
    $this->assertCount(2, $slots); // 9:00-9:30, 9:30-10:00
    $this->assertEquals('09:00', $slots[0]['start']);
}

public function test_exception_overrides_schedule()
{
    // Setup Monday Schedule
    // Add Exception "Off" for next Monday
    
    // Act
    $slots = $service->generateSlots($doctor, $monday);
    
    // Assert
    $this->assertEmpty($slots);
}
```

## Summary
We have built a reliable engine for generating time slots.

## Module 6 Completion
Congratulations! You have completed Module 6. You have built:
-   **Weekly Schedules**: Recurring availability.
-   **Exceptions**: Handling holidays and leave.
-   **Slot Generation**: The core algorithm for booking.

In Module 7, we will build the **Clinical Operations (OPD)** system, allowing patients to actually book these slots.
