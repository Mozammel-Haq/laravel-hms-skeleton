# Class 54: Admission Workflow

## Introduction
Admitting a patient involves selecting a patient, a doctor, and an available bed.

## 1. Controller
`AdmissionController`

```php
public function store(Request $request)
{
    $request->validate([
        'patient_id' => 'required',
        'doctor_id' => 'required',
        'bed_id' => 'required|exists:rooms,id', // Should verify bed is free
        'deposit' => 'numeric'
    ]);

    DB::transaction(function() use ($request) {
        // 1. Create Admission
        $admission = Admission::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'admission_date' => now(),
            'deposit_amount' => $request->deposit
        ]);
        
        // 2. Assign Bed
        BedAssignment::create([
            'admission_id' => $admission->id,
            'bed_id' => $request->bed_id,
            'assigned_at' => now()
        ]);
        
        // 3. Update Bed Status
        Room::where('id', $request->bed_id)->update(['status' => 'occupied']);
    });
    
    return redirect()->route('admissions.index');
}
```

## 2. Generating Admission Numbers
Use an Observer (like we did for Patients) to generate `ADM-2023-XXXX`.

## Summary
The admission process locks the resource (Bed) and starts the clock for billing.
