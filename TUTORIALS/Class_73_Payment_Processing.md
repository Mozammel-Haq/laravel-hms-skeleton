# Class 73: Payment Processing

## Introduction
An invoice can be paid in chunks (deposits) or all at once.

## 1. Payment Model
```php
Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained();
    $table->foreignId('invoice_id')->constrained();
    
    $table->decimal('amount', 15, 2);
    $table->string('method'); // cash, card, insurance
    $table->string('transaction_reference')->nullable();
    
    $table->text('notes')->nullable();
    $table->timestamp('paid_at');
    
    $table->timestamps();
});
```

## 2. Observer Logic
When a `Payment` is created, update the `Invoice`.

```php
// PaymentObserver.php
public function created(Payment $payment)
{
    $invoice = $payment->invoice;
    $invoice->increment('paid_amount', $payment->amount);
    $invoice->decrement('due_amount', $payment->amount);
    
    if ($invoice->due_amount <= 0) {
        $invoice->update(['status' => 'paid']);
    } else {
        $invoice->update(['status' => 'partial']);
    }
}
```

## 3. Controller
Simple form to accept payment against an Invoice ID.

## Summary
Separating `Invoices` (Debt) from `Payments` (Cash) is Accounting 101. It allows for partial payments and proper ledger tracking.
