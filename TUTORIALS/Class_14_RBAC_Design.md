# Class 14: Role-Based Access Control (RBAC) Design

## Introduction
For a Hospital Management System, simple "User" and "Admin" roles are not enough. We need fine-grained control.
-   **Receptionist**: Can book appointments, but cannot see medical records.
-   **Doctor**: Can see medical records, but cannot see financial reports.
-   **Accountant**: Can see finances, but not patient details.

We will implement a custom RBAC system similar to the popular `spatie/laravel-permission` package, but simplified for our learning.

## 1. The Database Schema
We need 4 tables:
1.  `users` (Already exists)
2.  `roles` (e.g., 'doctor', 'receptionist')
3.  `permissions` (e.g., 'view_patients', 'create_appointment')
4.  `role_permission` (Pivot: Role X has Permission Y)
5.  `user_role` (Pivot: User A has Role X)

## 2. Creating the Migrations
Run:
```bash
php artisan make:model Role -m
php artisan make:model Permission -m
```
We don't need models for the pivots usually, but we need migrations.

### Roles Table Migration
```php
Schema::create('roles', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique(); // e.g., 'doctor'
    $table->string('label')->nullable(); // e.g., 'Medical Doctor'
    $table->timestamps();
});
```

### Permissions Table Migration
```php
Schema::create('permissions', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique(); // e.g., 'view_medical_records'
    $table->string('label')->nullable();
    $table->timestamps();
});
```

### Pivot Tables (Create a new migration `create_rbac_pivot_tables`)
```bash
php artisan make:migration create_rbac_pivot_tables
```

```php
public function up(): void
{
    Schema::create('role_permission', function (Blueprint $table) {
        $table->id();
        $table->foreignId('role_id')->constrained()->onDelete('cascade');
        $table->foreignId('permission_id')->constrained()->onDelete('cascade');
        // Prevent duplicate assignments
        $table->unique(['role_id', 'permission_id']);
    });

    Schema::create('user_role', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('role_id')->constrained()->onDelete('cascade');
        $table->unique(['user_id', 'role_id']);
    });
}
```

## 3. Why Custom?
Why not use a package?
1.  **Learning**: You need to understand how these relationships work.
2.  **Performance**: We can optimize it for our Multi-Tenant needs (e.g., a user might be a Doctor in Clinic A but a Patient in Clinic B - though our current design simplifies this to one role per user per session).

## Summary
We have designed the database structure for a flexible permission system.

In the next class, we will implement the Models and Relationships.
