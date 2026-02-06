# Class 16: RBAC Seeding

## Introduction
An RBAC system is useless without data. We need to define what roles exist and what they can do.

## 1. Creating the Seeder
```bash
php artisan make:seeder RolePermissionSeeder
```

## 2. Defining the Hierarchy
Open `database/seeders/RolePermissionSeeder.php`.

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Define Permissions
        $permissions = [
            'view_dashboard',
            'manage_users',
            'manage_clinic',
            'view_patients',
            'create_patient',
            'edit_patient',
            'view_appointments',
            'create_appointment',
            'manage_settings',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // 2. Define Roles and Assign Permissions
        
        // Super Admin (Has everything)
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'label' => 'Super Admin']);
        $superAdmin->permissions()->sync(Permission::all());

        // Doctor
        $doctor = Role::firstOrCreate(['name' => 'doctor', 'label' => 'Doctor']);
        $doctor->permissions()->sync(Permission::whereIn('name', [
            'view_dashboard',
            'view_patients',
            'create_patient', // Doctors can create patients? Maybe.
            'edit_patient',
            'view_appointments',
        ])->pluck('id'));

        // Receptionist
        $receptionist = Role::firstOrCreate(['name' => 'receptionist', 'label' => 'Receptionist']);
        $receptionist->permissions()->sync(Permission::whereIn('name', [
            'view_dashboard',
            'view_patients',
            'create_patient',
            'edit_patient',
            'view_appointments',
            'create_appointment',
        ])->pluck('id'));

        // Patient
        $patient = Role::firstOrCreate(['name' => 'patient', 'label' => 'Patient']);
        // Patients have very limited permissions, usually just viewing their own data.
        // We might not use permissions for them, but rather Policy logic.
    }
}
```

## 3. Running the Seeder
Don't forget to call this in `DatabaseSeeder.php`.

```php
public function run(): void
{
    $this->call([
        RolePermissionSeeder::class,
    ]);
}
```

## Summary
We have bootstrapped our application with the fundamental roles needed for a Hospital Management System.

In the next class, we will look at how to verify these roles in our Controllers using Policies.
