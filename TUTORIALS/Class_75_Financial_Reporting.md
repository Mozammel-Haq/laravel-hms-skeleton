# Class 75: Financial Reporting

## Introduction
The clinic owner needs to know: "How much did we make today?"

## 1. Daily Collection Report
Sum of `payments` table where `paid_at` is today.

```php
$dailyCash = Payment::whereDate('paid_at', today())
    ->where('method', 'cash')
    ->sum('amount');
    
$dailyCard = Payment::whereDate('paid_at', today())
    ->where('method', 'card')
    ->sum('amount');
```

## 2. Revenue by Department
Sum of `invoice_items` grouped by `billable_type`.

```php
$revenue = InvoiceItem::select('billable_type', DB::raw('sum(total_price) as total'))
    ->groupBy('billable_type')
    ->get();
    
// Map billable_type to readable names
// App\Models\LabRequest -> "Laboratory"
// App\Models\Admission -> "In-Patient"
```

## 3. Outstanding Due
Sum of `due_amount` from all unpaid invoices.

## Summary
These queries give the financial health of the clinic.

## Module 11 Completion
You have built the financial backbone of the HMS.

Next: **Module 12: Advanced Features & Deployment**.
