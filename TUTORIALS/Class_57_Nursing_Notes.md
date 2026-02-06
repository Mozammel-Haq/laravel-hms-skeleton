# Class 57: Nursing Notes

## Introduction
Nurses monitor patients 24/7. They record vitals, fluid intake/output, and medication administration.

## 1. Migration
`NursingNote`.

```php
Schema::create('nursing_notes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('admission_id')->constrained();
    $table->foreignId('user_id')->constrained(); // The Nurse
    
    $table->text('note');
    $table->boolean('medication_given')->default(false);
    
    $table->timestamps();
});
```

## 2. Integration with Vitals
We can reuse the `PatientVital` model (Class 35), just linking it to the Admission context if needed (via timestamp or explicit column).

## Summary
Nursing notes provide the minute-by-minute detail of patient care.
