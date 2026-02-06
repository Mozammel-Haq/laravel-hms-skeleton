# Class 51: Diagnosis & Notes

## Introduction
Standardizing diagnosis codes (ICD-10) is important for insurance and reporting.

## 1. Migration (Optional)
`icd_codes` table (Seeded with standard data).
```php
Schema::create('icd_codes', function ($table) {
    $table->id();
    $table->string('code')->unique(); // A00
    $table->string('name'); // Cholera
});
```

## 2. Integration
In `consultations` table, we can store `icd_code` or a JSON array `icd_codes` (if multiple).

## 3. UI
An autocomplete dropdown for the doctor to search "Flu" and select "J11 - Influenza".

## Summary
Structured diagnosis data enables "Public Health" reporting (e.g., detecting a Dengue outbreak).
