# Class 71: Billing Architecture

## Introduction
Billing is where all services (OPD, IPD, Lab, Pharmacy) meet. We need a central `Invoice` system that can handle charges from any source.

## 1. Polymorphic Design
Instead of separate columns for every service, we use polymorphism for line items.

## 2. The Invoice Model
This is the master record of debt.

```php
Schema::create('invoices', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained();
    $table->foreignId('patient_id')->constrained();
    $table->foreignId('user_id')->constrained(); // Creator
    
    $table->string('invoice_number')->unique(); // INV-2023-00001
    
    $table->decimal('subtotal', 15, 2);
    $table->decimal('discount_amount', 15, 2)->default(0);
    $table->decimal('tax_amount', 15, 2)->default(0);
    $table->decimal('total_amount', 15, 2);
    
    $table->decimal('paid_amount', 15, 2)->default(0);
    $table->decimal('due_amount', 15, 2);
    
    $table->string('status')->default('unpaid'); // unpaid, partial, paid, cancelled
    
    $table->timestamps();
});
```

## 3. The Invoice Item Model
This links to the source of the charge.

```php
Schema::create('invoice_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
    
    // Polymorphic relation to the service (LabRequest, PharmacyInvoice, Appointment, etc.)
    $table->nullableMorphs('billable'); 
    
    $table->string('description'); // e.g., "Consultation Fee", "CBC Test"
    $table->integer('quantity')->default(1);
    $table->decimal('unit_price', 15, 2);
    $table->decimal('total_price', 15, 2);
    
    $table->timestamps();
});
```

## 4. Models Setup
Create `Invoice` and `InvoiceItem`.
Add `items()` relationship to `Invoice`.
Add `invoiceItems()` morphMany to `Appointment`, `LabRequest`, etc.

## Summary
This centralizes revenue tracking. Whether it's a $5 bandage or a $5000 surgery, it's just an `InvoiceItem`.
