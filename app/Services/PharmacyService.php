<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\PharmacySale;
use App\Models\PharmacySaleItem;
use Illuminate\Support\Facades\DB;
use Exception;

class PharmacyService
{
    /**
     * Process a pharmacy sale.
     *
     * @param mixed $patient
     * @param array $items Array of ['medicine_id', 'quantity']
     * @return PharmacySale
     * @throws Exception
     */
    public function processSale($patient, array $items, ?int $prescriptionId = null)
    {
        $clinicId = \App\Support\TenantContext::getClinicId() ?? auth()->user()->clinic_id ?? $patient->clinic_id;
        
        if (!$clinicId) {
            throw new Exception("Clinic context is required to process sale.");
        }

        return DB::transaction(function () use ($patient, $items, $prescriptionId, $clinicId) {
            $totalAmount = 0;
            $saleItems = [];

            foreach ($items as $item) {
                $medicine = Medicine::find($item['medicine_id']);

                if (!$medicine) {
                    throw new Exception("Medicine not found: " . $item['medicine_id']);
                }

                // Get batches for this clinic, ordered by expiry (FIFO)
                $batches = MedicineBatch::where('medicine_id', $medicine->id)
                    ->where('clinic_id', $clinicId)
                    ->where('quantity_in_stock', '>', 0)
                    ->orderBy('expiry_date', 'asc')
                    ->lockForUpdate()
                    ->get();

                $totalStock = $batches->sum('quantity_in_stock');

                if ($totalStock < $item['quantity']) {
                    throw new Exception("Insufficient stock for medicine: " . $medicine->name . " (Requested: " . $item['quantity'] . ", Available: " . $totalStock . ")");
                }

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

                // Check for low stock after deduction
                $newTotalStock = MedicineBatch::where('medicine_id', $medicine->id)
                    ->where('clinic_id', $clinicId)
                    ->sum('quantity_in_stock');

                if ($newTotalStock < 10) {
                    $pharmacists = \App\Models\User::whereHas('roles', function ($q) {
                        $q->where('name', 'Pharmacist');
                    })->where('clinic_id', $clinicId)->get();

                    foreach ($pharmacists as $pharmacist) {
                        $pharmacist->notify(new \App\Notifications\LowStockNotification($medicine, (int)$newTotalStock));
                    }
                }

                $subtotal = $medicine->price * $item['quantity'];
                $totalAmount += $subtotal;

                $saleItems[] = [
                    'medicine_id' => $medicine->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $medicine->price,
                    'total_price' => $subtotal,
                ];
            }

            $sale = PharmacySale::create([
                'clinic_id' => $clinicId,
                'prescription_id' => $prescriptionId,
                'patient_id' => $patient->id,
                'sale_date' => now(),
                'total_amount' => $totalAmount,
            ]);

            foreach ($saleItems as $saleItem) {
                PharmacySaleItem::create([
                    'pharmacy_sale_id' => $sale->id,
                    'medicine_id' => $saleItem['medicine_id'],
                    'quantity' => $saleItem['quantity'],
                    'unit_price' => $saleItem['unit_price'],
                ]);
            }

            return $sale;
        });
    }
}
