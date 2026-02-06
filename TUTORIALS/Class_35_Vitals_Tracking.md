# Class 35: Vitals Tracking

## Introduction
Every time a patient visits, a nurse takes their vitals (BP, Weight, Temp). Tracking this over time allows us to generate charts.

## 1. Migration
```bash
php artisan make:model PatientVital -m
```

```php
Schema::create('patient_vitals', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained(); // Vitals belong to the visit context
    $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
    $table->unsignedBigInteger('performed_by'); // User ID (Nurse)
    
    // Measurements
    $table->integer('systolic_bp')->nullable(); // 120
    $table->integer('diastolic_bp')->nullable(); // 80
    $table->decimal('heart_rate', 5, 2)->nullable();
    $table->decimal('temperature', 5, 2)->nullable(); // Celsius or F
    $table->decimal('weight_kg', 5, 2)->nullable();
    $table->decimal('height_cm', 5, 2)->nullable();
    $table->decimal('bmi', 5, 2)->nullable();
    $table->decimal('spo2', 5, 2)->nullable(); // Oxygen saturation
    
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

## 2. BMI Calculation Logic
We should auto-calculate BMI when saving.

In `PatientVital` model:

```php
protected static function boot()
{
    parent::boot();
    
    static::saving(function ($vital) {
        if ($vital->weight_kg && $vital->height_cm) {
            $heightM = $vital->height_cm / 100;
            $vital->bmi = $vital->weight_kg / ($heightM * $heightM);
        }
    });
}
```

## 3. Chart Data API
To show a graph on the frontend, we need an endpoint.

```php
// PatientController
public function getVitalsHistory(Patient $patient)
{
    return $patient->vitals()
                   ->orderBy('created_at', 'asc')
                   ->get(['created_at', 'systolic_bp', 'diastolic_bp', 'weight_kg']);
}
```

## Summary
Vitals are the first "Time-Series" data we have encountered. Storing them efficiently allows for longitudinal patient care.
