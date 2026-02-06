# Class 58: Discharge Process

## Introduction
The final step. Calculating the bill, releasing the bed, and closing the file.

## 1. The Service Logic
`DischargeService`.

```php
public function calculateBill(Admission $admission)
{
    $total = 0;
    
    // 1. Bed Charges
    foreach ($admission->bedAssignments as $assignment) {
        $end = $assignment->released_at ?? now();
        $days = $assignment->assigned_at->diffInDays($end) + 1; // Part day = 1 day
        $rate = $assignment->bed->daily_rate;
        $total += ($days * $rate);
    }
    
    // 2. Doctor Rounds
    // $total += $admission->rounds->count() * $doctorVisitFee;
    
    // 3. Medicines / Labs (Future Modules)
    
    return $total;
}

public function discharge(Admission $admission)
{
    DB::transaction(function() use ($admission) {
        // 1. Release Bed
        $currentAssignment = $admission->bedAssignments()->whereNull('released_at')->first();
        if ($currentAssignment) {
            $currentAssignment->update(['released_at' => now()]);
            $currentAssignment->bed->update(['status' => 'available']);
        }
        
        // 2. Update Admission
        $admission->update([
            'status' => 'discharged',
            'discharge_date' => now()
        ]);
        
        // 3. Generate Invoice (Module 11)
    });
}
```

## Summary
The discharge process is the trigger for the Billing Module.

## Module 8 Completion
Congratulations! You have completed Module 8. You have built:
-   **Admissions**: Long-term stay tracking.
-   **Bed Management**: Allocation and transfers.
-   **Rounds & Notes**: Clinical documentation.
-   **Discharge**: The exit workflow.

In Module 9, we will build the **Pharmacy Management** system (Inventory, Sales, POS).
