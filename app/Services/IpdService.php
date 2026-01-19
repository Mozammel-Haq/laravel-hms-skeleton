<?php

namespace App\Services;

use App\Models\Admission;
use App\Models\AdmissionDeposit;
use App\Models\Bed;
use App\Models\BedAssignment;
use App\Models\Patient;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Services\BillingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;

class IpdService
{
    /**
     * Admit a patient.
     *
     * @param Patient $patient
     * @param int $doctorId
     * @param string $admissionDate
     * @param string $reason
     * @return Admission
     */
    public function admitPatient(Patient $patient, int $doctorId, string $admissionDate, string $reason)
    {
        return Admission::create([
            'clinic_id' => $patient->clinic_id,
            'patient_id' => $patient->id,
            'admitting_doctor_id' => $doctorId,
            'admission_date' => $admissionDate,
            'status' => 'admitted',
            'admission_reason' => $reason,
            'current_bed_id' => null,
        ]);
    }

    /**
     * Assign a bed to an admission.
     *
     * @param Admission $admission
     * @param int $bedId
     * @return BedAssignment
     * @throws Exception
     */
    public function assignBed(Admission $admission, int $bedId)
    {
        return DB::transaction(function () use ($admission, $bedId) {
            $bed = Bed::lockForUpdate()->find($bedId);

            if (!$bed) {
                throw new Exception("Bed not found.");
            }

            if ($bed->status !== 'available') {
                throw new Exception("Bed is not available.");
            }

            // End current active assignment if any
            $currentAssignment = $admission->bedAssignments()
                ->whereNull('released_at')
                ->first();

            if ($currentAssignment) {
                $currentAssignment->update(['released_at' => now()]);
                // Free up the old bed
                $oldBed = Bed::find($currentAssignment->bed_id);
                $oldBed->update(['status' => 'available']);
            }

            $assignment = BedAssignment::create([
                'clinic_id' => $admission->clinic_id,
                'admission_id' => $admission->id,
                'bed_id' => $bed->id,
                'assigned_at' => now(),
                'status' => 'active',
            ]);

            $bed->update(['status' => 'occupied']);
            $admission->update(['current_bed_id' => $bed->id]);

            return $assignment;
        });
    }

    /**
     * Discharge a patient.
     *
     * @param Admission $admission
     * @param string $dischargeDate
     * @param int|null $dischargedBy
     * @param string|null $reason
     * @return Admission
     */
    public function dischargePatient(Admission $admission, string $dischargeDate, ?int $dischargedBy = null, ?string $reason = null)
    {
        return DB::transaction(function () use ($admission, $dischargeDate, $dischargedBy, $reason) {
            // Release bed if any
            $currentAssignment = $admission->bedAssignments()
                ->whereNull('released_at')
                ->first();

            if ($currentAssignment) {
                $currentAssignment->update(['released_at' => $dischargeDate]);
                $bed = Bed::find($currentAssignment->bed_id);
                $bed->update(['status' => 'available']);
            }

            $admission->update([
                'status' => 'discharged',
                'current_bed_id' => null,
                'discharged_by' => $dischargedBy,
                'discharge_date' => $dischargeDate,
                'discharge_reason' => $reason,
            ]);

            return $admission;
        });
    }

    /**
     * Generate discharge invoice for admission.
     *
     * @param Admission $admission
     * @param string|null $dischargeDate
     * @param float $discount
     * @param float $tax
     * @return Invoice
     */
    public function generateDischargeInvoice(Admission $admission, ?string $dischargeDate = null, float $discount = 0, float $tax = 0)
    {
        $items = [];

        // 1. Calculate Bed Charges
        foreach ($admission->bedAssignments as $assignment) {
            $start = Carbon::parse($assignment->assigned_at);
            $end = $assignment->released_at ? Carbon::parse($assignment->released_at) : ($dischargeDate ? Carbon::parse($dischargeDate) : now());

            // Calculate duration in days, minimum 1 day
            $days = $start->diffInDays($end);
            if ($days < 1) {
                $days = 1;
            }

            $bed = $assignment->bed;
            $room = $bed->room;
            $dailyRate = $room->daily_rate ?? 0;
            $amount = $days * $dailyRate;

            if ($amount > 0) {
                $items[] = [
                    'item_type' => 'bed',
                    'reference_id' => $assignment->id,
                    'description' => "Bed Charge: {$bed->bed_number} ({$room->room_type}) - {$days} days",
                    'quantity' => $days,
                    'unit_price' => $dailyRate,
                ];
            }
        }

        // 2. Calculate Service Charges
        foreach ($admission->services as $service) {
            $items[] = [
                'item_type' => 'service',
                'reference_id' => $service->id,
                'description' => $service->service_name,
                'quantity' => $service->quantity,
                'unit_price' => $service->unit_price,
            ];
        }

        // Create Invoice via BillingService to ensure consistent state handling
        /** @var BillingService $billing */
        $billing = app(BillingService::class);
        $invoice = $billing->createInvoice(
            $admission->patient,
            $items,
            appointmentId: null,
            discount: $discount,
            tax: $tax,
            visitId: null,
            invoiceType: 'ipd_discharge',
            createdBy: auth()->id(),
            finalize: true
        );

        // Link the invoice to the admission
        $invoice->update(['admission_id' => $admission->id]);

        // Adjust deposits against the invoice as payments
        $depositTotal = AdmissionDeposit::where('admission_id', $admission->id)->sum('amount');
        if ($depositTotal > 0) {
            $amountToApply = min($depositTotal, $invoice->total_amount);
            $billing->recordPayment(
                $invoice,
                $amountToApply,
                method: 'cash',
                receivedBy: auth()->user(),
                reference: 'ADJ-DEP-' . Str::upper(Str::random(10))
            );
        }

        return $invoice;
    }
}
