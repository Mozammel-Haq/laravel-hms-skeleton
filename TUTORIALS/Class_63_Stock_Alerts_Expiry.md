# Class 63: Stock Alerts & Expiry

## Introduction
We need to know what to reorder and what to throw away.

## 1. Low Stock Alert
Simple query based on `reorder_level`.

```php
// Medicine Model Scope
public function scopeLowStock($query)
{
    // This is tricky because stock is calculated from batches.
    // Efficient way: use withSum or subquery.
    return $query->withSum(['batches' => function($q) {
        $q->where('expiry_date', '>', now());
    }], 'quantity')
    ->having('batches_sum_quantity', '<=', DB::raw('reorder_level'));
}
```
*Note: `having` works with aggregates. Depending on DB strict mode, might need full group by.*

## 2. Expiry Alert
Batches expiring in next 30 days.

```php
// MedicineBatch Model Scope
public function scopeExpiringSoon($query, $days = 30)
{
    return $query->where('expiry_date', '>', now())
                 ->where('expiry_date', '<=', now()->addDays($days))
                 ->where('quantity', '>', 0);
}
```

## 3. Command
`php artisan pharmacy:check-expiry`.
Send email to admin if batches are expiring.

## Summary
Proactive alerts prevent lost revenue (out of stock) and safety hazards (expired meds).
