# Class 33: Patient Registration Workflow

## Introduction
Now that we have the model, we need the UI and Controller to handle the registration process. This is one of the most used forms in the hospital.

## 1. The Controller
Run: `php artisan make:controller PatientController --resource`

```php
public function create()
{
    return view('patients.create');
}

public function store(Request $request, PatientService $patientService)
{
    $validated = $request->validate([
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'phone' => 'required|string', // Unique validation is tricky with global scope
        'dob' => 'required|date',
        'gender' => 'required|in:male,female,other',
        // ...
    ]);

    // Use the Service we designed in Class 32
    $clinicId = TenantContext::getClinicId();
    $patient = $patientService->findOrRegister($validated, $clinicId);

    return redirect()->route('patients.show', $patient->id)
                     ->with('success', 'Patient registered successfully.');
}
```

## 2. The View
`resources/views/patients/create.blade.php`.
Use a standard grid layout.

```html
<form action="{{ route('patients.store') }}" method="POST">
    @csrf
    <div class="grid grid-cols-2 gap-4">
        <!-- Name -->
        <div>
            <label>First Name</label>
            <input type="text" name="first_name" required class="form-input">
        </div>
        <div>
            <label>Last Name</label>
            <input type="text" name="last_name" required class="form-input">
        </div>

        <!-- Demographics -->
        <div>
            <label>Date of Birth</label>
            <input type="date" name="dob" required class="form-input">
        </div>
        <div>
            <label>Gender</label>
            <select name="gender" class="form-select">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>

        <!-- Contact -->
        <div class="col-span-2">
            <label>Phone Number</label>
            <input type="text" name="phone" required class="form-input">
            <p class="text-sm text-gray-500">Used for patient lookup.</p>
        </div>
    </div>
    
    <button type="submit" class="btn-primary mt-4">Register Patient</button>
</form>
```

## 3. Duplicate Prevention via AJAX
In a real-world app, as soon as the receptionist types the phone number, we should check if the patient exists.

Route:
```php
Route::get('/api/patients/check-phone', [PatientController::class, 'checkPhone']);
```

Controller:
```php
public function checkPhone(Request $request)
{
    // Search Globally
    $patient = Patient::withoutGlobalScope('clinic_access')
                      ->where('phone', $request->phone)
                      ->first();
                      
    if ($patient) {
        return response()->json([
            'exists' => true,
            'name' => $patient->name,
            'id' => $patient->id
        ]);
    }
    return response()->json(['exists' => false]);
}
```

## Summary
The registration flow handles the complexity of "Create or Link" seamlessly, ensuring data integrity while keeping the UI simple.
