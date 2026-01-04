# 03. Core Business Logic (Services)

The application uses the **Service Layer Pattern** to handle complex business logic. This keeps Controllers thin and ensures data integrity via database transactions.

## 1. Appointment Service
Handles slot generation logic. It calculates time slots dynamically based on the doctor's schedule and excludes already booked slots.

**File:** `app/Services/AppointmentService.php`

```php
<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentService
{
    public function getAvailableSlots(Doctor $doctor, string $date): array
    {
        $date = Carbon::parse($date);
        $dayOfWeek = $date->dayOfWeek;

        // 1. Check Schedule
        $schedule = $doctor->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('status', 'active')
            ->first();

        if (!$schedule) return [];

        // 2. Generate Slots
        $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->start_time);
        $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->end_time);
        $slotDuration = $schedule->slot_duration_minutes;

        $slots = [];
        $currentSlot = $startTime->copy();

        // 3. Get Existing Bookings
        $bookedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $date->format('Y-m-d'))
            ->whereIn('status', ['pending', 'confirmed'])
            ->get(['start_time']);

        while ($currentSlot->lt($endTime)) {
            $slotEnd = $currentSlot->copy()->addMinutes($slotDuration);
            if ($slotEnd->gt($endTime)) break;

            $startString = $currentSlot->format('H:i:00');
            
            // Check availability
            $isBooked = $bookedAppointments->contains('start_time', $startString);

            $slots[] = [
                'start_time' => $currentSlot->format('H:i'),
                'end_time' => $slotEnd->format('H:i'),
                'is_booked' => $isBooked,
            ];

            $currentSlot->addMinutes($slotDuration);
        }

        return $slots;
    }
}
```

## 2. Pharmacy Service
Handles FIFO (First-In-First-Out) stock deduction. It ensures that the oldest medicine batches are sold first to reduce wastage.

**File:** `app/Services/PharmacyService.php`

```php
public function processSale($patient, array $items, ?int $prescriptionId = null)
{
    return DB::transaction(function () use ($patient, $items, $prescriptionId) {
        $totalAmount = 0;
        $saleItems = [];

        foreach ($items as $item) {
            $medicine = Medicine::find($item['medicine_id']);
            
            // FIFO: Lock batches for update
            $batches = MedicineBatch::where('medicine_id', $medicine->id)
                ->where('clinic_id', $patient->clinic_id)
                ->where('quantity_in_stock', '>', 0)
                ->orderBy('expiry_date', 'asc')
                ->lockForUpdate()
                ->get();

            $totalStock = $batches->sum('quantity_in_stock');
            if ($totalStock < $item['quantity']) {
                throw new Exception("Insufficient stock for {$medicine->name}");
            }

            // Deduct from batches
            $remainingToDeduct = $item['quantity'];
            foreach ($batches as $batch) {
                if ($remainingToDeduct <= 0) break;

                if ($batch->quantity_in_stock >= $remainingToDeduct) {
                    $batch->decrement('quantity_in_stock', $remainingToDeduct);
                    $remainingToDeduct = 0;
                } else {
                    $deducted = $batch->quantity_in_stock;
                    $batch->update(['quantity_in_stock' => 0]);
                    $remainingToDeduct -= $deducted;
                }
            }

            // Prepare record
            $subtotal = $medicine->price * $item['quantity'];
            $totalAmount += $subtotal;
            // ... add to $saleItems array
        }

        // Create Sale Record
        $sale = PharmacySale::create([/*...*/]);
        
        // Create Sale Items
        foreach ($saleItems as $saleItem) {
            PharmacySaleItem::create([/*...*/]);
        }

        return $sale;
    });
}
```

## 3. Billing Service
Manages Invoice creation and Payments.

**File:** `app/Services/BillingService.php`

```php
public function createInvoice($patient, array $items, $appointmentId = null, $discount = 0, $tax = 0)
{
    return DB::transaction(function () use ($patient, $items, $appointmentId, $discount, $tax) {
        $subtotal = collect($items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
        $totalAmount = $subtotal - $discount + $tax;

        $invoice = Invoice::create([
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'total_amount' => $totalAmount,
            'status' => 'unpaid',
            // ... other fields
        ]);

        foreach ($items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                // ... item details
            ]);
        }

        return $invoice;
    });
}
```

## 4. IPD Service (In-Patient Department)
Handles Admissions, Bed Assignment (with conflict checks), and Discharges.

**File:** `app/Services/IpdService.php`

```php
public function assignBed(Admission $admission, int $bedId)
{
    return DB::transaction(function () use ($admission, $bedId) {
        $bed = Bed::lockForUpdate()->find($bedId);

        if ($bed->status !== 'available') {
            throw new Exception("Bed is not available.");
        }

        // Handle Transfer: End current assignment if exists
        $currentAssignment = $admission->bedAssignments()
            ->whereNull('end_date')
            ->first();

        if ($currentAssignment) {
            $currentAssignment->update(['end_date' => now()]);
            Bed::find($currentAssignment->bed_id)->update(['status' => 'available']);
        }

        // Create new assignment
        $assignment = BedAssignment::create([
            'admission_id' => $admission->id,
            'bed_id' => $bed->id,
            'start_date' => now(),
            'status' => 'active',
        ]);

        $bed->update(['status' => 'occupied']);

        return $assignment;
    });
}
```
