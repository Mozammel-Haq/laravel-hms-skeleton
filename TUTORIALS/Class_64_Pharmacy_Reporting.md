# Class 64: Pharmacy Reporting

## Introduction
Daily sales reports and profit analysis.

## 1. Daily Sales Report
Aggregate invoices by day.

```php
$sales = PharmacyInvoice::whereDate('created_at', today())
            ->with('items.medicine')
            ->get();

$totalCash = $sales->where('status', 'paid')->sum('total');
```

## 2. Profit Calculation
This is why we stored `purchase_price` in batches.
Profit = `(Selling Price - Purchase Price) * Qty`.

Since we use FIFO, calculating exact profit per sale is complex unless we stored `batch_id` in `invoice_items`.
*Refinement*: Modify `pharmacy_invoice_items` to store `cost_price` snapshot at time of sale.

```php
// Improved deduction logic in Class 61 should return the average cost or specific cost
// For simplicity in this tutorial, we use Medicine->purchase_price (Moving Average or Last Price).
```

## Summary
Reporting drives business decisions.

## Module 9 Completion
You have built a robust Pharmacy system with:
-   Multi-batch inventory (Expiry management).
-   FIFO stock deduction.
-   Point of Sale.
-   Automated Alerts.

Next: **Module 10: Laboratory & Diagnostics**.
