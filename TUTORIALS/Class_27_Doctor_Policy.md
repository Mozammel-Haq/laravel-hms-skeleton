# Class 27: Doctor Policy

## Introduction
We need to secure the Doctor management. Not everyone should be able to create or edit doctors.

## 1. Creating the Policy
```bash
php artisan make:policy DoctorPolicy --model=Doctor
```

## 2. Implementing Logic
Open `app/Policies/DoctorPolicy.php`.

```php
public function viewAny(User $user)
{
    // Anyone logged in can see the list of doctors?
    // Or maybe only Staff?
    return $user->hasPermission('view_doctors');
}

public function view(User $user, Doctor $doctor)
{
    // Public profile access vs Admin detail access
    return $user->hasPermission('view_doctors');
}

public function create(User $user)
{
    return $user->hasPermission('manage_doctors');
}

public function update(User $user, Doctor $doctor)
{
    // Admin can update anyone
    if ($user->hasPermission('manage_doctors')) {
        return true;
    }
    
    // Doctor can update their OWN profile
    return $user->id === $doctor->user_id;
}
```

## 3. Registering (if needed) and Using
In `DoctorController`:

```php
public function edit(Doctor $doctor)
{
    $this->authorize('update', $doctor);
    return view('doctors.edit', compact('doctor'));
}
```

## Summary
We added a layer of security. Crucially, we allowed doctors to edit *themselves* while preventing them from editing *others*.
