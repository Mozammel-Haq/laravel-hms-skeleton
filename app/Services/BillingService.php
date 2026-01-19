<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BillingService
{
    /**
     * Create a new invoice.
     *
     * @param mixed $patient
     * @param array $items Array of ['item_type', 'reference_id', 'description', 'quantity', 'unit_price']
     * @param int|null $appointmentId
     * @param float $discount
     * @param float $tax
     * @return Invoice
     */
    public function createInvoice($patient, array $items, ?int $appointmentId = null, float $discount = 0, float $tax = 0, ?int $visitId = null, ?string $invoiceType = null, ?int $createdBy = null, bool $finalize = true)
    {
        return DB::transaction(function () use ($patient, $items, $appointmentId, $discount, $tax, $visitId, $invoiceType, $createdBy, $finalize) {
            $subtotal = collect($items)->sum(fn($item) => $item['quantity'] * $item['unit_price']);

            // Calculate tax amount (tax is passed as percentage)
            $taxAmount = max(0, ($subtotal - $discount)) * ($tax / 100);
            $totalAmount = max(0, $subtotal - $discount + $taxAmount);

            // Generate a simple unique invoice number for now.
            // In production, this should be sequential per clinic.
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            $invoice = Invoice::create([
                'clinic_id' => $patient->clinic_id,
                'patient_id' => $patient->id,
                'appointment_id' => $appointmentId,
                'visit_id' => $visitId,
                'invoice_number' => $invoiceNumber,
                'invoice_type' => $invoiceType,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total_amount' => $totalAmount,
                'status' => 'unpaid',
                'state' => $finalize ? 'finalized' : 'draft',
                'issued_at' => now(),
                'finalized_at' => $finalize ? now() : null,
                'finalized_by' => $finalize ? ($createdBy ?? auth()->id()) : null,
                'created_by' => $createdBy ?? auth()->id(),
            ]);

            foreach ($items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_type' => $item['item_type'],
                    'reference_id' => $item['reference_id'] ?? null,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            return $invoice;
        });
    }

    /**
     * Record a payment for an invoice.
     *
     * @param Invoice $invoice
     * @param float $amount
     * @param string $method
     * @param User $receivedBy
     * @param string|null $reference
     * @return Invoice
     */
    public function recordPayment(Invoice $invoice, float $amount, string $method, User $receivedBy, ?string $reference = null)
    {
        return DB::transaction(function () use ($invoice, $amount, $method, $receivedBy, $reference) {
            Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $amount,
                'payment_method' => $method,
                'transaction_reference' => $reference,
                'paid_at' => now(),
                'received_by' => $receivedBy->id,
            ]);

            // Recalculate status
            $totalPaid = $invoice->payments()->sum('amount'); // This sum includes the one we just added? No, unless we reload.
            // But we didn't add it to the relationship instance.
            // Let's just sum from DB.
            // Wait, inside transaction, if we query relation, it should see the new record if we refresh or query DB.

            // Payment::create inserts into DB.
            // $invoice->payments() queries DB.
            // So it should include the new payment.

            // Re-fetch total paid
            $totalPaid = $invoice->payments()->sum('amount');

            if ($totalPaid >= $invoice->total_amount) {
                $invoice->update(['status' => 'paid']);
            } elseif ($totalPaid > 0) {
                $invoice->update(['status' => 'partial']);
            }

            return $invoice->fresh();
        });
    }
}
