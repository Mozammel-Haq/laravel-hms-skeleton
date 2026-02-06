# Class 61: Pharmacy Service Layer (FIFO)

## Introduction
When selling medicine, we should sell the batch that expires soonest or arrived first (FIFO/FEFO).

## 1. The Strategy
**FEFO (First Expired, First Out)** is best for pharmacy.

## 2. Implementation
In `PharmacyService`:

```php
public function deductStock(Medicine $medicine, int $quantity)
{
    // 1. Get valid batches ordered by expiry date ascending
    $batches = $medicine->batches()
        ->where('quantity', '>', 0)
        ->where('expiry_date', '>=', now())
        ->orderBy('expiry_date', 'asc')
        ->get();

    $remainingToDeduct = $quantity;

    if ($batches->sum('quantity') < $quantity) {
        throw new Exception("Insufficient stock for {$medicine->name}");
    }

    DB::transaction(function() use ($batches, $remainingToDeduct) {
        foreach ($batches as $batch) {
            if ($remainingToDeduct <= 0) break;

            if ($batch->quantity >= $remainingToDeduct) {
                // Batch has enough
                $batch->decrement('quantity', $remainingToDeduct);
                $remainingToDeduct = 0;
            } else {
                // Batch doesn't have enough, take all and move to next
                $take = $batch->quantity;
                $batch->update(['quantity' => 0]);
                $remainingToDeduct -= $take;
            }
        }
    });
}
```

## Summary
This logic ensures we reduce stock from specific batches automatically, without the cashier needing to select "Batch #123" manually (though manual override is a feature for advanced systems).
