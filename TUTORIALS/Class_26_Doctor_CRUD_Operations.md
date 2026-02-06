# Class 26: Doctor CRUD Operations

## Introduction
Managing doctors involves complex logic: creating a User, assigning a Role, creating the Doctor profile, and linking it all together.

## 1. The Controller
Run: `php artisan make:controller DoctorController --resource`

## 2. The Store Method
This is the most critical part. We use a Database Transaction to ensure atomicity.

```php
public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'specialization' => 'required',
        'department_id' => 'required|exists:departments,id',
        // ...
    ]);

    DB::transaction(function () use ($request) {
        // 1. Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'), // Default
            'clinic_id' => TenantContext::getClinicId(),
            'status' => 'active',
        ]);
        
        // 2. Assign Role
        $role = Role::where('name', 'doctor')->first();
        $user->roles()->attach($role);

        // 3. Create Doctor Profile
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'clinic_id' => TenantContext::getClinicId(),
            'department_id' => $request->department_id,
            'specialization' => $request->specialization,
            'license_number' => $request->license_number,
            'consultation_fee' => $request->consultation_fee,
            'bio' => $request->bio,
        ]);

        // 4. Handle Education (Optional)
        if ($request->has('education')) {
            // Loop and create...
        }
    });

    return redirect()->route('doctors.index')->with('success', 'Doctor added successfully.');
}
```

## 3. The Index Method
```php
public function index()
{
    // Eager load relationships to prevent N+1 problem
    $doctors = Doctor::with(['user', 'department'])->paginate(10);
    return view('doctors.index', compact('doctors'));
}
```

## Summary
We have implemented a robust creation flow. The separation of User and Doctor data is handled seamlessly by the Controller.
