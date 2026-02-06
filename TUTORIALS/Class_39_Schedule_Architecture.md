# Class 39: Schedule Architecture

## Introduction
Before a patient can book an appointment, we must know when a doctor is available. This is more complex than "9-5". Doctors have shifts, days off, and different hours on different days.

## 1. The Strategy
We will use a **Weekly Template** approach.
-   **DoctorSchedule**: Represents a recurring weekly slot (e.g., "Mondays, 09:00 - 12:00").
-   **DoctorScheduleException**: Represents one-off changes (e.g., "Leave on Dec 25th" or "Extra shift on Jan 1st").

## 2. Migrations
```bash
php artisan make:model DoctorSchedule -m
php artisan make:model DoctorScheduleException -m
```

### DoctorSchedule Table
```php
Schema::create('doctor_schedules', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
    $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
    
    $table->string('day_of_week'); // "monday", "tuesday", etc.
    $table->time('start_time');
    $table->time('end_time');
    $table->integer('avg_consultation_time')->default(15); // minutes
    
    $table->timestamps();
});
```

### DoctorScheduleException Table
```php
Schema::create('doctor_schedule_exceptions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained();
    $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
    
    $table->date('date');
    $table->string('type'); // "off", "extra"
    $table->time('start_time')->nullable(); // Only if type=extra
    $table->time('end_time')->nullable();
    $table->text('reason')->nullable();
    
    $table->timestamps();
});
```

## 3. The Models
Both extend `BaseTenantModel`.

In `Doctor.php`:
```php
public function schedules()
{
    return $this->hasMany(DoctorSchedule::class);
}

public function exceptions()
{
    return $this->hasMany(DoctorScheduleException::class);
}
```

## Summary
This data structure allows us to answer the question: "Is Dr. Smith available on Tuesday, Oct 24th at 10:00 AM?"
-   Step 1: Check `exceptions` for that date.
-   Step 2: If no exception, check `schedules` for "Tuesday".
