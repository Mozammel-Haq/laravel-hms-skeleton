# Class 72: Service Aggregation

## Introduction
When discharging a patient (IPD) or checking out an OPD patient, we need to gather all "unbilled" items.

## 1. The Billing Service
`BillingService` is responsible for generating invoices.

```php
class BillingService
{
    public function generateForAdmission(Admission $admission)
    {
        // 1. Calculate Bed Charges (from Class 58)
        $bedCharges = $this->calculateBedCharges($admission);
        
        // 2. Gather Unbilled Lab Requests
        $labRequests = $admission->labRequests()->whereDoesntHave('invoiceItem')->get();
        
        // 3. Gather Unbilled Pharmacy Sales
        // ...
        
        // 4. Create Invoice
        $invoice = Invoice::create([...]);
        
        // 5. Add Items
        foreach ($labRequests as $req) {
            $invoice->items()->create([
                'billable_type' => get_class($req),
                'billable_id' => $req->id,
                'description' => 'Lab Request #' . $req->id,
                'unit_price' => $req->total_amount,
                'total_price' => $req->total_amount
            ]);
        }
        
        return $invoice;
    }
}
```

## 2. Preventing Double Billing
The `whereDoesntHave('invoiceItem')` check is crucial.
Alternatively, update a `billing_status` column on the source model (`billed`).

## Summary
The aggregator pattern ensures nothing slips through the cracks when the patient leaves.
