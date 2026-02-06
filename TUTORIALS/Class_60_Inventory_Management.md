# Class 60: Inventory Management (Batches)

## Introduction
Medicines expire. We cannot just have a single `stock_quantity` column in the `medicines` table. We need a `medicine_batches` table to track expiry dates and specific costs.

## 1. Migration: Medicine Batches
```php
Schema::create('medicine_batches', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained();
    $table->foreignId('medicine_id')->constrained();
    $table->foreignId('supplier_id')->nullable()->constrained(); // Source of this batch
    
    $table->string('batch_number');
    $table->date('expiry_date');
    $table->date('manufacture_date')->nullable();
    
    $table->integer('quantity'); // Current remaining stock
    $table->decimal('purchase_price', 10, 2); // Cost for this specific batch
    
    $table->timestamps();
});
```

## 2. Purchasing Logic (Stock In)
When we buy medicine, we create a batch.

```php
// PharmacyService.php
public function purchaseStock(Medicine $medicine, array $data)
{
    return MedicineBatch::create([
        'medicine_id' => $medicine->id,
        'supplier_id' => $data['supplier_id'],
        'batch_number' => $data['batch_number'],
        'expiry_date' => $data['expiry_date'],
        'quantity' => $data['quantity'],
        'purchase_price' => $data['purchase_price'],
    ]);
}
```

## 3. Total Stock Accessor
In `Medicine` model:

```php
public function batches()
{
    return $this->hasMany(MedicineBatch::class);
}

public function getStockAttribute()
{
    // Sum of all non-expired, positive quantity batches
    return $this->batches()
                ->where('expiry_date', '>', now())
                ->where('quantity', '>', 0)
                ->sum('quantity');
}
```

## Summary
We now track *which* box of medicine we have, and when it expires.
