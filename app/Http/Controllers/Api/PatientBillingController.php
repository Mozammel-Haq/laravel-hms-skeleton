<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Payment;
use App\Services\PaymentGatewayService;
use App\Support\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PatientBillingController extends Controller
{
    protected $gatewayService;

    public function __construct(PaymentGatewayService $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $requestedClinicId = $request->header('X-Clinic-ID');
        $targetPatient = $this->resolvePatient($user, $requestedClinicId);

        TenantContext::setClinicId($targetPatient->clinic_id);

        $query = Invoice::where('patient_id', $targetPatient->id)
            ->with(['items', 'payments']);

        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', strtolower($request->status));
        }

        $invoices = $query->latest()->get()->map(function ($invoice) {
            $paidAmount = $invoice->payments->where('status', 'success')->sum('amount');
            $dueAmount = max(0, $invoice->total_amount - $paidAmount);

            return [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'date' => $invoice->issued_at ? \Carbon\Carbon::parse($invoice->issued_at)->format('Y-m-d') : \Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d'),
                'amount' => $invoice->total_amount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'status' => ucfirst($invoice->status),
                'items_count' => $invoice->items->count(),
                'download_url' => URL::signedRoute('patient.invoices.download', ['invoice' => $invoice->id]),
            ];
        });

        return response()->json([
            'invoices' => $invoices,
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $requestedClinicId = $request->header('X-Clinic-ID');
        $targetPatient = $this->resolvePatient($user, $requestedClinicId);

        TenantContext::setClinicId($targetPatient->clinic_id);

        $invoice = Invoice::where('patient_id', $targetPatient->id)
            ->where('id', $id)
            ->with(['items', 'payments'])
            ->firstOrFail();

        return response()->json([
            'id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'date' => $invoice->issued_at ? \Carbon\Carbon::parse($invoice->issued_at)->format('Y-m-d') : $invoice->created_at->format('Y-m-d'),
            'subtotal' => $invoice->subtotal,
            'discount' => $invoice->discount,
            'tax' => $invoice->tax,
            'total_amount' => $invoice->total_amount,
            'status' => ucfirst($invoice->status),
            'items' => $invoice->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total' => $item->total_price,
                ];
            }),
            'payments' => $invoice->payments->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'date' => $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') : null,
                    'method' => $payment->payment_method,
                ];
            }),
            'download_url' => URL::signedRoute('patient.invoices.download', ['invoice' => $invoice->id]),
        ]);
    }

    public function pay(Request $request, $id)
    {
        $request->validate([
            'gateway' => 'required|in:stripe,sslcommerz',
        ]);

        $user = $request->user();
        $requestedClinicId = $request->header('X-Clinic-ID');
        $targetPatient = $this->resolvePatient($user, $requestedClinicId);

        TenantContext::setClinicId($targetPatient->clinic_id);

        $invoice = Invoice::where('patient_id', $targetPatient->id)
            ->where('id', $id)
            ->firstOrFail();

        if ($invoice->status === 'cancelled') {
            return response()->json(['message' => 'Invoice has been cancelled.'], 400);
        }

        if ($invoice->invoice_type === 'consultation' && $invoice->appointment_id) {
            $appointment = $invoice->appointment;
            if ($appointment && $appointment->appointment_type === 'online') {
                $today = now()->toDateString();
                $nowTime = now()->format('H:i:s');
                $appointmentDate = $appointment->appointment_date ? $appointment->appointment_date->toDateString() : null;

                $isExpired = $appointmentDate
                    && ($appointmentDate < $today || ($appointmentDate === $today && $appointment->end_time && $appointment->end_time <= $nowTime));

                if ($isExpired || in_array($appointment->status, ['cancelled', 'completed'], true)) {
                    return response()->json(['message' => 'Payment is no longer available for this appointment. Please book a new appointment.'], 400);
                }
            }
        }

        if ($invoice->status === 'paid') {
            return response()->json(['message' => 'Invoice is already paid.'], 400);
        }

        $amount = $invoice->total_amount - $invoice->payments()->where('status', 'success')->sum('amount');
        if ($amount <= 0) {
            return response()->json(['message' => 'Invoice is already fully paid.'], 400);
        }

        $gateway = $request->gateway;
        $description = "Payment for Invoice #{$invoice->invoice_number}";

        // Create a pending payment record
        $payment = Payment::create([
            'clinic_id' => $invoice->clinic_id,
            'invoice_id' => $invoice->id,
            'amount' => $amount,
            'payment_method' => $gateway,
            'paid_at' => now(),
            'received_by' => null,
            'gateway' => $gateway,
            'status' => 'pending',
        ]);

        $transactionId = $payment->id;
        $type = 'invoice';

        if ($gateway === 'stripe') {
            $successUrl = route('online-payment.stripe.success', ['transaction_id' => $transactionId, 'type' => $type]);
            $cancelUrl = route('online-payment.stripe.cancel', ['transaction_id' => $transactionId, 'type' => $type]);

            $result = $this->gatewayService->createStripeSession(
                $amount,
                'bdt',
                $description,
                $successUrl,
                $cancelUrl,
                ['transaction_id' => $transactionId, 'type' => $type]
            );

            if ($result['success']) {
                $payment->update(['gateway_transaction_id' => $result['transaction_id']]);

                return response()->json(['payment_url' => $result['url']]);
            } else {
                $payment->update(['status' => 'failed']);

                return response()->json(['message' => 'Failed to initiate Stripe payment: '.$result['message']], 500);
            }
        } elseif ($gateway === 'sslcommerz') {
            $tran_id = 'INV-'.$invoice->id.'-'.time();
            $payment->update(['gateway_transaction_id' => $tran_id]);

            $successUrl = route('online-payment.sslcommerz.success');
            $failUrl = route('online-payment.sslcommerz.fail');
            $cancelUrl = route('online-payment.sslcommerz.cancel');

            $customerDetails = [
                'name' => $targetPatient->name,
                'email' => $targetPatient->email ?? 'guest@example.com',
                'phone' => $targetPatient->phone ?? '01700000000',
                'address' => $targetPatient->address ?? 'Dhaka',
            ];

            $result = $this->gatewayService->initiateSslCommerzPayment(
                $amount,
                'BDT',
                $description,
                $tran_id,
                $successUrl,
                $failUrl,
                $cancelUrl,
                $customerDetails
            );

            if ($result['success']) {
                return response()->json(['payment_url' => $result['url']]);
            } else {
                $payment->update(['status' => 'failed']);

                return response()->json(['message' => 'Failed to initiate SSLCommerz payment: '.$result['message']], 500);
            }
        }

        return response()->json(['message' => 'Unsupported gateway'], 400);
    }

    private function resolvePatient($user, $requestedClinicId)
    {
        $targetPatient = $user;
        if ($requestedClinicId && $requestedClinicId != $user->clinic_id) {
            $foundPatient = Patient::where('clinic_id', $requestedClinicId)
                ->where(function ($q) use ($user) {
                    if ($user->email) {
                        $q->where('email', $user->email);
                    }
                    if ($user->phone) {
                        $q->orWhere('phone', $user->phone);
                    }
                })
                ->first();

            if ($foundPatient) {
                $targetPatient = $foundPatient;
            }
        }

        return $targetPatient;
    }
}
