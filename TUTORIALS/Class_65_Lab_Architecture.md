# Class 65: Lab Architecture

## Introduction
The Laboratory module handles diagnostic tests. It needs to be flexible enough to handle simple blood tests (one result) and complex panels (lipid profile, CBC).

## 1. Models
We need categories and the test definitions themselves.

### A. Lab Test Category
Hematology, Biochemistry, Microbiology, etc.
```php
Schema::create('lab_test_categories', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained();
    $table->string('name');
    $table->timestamps();
});
```

### B. Lab Test
The specific test (e.g., "Complete Blood Count").
```php
Schema::create('lab_tests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained();
    $table->foreignId('lab_test_category_id')->constrained();
    
    $table->string('name');
    $table->string('code')->nullable();
    $table->decimal('price', 10, 2);
    
    $table->boolean('is_active')->default(true);
    
    $table->timestamps();
});
```

### C. Lab Test Parameter (The Result Fields)
A test like "CBC" has multiple parameters (Hemoglobin, RBC, WBC).
A test like "Blood Group" has one.

```php
Schema::create('lab_test_parameters', function (Blueprint $table) {
    $table->id();
    $table->foreignId('lab_test_id')->constrained()->onDelete('cascade');
    
    $table->string('name'); // e.g., "Hemoglobin"
    $table->string('unit')->nullable(); // e.g., "g/dL"
    $table->string('reference_range')->nullable(); // e.g., "13.5-17.5"
    
    $table->timestamps();
});
```

## 2. Models Setup
Create the models. `LabTest` has many `LabTestParameter`.

## Summary
This structure allows us to define the *shape* of the result before we even perform the test.
