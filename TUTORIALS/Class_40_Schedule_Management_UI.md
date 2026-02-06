# Class 40: Schedule Management UI

## Introduction
Doctors need a way to set their weekly hours. This is usually a "Settings" page in their dashboard.

## 1. The Controller
```bash
php artisan make:controller DoctorScheduleController
```

```php
public function edit()
{
    $doctor = auth()->user()->doctor;
    $schedules = $doctor->schedules;
    
    // Structure for view: ['monday' => [start, end], 'tuesday' => ...]
    return view('doctor.schedule.edit', compact('schedules'));
}

public function update(Request $request)
{
    $doctor = auth()->user()->doctor;
    
    // Input format: schedules[monday][start], schedules[monday][end]
    
    // Transactional update
    DB::transaction(function() use ($request, $doctor) {
        // Delete old
        $doctor->schedules()->delete();
        
        // Insert new
        foreach ($request->schedules as $day => $times) {
            if ($times['start'] && $times['end']) {
                $doctor->schedules()->create([
                    'day_of_week' => $day,
                    'start_time' => $times['start'],
                    'end_time' => $times['end'],
                    'avg_consultation_time' => $request->avg_time ?? 15
                ]);
            }
        }
    });
    
    return back()->with('success', 'Schedule updated.');
}
```

## 2. The View
`resources/views/doctor/schedule/edit.blade.php`

```html
<form action="{{ route('doctor.schedule.update') }}" method="POST">
    @csrf
    
    @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
        <div class="row mb-2">
            <div class="col-md-2">{{ ucfirst($day) }}</div>
            <div class="col-md-4">
                <input type="time" name="schedules[{{ $day }}][start]" class="form-control">
            </div>
            <div class="col-md-4">
                <input type="time" name="schedules[{{ $day }}][end]" class="form-control">
            </div>
        </div>
    @endforeach
    
    <button type="submit">Save Schedule</button>
</form>
```

## Summary
A simple matrix form allows doctors to configure their recurring availability.
