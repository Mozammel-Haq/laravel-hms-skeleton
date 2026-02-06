# Class 49: Consultation Model

## Introduction
The `Appointment` is the *scheduling* entity.
The `Consultation` is the *clinical* entity.
When the doctor sees the patient, they create a Consultation record.

## 1. Migration
`php artisan make:model Consultation -m`

```php
Schema::create('consultations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('appointment_id')->constrained()->unique(); // 1-to-1 with Appointment
    // Denormalize for easier querying
    $table->foreignId('doctor_id')->constrained();
    $table->foreignId('patient_id')->constrained();
    $table->foreignId('clinic_id')->constrained();
    
    // Clinical Data
    $table->text('symptoms')->nullable(); // "Cough, Fever"
    $table->text('diagnosis')->nullable(); // "Viral Flu"
    $table->text('notes')->nullable(); // Private doctor notes
    
    $table->timestamps();
});
```

## 2. Model
`app/Models/Consultation.php`

```php
public function appointment() { return $this->belongsTo(Appointment::class); }
public function prescription() { return $this->hasOne(Prescription::class); }
```

## 3. Workflow
1.  Doctor clicks "Start Visit" on Appointment.
2.  System creates `Consultation`.
3.  Appointment status -> `completed`.

## Summary
Separating Appointment (Logistics) from Consultation (Medical) allows us to have appointments that don't result in a consultation (e.g., No Show) and keeps medical data secure.
