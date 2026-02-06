# Class 47: Appointment Booking Flow

## Introduction
The booking flow involves:
1.  Selecting a Department/Doctor.
2.  Selecting a Date.
3.  Choosing an available Slot.
4.  Confirming the Patient.

## 1. Controller
`AppointmentController`

```php
public function create()
{
    // Step 1: Just show the form to select Doctor/Date
    return view('appointments.create', [
        'doctors' => Doctor::with('user')->get()
    ]);
}

public function getSlots(Request $request, AppointmentService $service)
{
    // AJAX endpoint for Step 2
    $request->validate([
        'doctor_id' => 'required|exists:doctors,id',
        'date' => 'required|date|after_or_equal:today'
    ]);
    
    $doctor = Doctor::find($request->doctor_id);
    $slots = $service->generateSlots($doctor, Carbon::parse($request->date));
    
    return response()->json($slots);
}

public function store(Request $request)
{
    // Step 3: Final Save
    // Validation is complex (Double Booking Check) - see next class
    
    Appointment::create($request->all());
    return redirect()->route('appointments.index');
}
```

## 2. The View (Frontend Logic)
This usually requires JavaScript (Vue/React/Alpine).
-   User changes Date -> Fetch Slots via AJAX -> Render Buttons.
-   User clicks "09:00" -> Fills hidden input `start_time`.

## Summary
The "Wizard" style flow ensures we only capture valid data.
