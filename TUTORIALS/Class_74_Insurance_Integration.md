# Class 74: Insurance Integration

## Introduction
Many patients don't pay cash; their insurance company pays later.

## 1. Models
`InsuranceProvider`.

```php
Schema::create('insurance_providers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained();
    $table->string('name'); // e.g., "Blue Cross"
    $table->string('contact_number')->nullable();
    $table->timestamps();
});
```

Add `insurance_provider_id` and `policy_number` to `patients` table.

## 2. Claim Logic
When creating an Invoice, if the patient is insured:
1.  Total Bill: $1000.
2.  Coverage: 80% (Logic varies wildly).
3.  Patient Pay: $200.
4.  Insurance Claim: $800.

We represent this as two "Payments" or a specific "Claim" model.
For simplicity, we treat "Insurance" as a Payment Method in Class 73, but we mark the payment as "Pending Clearance".

## Summary
Insurance is complex. In this tutorial, we treat it as a payment method that requires manual reconciliation later.
