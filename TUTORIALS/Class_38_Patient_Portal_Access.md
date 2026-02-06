# Class 38: Patient Portal Access

## Introduction
Modern healthcare requires patients to have access to their own data. We need to allow `Patients` to log in as `Users`.

## 1. The Strategy
We already have a `User` model.
We have a `Patient` model.
We need to link them. `Patient belongsTo User`.

## 2. Migration Update
Add `user_id` to `patients` table.
```bash
php artisan make:migration add_user_id_to_patients_table
```

```php
Schema::table('patients', function (Blueprint $table) {
    $table->unsignedBigInteger('user_id')->nullable()->after('id');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
});
```

## 3. Creating Access
When a patient is registered, they don't automatically get a login. We can have a button "Enable Portal Access".

```php
// PatientController
public function enablePortal(Patient $patient)
{
    if ($patient->user_id) {
        return back()->with('error', 'User already exists.');
    }

    // Create User
    $user = User::create([
        'name' => $patient->name,
        'email' => $patient->email, // Must have email
        'password' => Hash::make($patient->phone), // Default password is phone
        'status' => 'active',
        // No clinic_id because patient is global user? 
        // Or clinic_id of the registering clinic? 
        // Let's keep it null for global patient user.
    ]);
    
    // Assign Role
    $user->assignRole('patient');

    // Link
    $patient->update(['user_id' => $user->id]);
    
    // Send Welcome Email
    // Mail::to($user)->send(new WelcomePatient($user));

    return back()->with('success', 'Portal access enabled.');
}
```

## 4. Patient Dashboard
In `DashboardController` (Class 29), we had:
```php
if ($user->hasRole('patient')) {
    // Find the patient record linked to this user
    $patient = Patient::where('user_id', $user->id)->first();
    return view('dashboards.patient', compact('patient'));
}
```

## Summary
We have closed the loop. Patients are now active participants in the system, able to view the vitals and history we created in previous classes.

## Module 5 Completion
Congratulations! You have completed Module 5. You have built a sophisticated Patient Management System that handles:
-   Complex Demographics
-   Global vs Local Scope
-   Medical History
-   Vitals Tracking
-   Patient Portal

In Module 6, we will build the **Doctor Scheduling System**.
