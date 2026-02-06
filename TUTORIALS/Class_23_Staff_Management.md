# Class 23: Staff Management

## Introduction
Not everyone is a Doctor. We have Nurses, Pharmacists, Accountants, and Receptionists. In our system, these are primarily `Users` with specific `Roles`. However, sometimes we need to store HR data (Join Date, Salary, Address) which doesn't fit in the `users` table.

## 1. The Strategy
We will create a `Staff` model.
-   **User**: Authentication, Roles, Email.
-   **Staff**: HR details, Physical Address, Employment Status.

## 2. Migration
```bash
php artisan make:model Staff -m
```

```php
Schema::create('staff', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('clinic_id');
    $table->unsignedBigInteger('user_id')->unique(); // One user = One staff profile per clinic? 
    // Wait, if a User is in multiple clinics, they need multiple staff profiles. 
    // But our User model currently has a single 'clinic_id'. 
    // So for now, 1 User = 1 Clinic.
    
    $table->string('employee_id')->nullable(); // "EMP-001"
    $table->date('joining_date')->nullable();
    $table->string('designation')->nullable(); // "Senior Nurse"
    $table->text('address')->nullable();
    $table->timestamps();
    
    $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
```

## 3. Controller Logic
When creating a staff member, we must create a **User** first, then the **Staff** record.

```php
DB::transaction(function() use ($request) {
    // 1. Create User
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make('password'), // Default password
        'clinic_id' => TenantContext::getClinicId(),
        'status' => 'active',
    ]);
    
    // 2. Assign Role
    $role = Role::where('name', $request->role)->first();
    $user->roles()->attach($role);
    
    // 3. Create Staff Profile
    Staff::create([
        'user_id' => $user->id,
        'employee_id' => $request->employee_id,
        'joining_date' => $request->joining_date,
        'designation' => $request->designation,
    ]);
});
```

## Summary
Separating `User` (Auth) from `Staff` (HR) is a clean architectural pattern that keeps our authentication table lightweight.
