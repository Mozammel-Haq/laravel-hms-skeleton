# Class 53: IPD Architecture

## Introduction
The In-Patient Department (IPD) handles patients who stay overnight. This adds complexity: Bed management, daily charges, and 24/7 care.

## 1. Core Models
We need:
-   `Admission`: Represents the patient's stay.
-   `BedAssignment`: Tracks which bed they occupied and when (patients can move beds during a stay).

## 2. Migrations
`php artisan make:model Admission -m`
`php artisan make:model BedAssignment -m`

### Admission Table
```php
Schema::create('admissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained();
    $table->foreignId('patient_id')->constrained();
    $table->foreignId('doctor_id')->constrained(); // Attending doctor
    
    $table->string('admission_number')->unique(); // ADM-2023-001
    $table->dateTime('admission_date');
    $table->dateTime('discharge_date')->nullable();
    
    $table->string('status')->default('admitted'); // admitted, discharged
    $table->text('reason')->nullable(); // "Severe Dengue"
    $table->decimal('deposit_amount', 10, 2)->default(0);
    
    $table->timestamps();
});
```

### Bed Assignment Table
```php
Schema::create('bed_assignments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('admission_id')->constrained()->cascadeOnDelete();
    $table->foreignId('bed_id')->constrained('rooms'); // Assuming 'rooms' table holds beds
    
    $table->dateTime('assigned_at');
    $table->dateTime('released_at')->nullable(); // Null = currently occupying
    
    $table->timestamps();
});
```

## Summary
The one-to-many relationship between `Admission` and `BedAssignment` allows us to track history: "Patient started in ICU (Bed 1) for 2 days, then moved to General Ward (Bed 10) for 3 days".
