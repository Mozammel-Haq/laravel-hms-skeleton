# Class 50: Prescription System

## Introduction
The output of a consultation is often a Prescription.

## 1. Migrations
`Prescription` and `PrescriptionItem`.

```php
Schema::create('prescriptions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('consultation_id')->constrained();
    $table->foreignId('clinic_id')->constrained();
    $table->string('code')->unique(); // RX-2023-001
    $table->text('notes')->nullable();
    $table->timestamps();
});

Schema::create('prescription_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
    $table->foreignId('medicine_id')->nullable(); // Link to Pharmacy Inventory (Module 9)
    $table->string('medicine_name'); // Fallback if not in inventory
    
    $table->string('dosage'); // "10mg"
    $table->string('frequency'); // "1-0-1" (Morning, Noon, Night)
    $table->string('duration'); // "5 days"
    $table->text('instruction')->nullable(); // "After food"
    
    $table->timestamps();
});
```

## 2. Controller
`PrescriptionController`.

When saving:
```php
$prescription = $consultation->prescription()->create([...]);

foreach ($request->items as $item) {
    $prescription->items()->create($item);
}
```

## Summary
The digital prescription eliminates bad handwriting errors and integrates directly with the Pharmacy module.
