# Class 56: Doctor Rounds

## Introduction
In IPD, doctors visit patients daily ("Rounds") and write notes.

## 1. Migration
`InpatientRound` (or just `Round`).

```php
Schema::create('inpatient_rounds', function (Blueprint $table) {
    $table->id();
    $table->foreignId('admission_id')->constrained()->cascadeOnDelete();
    $table->foreignId('doctor_id')->constrained();
    
    $table->dateTime('visited_at');
    $table->text('notes'); // "Patient recovering well"
    $table->text('instructions'); // "Continue antibiotics"
    
    $table->timestamps();
});
```

## 2. Controller
Simple CRUD.
`RoundController`.

## Summary
These records form the core of the inpatient medical history.
