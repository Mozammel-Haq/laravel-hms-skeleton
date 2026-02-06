# Class 62: Point Of Sale (POS)

## Introduction
The POS interface allows pharmacists to sell medicines to walk-in patients or admitted patients.

## 1. Invoice Model
We need to record the sale.

```php
Schema::create('pharmacy_invoices', function (Blueprint $table) {
    $table->id();
    $table->foreignId('clinic_id')->constrained();
    $table->foreignId('patient_id')->nullable()->constrained(); // Null for walk-in
    $table->foreignId('user_id')->constrained(); // Cashier
    
    $table->decimal('subtotal', 10, 2);
    $table->decimal('discount', 10, 2)->default(0);
    $table->decimal('tax', 10, 2)->default(0);
    $table->decimal('total', 10, 2);
    
    $table->string('status')->default('paid'); // paid, unpaid (for IPD)
    
    $table->timestamps();
});

Schema::create('pharmacy_invoice_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pharmacy_invoice_id')->constrained()->onDelete('cascade');
    $table->foreignId('medicine_id')->constrained();
    
    $table->integer('quantity');
    $table->decimal('unit_price', 10, 2);
    $table->decimal('total_price', 10, 2);
    
    $table->timestamps();
});
```

## 2. The Sale Controller
`Pharmacy\InvoiceController`.

```php
public function store(Request $request, PharmacyService $service)
{
    // Validation...
    
    DB::transaction(function() use ($request, $service) {
        // 1. Create Invoice
        $invoice = PharmacyInvoice::create([...]);
        
        // 2. Process Items
        foreach ($request->items as $item) {
            $medicine = Medicine::find($item['medicine_id']);
            
            // Deduct Stock (The logic from Class 61)
            $service->deductStock($medicine, $item['quantity']);
            
            // Create Line Item
            $invoice->items()->create([
                'medicine_id' => $medicine->id,
                'quantity' => $item['quantity'],
                'unit_price' => $medicine->selling_price,
                'total_price' => $medicine->selling_price * $item['quantity']
            ]);
        }
    });
}
```

## Summary
The POS combines the UI (Vue/Blade with dynamic rows) with the backend stock deduction logic.
