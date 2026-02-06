# Class 59: Pharmacy Architecture

## Introduction
The Pharmacy module handles medicine inventory, purchasing, and sales. It's a critical financial component.

## 1. Core Models
We need several models to track stock effectively.

### A. Medicine Category
Tablets, Syrups, Injections, etc.
```php
Schema::create('medicine_categories', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained();
    $table->string('name');
    $table->text('description')->nullable();
    $table->timestamps();
});
```

### B. Supplier
Who we buy from.
```php
Schema::create('suppliers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained();
    $table->string('name');
    $table->string('contact_person')->nullable();
    $table->string('phone');
    $table->string('email')->nullable();
    $table->text('address')->nullable();
    $table->timestamps();
});
```

### C. Medicine
The product definition.
```php
Schema::create('medicines', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained();
    $table->foreignId('medicine_category_id')->constrained();
    $table->foreignId('supplier_id')->nullable()->constrained(); // Default supplier
    
    $table->string('name');
    $table->string('generic_name')->nullable();
    $table->string('sku')->nullable(); // Barcode
    $table->string('unit'); // box, strip, bottle
    
    $table->decimal('purchase_price', 10, 2);
    $table->decimal('selling_price', 10, 2);
    
    $table->integer('reorder_level')->default(10);
    
    $table->timestamps();
});
```

## 2. Models Setup
Create `MedicineCategory`, `Supplier`, and `Medicine` models. All should extend `BaseTenantModel`.

## Summary
These three tables form the static data layer. The dynamic layer (stock) comes next.
