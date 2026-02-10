<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\InpatientRound;
use App\Models\Patient;
use App\Models\InpatientService;
use App\Models\AdmissionDeposit;
use App\Support\TenantContext;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PatientIpdController extends Controller
{
    protected $gatewayService;

    public function __construct(PaymentGatewayService $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    public function currentAdmission(Request $request)
    {
        $user = $request->user();
        $requestedClinicId = $request->header('X-Clinic-ID');
        $targetPatient = $this->resolvePatient($user, $requestedClinicId);

        TenantContext::setClinicId($targetPatient->clinic_id);

        $admission = Admission::where('patient_id', $targetPatient->id)
            // ->where('status', 'admitted') // Allow viewing past admissions too, or at least the latest one
            ->with(['doctor.user', 'bedAssignments' => function($q) {
                $q->whereNull('released_at')->with('bed.room.ward');
            }])
            ->latest()
            ->first();

        if (!$admission) {
            return response()->json(['admission' => null]);
        }

        // If discharged, currentBed will be null or we should get the last one
        $currentBed = $admission->bedAssignments->sortByDesc('assigned_at')->first();

        return response()->json([
            'admission' => [
                'id' => $admission->id,
                'status' => $admission->status, // Added status
                'admission_date' => $admission->admission_date,
                'discharge_date' => $admission->discharge_date, // Added discharge date
                'reason' => $admission->admission_reason,
                'doctor' => $admission->doctor && $admission->doctor->user ? $admission->doctor->user->name : 'Unknown Doctor',
                'ward' => $currentBed && $currentBed->bed && $currentBed->bed->room && $currentBed->bed->room->ward ? $currentBed->bed->room->ward->name : 'N/A',
                'room' => $currentBed && $currentBed->bed && $currentBed->bed->room ? $currentBed->bed->room->room_number : 'N/A',
                'bed' => $currentBed && $currentBed->bed ? $currentBed->bed->bed_number : 'N/A',
            ]
        ]);
    }

    public function rounds(Request $request)
    {
        $user = $request->user();
        $requestedClinicId = $request->header('X-Clinic-ID');
        $targetPatient = $this->resolvePatient($user, $requestedClinicId);

        TenantContext::setClinicId($targetPatient->clinic_id);

        $admission = Admission::where('patient_id', $targetPatient->id)
            // ->where('status', 'admitted')
            ->latest()
            ->first();

        if (!$admission) {
            return response()->json(['rounds' => []]);
        }

        $rounds = InpatientRound::where('admission_id', $admission->id)
            ->with('doctor.user')
            ->orderBy('round_date', 'desc')
            ->get()
            ->map(function ($round) {
                return [
                    'id' => $round->id,
                    'date' => $round->round_date ? \Carbon\Carbon::parse($round->round_date)->format('Y-m-d H:i') : null,
                    'doctor' => $round->doctor && $round->doctor->user ? $round->doctor->user->name : 'Unknown Doctor',
                    'notes' => $round->notes,
                ];
            });

        return response()->json([
            'rounds' => $rounds
        ]);
    }

    public function billing(Request $request)
    {
        $user = $request->user();
        $requestedClinicId = $request->header('X-Clinic-ID');
        $targetPatient = $this->resolvePatient($user, $requestedClinicId);

        TenantContext::setClinicId($targetPatient->clinic_id);

        $admission = Admission::where('patient_id', $targetPatient->id)
            // ->where('status', 'admitted')
            ->latest()
            ->first();

        if (!$admission) {
            return response()->json(['billing' => null]);
        }

        // Check if invoice exists
        $invoice = $admission->invoice; // Assuming 'invoice' relation exists on Admission model

        if ($invoice) {
             $totalAmount = $invoice->total_amount;
             // Total Paid = Invoice Payments (success) + Deposits (success)
             // Note: In generateDischargeInvoice, we already adjusted deposits into payments.
             // So if we just sum invoice->payments, it should be correct IF the deposit adjustment payment is there.
             // But to be safe and robust as per user request:
             // If invoice exists, "Total Paid" is strictly sum of successful payments against this invoice.
             // The deposit adjustment logic in IpdService creates a payment record.

             $paidAmount = $invoice->payments()->where('status', 'success')->sum('amount');
             $dueAmount = max(0, $totalAmount - $paidAmount);

             // Breakdowns from invoice items
             $servicesTotal = $invoice->items->where('item_type', 'service')->sum('total_price');
             $roomRentTotal = $invoice->items->where('item_type', 'bed')->sum('total_price');

             return response()->json([
                'billing' => [
                    'admission_id' => $admission->id,
                    'status' => $admission->status,
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'total_estimated_bill' => round($totalAmount, 2), // It's actual now
                    'total_services' => round($servicesTotal, 2),
                    'total_room_rent' => round($roomRentTotal, 2),
                    'deposited_amount' => round($admission->deposits()->where('status', 'success')->sum('amount'), 2),
                    'paid_amount' => round($paidAmount, 2),
                    'due_amount' => round($dueAmount, 2),
                    'currency' => 'BDT',
                    'is_final' => true
                ]
            ]);
        }

        // Running Bill Calculation (No Invoice yet)
        // 1. Calculate Services Total
        $servicesTotal = InpatientService::where('admission_id', $admission->id)
            ->sum('total_price');

        // 2. Calculate Room Rent
        $roomRentTotal = 0;
        $bedAssignments = $admission->bedAssignments()
            ->with(['bed.room'])
            ->get();

        foreach ($bedAssignments as $assignment) {
            if ($assignment->bed && $assignment->bed->room) {
                $dailyRate = $assignment->bed->room->daily_rate ?? 0;
                $start = Carbon::parse($assignment->assigned_at);
                $end = $assignment->released_at ? Carbon::parse($assignment->released_at) : Carbon::now();

                // Calculate days (at least 1 day)
                $days = $start->diffInDays($end);
                if ($days < 1) $days = 1;

                $roomRentTotal += $days * $dailyRate;
            }
        }

        // 3. Calculate Deposits
        $depositsTotal = AdmissionDeposit::where('admission_id', $admission->id)
            ->where('status', 'success')
            ->sum('amount');

        // 4. Calculate Admission Fees (Invoices)
        // Find invoices linked to admission or created near admission time for this patient
        $admissionFeeInvoices = \App\Models\Invoice::where('patient_id', $admission->patient_id)
            ->where('invoice_type', 'ipd_admission_fee')
            ->where(function($q) use ($admission) {
                $q->where('admission_id', $admission->id)
                  ->orWhereBetween('created_at', [
                      Carbon::parse($admission->admission_date)->subHours(12),
                      Carbon::parse($admission->admission_date)->addHours(24)
                  ]);
            })
            ->get();

        $admissionFeeTotal = $admissionFeeInvoices->sum('total_amount');
        $admissionFeePaid = 0;
        foreach($admissionFeeInvoices as $inv) {
             $admissionFeePaid += $inv->payments()->where('status', 'success')->sum('amount');
        }

        // 5. Calculate Due
        $estimatedTotal = $servicesTotal + $roomRentTotal + $admissionFeeTotal;
        $totalPaid = $depositsTotal + $admissionFeePaid;
        $dueAmount = max(0, $estimatedTotal - $totalPaid);

        return response()->json([
            'billing' => [
                'admission_id' => $admission->id,
                'status' => $admission->status,
                'invoice_id' => null,
                'total_estimated_bill' => round($estimatedTotal, 2),
                'total_services' => round($servicesTotal, 2),
                'total_room_rent' => round($roomRentTotal, 2),
                'total_admission_fees' => round($admissionFeeTotal, 2),
                'deposited_amount' => round($depositsTotal, 2),
                'paid_amount' => round($totalPaid, 2),
                'due_amount' => round($dueAmount, 2),
                'currency' => 'BDT',
                'is_final' => false
            ]
        ]);
    }

    public function payDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'gateway' => 'required|in:stripe,sslcommerz',
            'redirect_url' => 'required|url',
        ]);

        $user = $request->user();
        $requestedClinicId = $request->header('X-Clinic-ID');
        $targetPatient = $this->resolvePatient($user, $requestedClinicId);

        TenantContext::setClinicId($targetPatient->clinic_id);

        $admission = Admission::where('patient_id', $targetPatient->id)
            ->where('status', 'admitted')
            ->latest()
            ->first();

        if (!$admission) {
             return response()->json(['message' => 'No active admission found to add deposit.'], 404);
        }

        $amount = $request->amount;
        $gateway = $request->gateway;
        $description = "Deposit for Admission #" . ($admission->admission_number ?? $admission->id);

        // Create pending deposit
        $deposit = AdmissionDeposit::create([
            'clinic_id' => $admission->clinic_id,
            'admission_id' => $admission->id,
            'amount' => $amount,
            'payment_method' => $gateway,
            'paid_at' => now(),
            'received_by' => null,
            'gateway' => $gateway,
            'status' => 'pending',
        ]);

        $transactionId = $deposit->id;
        $type = 'admission_deposit';
        $redirectUrl = urlencode($request->redirect_url);

        if ($gateway === 'stripe') {
            $successUrl = route('online-payment.stripe.success', [
                'transaction_id' => $transactionId,
                'type' => $type,
                'redirect_url' => $redirectUrl
            ]);
            $cancelUrl = route('online-payment.stripe.cancel', [
                'transaction_id' => $transactionId,
                'type' => $type
            ]);

            $result = $this->gatewayService->createStripeSession(
                $amount,
                'bdt',
                $description,
                $successUrl,
                $cancelUrl,
                ['transaction_id' => $transactionId, 'type' => $type]
            );

            if ($result['success']) {
                $deposit->update(['gateway_transaction_id' => $result['transaction_id']]);
                return response()->json(['payment_url' => $result['url']]);
            } else {
                $deposit->update(['status' => 'failed']);
                return response()->json(['message' => 'Failed to initiate Stripe payment: ' . $result['message']], 500);
            }
        } elseif ($gateway === 'sslcommerz') {
             $tran_id = 'DEP-' . $admission->id . '-' . time();
             $deposit->update(['gateway_transaction_id' => $tran_id]);

             $successUrl = route('online-payment.sslcommerz.success');
             $failUrl = route('online-payment.sslcommerz.fail');
             $cancelUrl = route('online-payment.sslcommerz.cancel');

             $customerDetails = [
                'name' => $targetPatient->name,
                'email' => $targetPatient->email ?? 'guest@example.com',
                'phone' => $targetPatient->phone ?? '01700000000',
                'address' => $targetPatient->address ?? 'Dhaka',
            ];

            // Pass redirectUrl as extra param (value_a)
            $extraParams = ['value_a' => $redirectUrl];

            $result = $this->gatewayService->initiateSslCommerzPayment(
                $amount,
                'BDT',
                $description,
                $tran_id,
                $successUrl,
                $failUrl,
                $cancelUrl,
                $customerDetails,
                $extraParams
            );

            if ($result['success']) {
                return response()->json(['payment_url' => $result['url']]);
            } else {
                $deposit->update(['status' => 'failed']);
                return response()->json(['message' => 'Failed to initiate SSLCommerz payment: ' . $result['message']], 500);
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
                    if ($user->email) $q->where('email', $user->email);
                    if ($user->phone) $q->orWhere('phone', $user->phone);
                })
                ->first();

            if ($foundPatient) {
                $targetPatient = $foundPatient;
            }
        }
        return $targetPatient;
    }
}
