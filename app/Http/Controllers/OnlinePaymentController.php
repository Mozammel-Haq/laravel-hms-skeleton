<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\AdmissionDeposit;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class OnlinePaymentController extends Controller
{
    protected $gatewayService;

    public function __construct(PaymentGatewayService $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    /**
     * Initiate an online payment for an Admission Deposit or Invoice.
     */
    public function initiate(Request $request)
    {
        $request->validate([
            'type' => 'required|in:admission_deposit,invoice',
            'id' => 'required|integer',
            'amount' => 'required|numeric|min:1',
            'gateway' => 'required|in:stripe,sslcommerz',
        ]);

        $type = $request->type;
        $id = $request->id;
        $amount = $request->amount;
        $gateway = $request->gateway;

        $description = "";
        $cancelUrl = url()->previous();

        // Context data to store in session or metadata
        $context = [
            'type' => $type,
            'id' => $id,
            'amount' => $amount,
            'gateway' => $gateway,
        ];

        if ($type === 'admission_deposit') {
            $admission = Admission::findOrFail($id);
            $description = "Deposit for Admission #{$admission->admission_number}";
            // Create a pending deposit record
            $deposit = AdmissionDeposit::create([
                'clinic_id' => $admission->clinic_id,
                'admission_id' => $admission->id,
                'amount' => $amount,
                'payment_method' => $gateway, // e.g., 'stripe'
                'paid_at' => now(), // Will confirm on success
                'received_by' => null, // Online payment
                'gateway' => $gateway,
                'status' => 'pending',
            ]);
            $context['transaction_id'] = $deposit->id; // Use ID to track

        } elseif ($type === 'invoice') {
            $invoice = Invoice::findOrFail($id);
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
            $context['transaction_id'] = $payment->id;
        }

        if ($gateway === 'stripe') {
            $successUrl = route('online-payment.stripe.success', ['transaction_id' => $context['transaction_id'], 'type' => $type]);
            $cancelUrl = route('online-payment.stripe.cancel', ['transaction_id' => $context['transaction_id'], 'type' => $type]);

            $result = $this->gatewayService->createStripeSession(
                $amount,
                'bdt', // Changed from USD to BDT as per requirements
                $description,
                $successUrl,
                $cancelUrl,
                ['transaction_id' => $context['transaction_id'], 'type' => $type]
            );

            if ($result['success']) {
                // Update the pending record with gateway transaction ID if needed
                if ($type === 'admission_deposit') {
                    AdmissionDeposit::find($context['transaction_id'])->update(['gateway_transaction_id' => $result['transaction_id']]);
                } elseif ($type === 'invoice') {
                    Payment::find($context['transaction_id'])->update(['gateway_transaction_id' => $result['transaction_id']]);
                }

                return redirect($result['url']);
            } else {
                // Mark as failed if initialization fails
                if ($type === 'admission_deposit') {
                    AdmissionDeposit::find($context['transaction_id'])->update(['status' => 'failed']);
                } elseif ($type === 'invoice') {
                    Payment::find($context['transaction_id'])->update(['status' => 'failed']);
                }

                return back()->with('error', 'Failed to initiate Stripe payment: ' . $result['message']);
            }
        } elseif ($gateway === 'sslcommerz') {
            // Generate unique transaction ID for SSLCommerz
            // Format: TYPE-ID-TIMESTAMP
            $tran_id = strtoupper(substr($type, 0, 3)) . '-' . $id . '-' . time();

            // Update the pending record with this transaction ID immediately
            if ($type === 'admission_deposit') {
                AdmissionDeposit::find($context['transaction_id'])->update(['gateway_transaction_id' => $tran_id]);
                $customer = Admission::find($id)->patient;
            } elseif ($type === 'invoice') {
                Payment::find($context['transaction_id'])->update(['gateway_transaction_id' => $tran_id]);
                $customer = Invoice::find($id)->patient;
            }

            $successUrl = route('online-payment.sslcommerz.success');
            $failUrl = route('online-payment.sslcommerz.fail');
            $cancelUrl = route('online-payment.sslcommerz.cancel');

            $customerDetails = [
                'name' => $customer ? $customer->name : 'Guest',
                'email' => $customer ? $customer->email : 'guest@example.com',
                'phone' => $customer ? $customer->phone : '01700000000',
                'address' => $customer ? $customer->address : 'Dhaka',
            ];

            $result = $this->gatewayService->initiateSslCommerzPayment(
                $amount,
                'BDT', // SSLCommerz usually uses BDT
                $description,
                $tran_id,
                $successUrl,
                $failUrl,
                $cancelUrl,
                $customerDetails
            );

            if ($result['success']) {
                return redirect($result['url']);
            } else {
                 if ($type === 'admission_deposit') {
                    AdmissionDeposit::find($context['transaction_id'])->update(['status' => 'failed']);
                } elseif ($type === 'invoice') {
                    Payment::find($context['transaction_id'])->update(['status' => 'failed']);
                }
                return back()->with('error', 'Failed to initiate SSLCommerz payment: ' . $result['message']);
            }
        }

        return back()->with('error', 'Unsupported gateway');
    }

    public function stripeSuccess(Request $request)
    {
        $transactionId = $request->transaction_id;
        $type = $request->type;
        $redirectUrl = $request->input('redirect_url');

        try {
            DB::beginTransaction();

            if ($type === 'admission_deposit') {
                $deposit = AdmissionDeposit::findOrFail($transactionId);
                if ($deposit->status !== 'success') {
                    $deposit->update(['status' => 'success']);
                }
                $redirectRoute = $redirectUrl ? urldecode($redirectUrl) : route('ipd.show', $deposit->admission_id);

            } elseif ($type === 'invoice') {
                $payment = Payment::findOrFail($transactionId);
                if ($payment->status !== 'success') {
                    $payment->update(['status' => 'success']);

                    // Update Invoice status based on total paid amount
                    $invoice = $payment->invoice;

                    // Calculate total paid amount including this new payment
                    $totalPaid = $invoice->payments()->where('status', 'success')->sum('amount');

                    if ($totalPaid >= $invoice->total_amount) {
                        $invoice->update(['status' => 'paid']);
                    } else {
                        $invoice->update(['status' => 'partial']);
                    }
                }
                $redirectRoute = $redirectUrl ? urldecode($redirectUrl) : route('billing.show', $payment->invoice_id);
            }

            DB::commit();
            return redirect($redirectRoute)->with('success', 'Payment successful!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Stripe Success Error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Payment processed but failed to update records. Please contact support.');
        }
    }

    public function stripeCancel(Request $request)
    {
        $transactionId = $request->transaction_id;
        $type = $request->type;

        if ($type === 'admission_deposit') {
            $deposit = AdmissionDeposit::findOrFail($transactionId);
            $deposit->update(['status' => 'failed']);
            return redirect()->route('ipd.show', $deposit->admission_id)->with('error', 'Payment was cancelled.');
        } elseif ($type === 'invoice') {
            $payment = Payment::findOrFail($transactionId);
            $payment->update(['status' => 'failed']);
            return redirect()->route('billing.show', $payment->invoice_id)->with('error', 'Payment was cancelled.');
        }

        return redirect('/')->with('error', 'Payment cancelled.');
    }

    public function sslCommerzSuccess(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $val_id = $request->input('val_id');
        $amount = $request->input('amount');
        $card_type = $request->input('card_type');
        $store_amount = $request->input('store_amount');
        $card_no = $request->input('card_no');
        $bank_tran_id = $request->input('bank_tran_id');
        $status = $request->input('status');
        $tran_date = $request->input('tran_date');
        $currency = $request->input('currency');
        $card_issuer = $request->input('card_issuer');
        $card_brand = $request->input('card_brand');
        $card_issuer_country = $request->input('card_issuer_country');
        $card_issuer_country_code = $request->input('card_issuer_country_code');
        $redirectUrl = $request->input('value_a'); // Use value_a for redirect_url

        // Validation logic (can call validate API here)
        if ($status !== 'VALID') {
             return redirect('/?error=Payment validation failed');
        }

        return $this->processSslCommerzPayment($tran_id, $val_id, false, $redirectUrl);
    }

    public function sslCommerzFail(Request $request)
    {
        $tran_id = $request->input('tran_id');
        return $this->updateSslCommerzStatus($tran_id, 'failed', 'Payment Failed');
    }

    public function sslCommerzCancel(Request $request)
    {
        $tran_id = $request->input('tran_id');
        return $this->updateSslCommerzStatus($tran_id, 'cancelled', 'Payment Cancelled');
    }

    public function sslCommerzIpn(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $status = $request->input('status');
        $val_id = $request->input('val_id');

        if ($status === 'VALID') {
            $this->processSslCommerzPayment($tran_id, $val_id, true);
            echo "IPN Success";
        } else {
            $this->updateSslCommerzStatus($tran_id, 'failed', 'IPN Failed', true);
             echo "IPN Failed";
        }
    }

    protected function processSslCommerzPayment($tran_id, $val_id, $isIpn = false, $redirectUrl = null)
    {
        // Identify record by tran_id
        $record = null;
        $type = null;

        $deposit = AdmissionDeposit::where('gateway_transaction_id', $tran_id)->first();
        if ($deposit) {
            $record = $deposit;
            $type = 'admission_deposit';
        } else {
            $payment = Payment::where('gateway_transaction_id', $tran_id)->first();
            if ($payment) {
                $record = $payment;
                $type = 'invoice';
            }
        }

        if (!$record) {
            Log::error("SSLCommerz Payment Record Not Found: $tran_id");
            if ($isIpn) return;
            return redirect('/?error=Transaction record not found');
        }

        if ($record->status === 'success') {
             if ($isIpn) return;
             // Redirect to appropriate view
             if ($type === 'admission_deposit') {
                 return redirect()->route('ipd.show', ['admission' => $record->admission_id, 'payment_status' => 'success', 'message' => 'Payment already processed']);
             } else {
                 return redirect()->route('billing.show', ['invoice' => $record->invoice_id, 'payment_status' => 'success', 'message' => 'Payment already processed']);
             }
        }

        try {
            DB::beginTransaction();

            $record->update(['status' => 'success', 'gateway_transaction_id' => $val_id]); // Store val_id as verified ID

            $redirectResponse = null;

            if ($type === 'admission_deposit') {
                // $record->admission->increment('deposit_amount', $record->amount); // Column does not exist
                $route = $redirectUrl ? urldecode($redirectUrl) : route('ipd.show', ['admission' => $record->admission_id, 'payment_status' => 'success']);
                $redirectResponse = redirect($route);
            } else {
                $invoice = $record->invoice;
                // $invoice->increment('paid_amount', $record->amount); // Removed as column might not exist

                $totalPaid = $invoice->payments()->where('status', 'success')->sum('amount');

                if ($totalPaid >= $invoice->total_amount) {
                    $invoice->update(['status' => 'paid']);
                } else {
                    $invoice->update(['status' => 'partial']);
                }
                $route = $redirectUrl ? urldecode($redirectUrl) : route('billing.show', ['invoice' => $record->invoice_id, 'payment_status' => 'success']);
                $redirectResponse = redirect($route);
            }

            DB::commit();

            if ($isIpn) return;
            return $redirectResponse;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SSLCommerz Process Error: ' . $e->getMessage());
            if ($isIpn) return;
            return redirect('/?error=Payment processed but failed to update records');
        }
    }

    protected function updateSslCommerzStatus($tran_id, $status, $message, $isIpn = false)
    {
         $deposit = AdmissionDeposit::where('gateway_transaction_id', $tran_id)->first();
         if ($deposit) {
             $deposit->update(['status' => $status]);
             if ($isIpn) return;
             return redirect()->route('ipd.show', ['admission' => $deposit->admission_id, 'payment_status' => 'error', 'message' => $message]);
         }

         $payment = Payment::where('gateway_transaction_id', $tran_id)->first();
         if ($payment) {
             $payment->update(['status' => $status]);
             if ($isIpn) return;
             return redirect()->route('billing.show', ['invoice' => $payment->invoice_id, 'payment_status' => 'error', 'message' => $message]);
         }

         if ($isIpn) return;
         return redirect('/?error=' . urlencode($message));
    }

    /**
     * Handle Stripe Webhook
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::error('Stripe Webhook Error: Invalid Payload');
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Stripe Webhook Error: Invalid Signature');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->fulfillCheckout($session);
                break;
            default:
                Log::info('Stripe Webhook Received: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    protected function fulfillCheckout($session)
    {
        $transactionId = $session->metadata->transaction_id ?? null;
        $type = $session->metadata->type ?? null;

        if (!$transactionId || !$type) {
            Log::error('Stripe Webhook Error: Missing metadata');
            return;
        }

        Log::info("Processing Stripe Webhook for $type #$transactionId");

        try {
            if ($type === 'admission_deposit') {
                $deposit = AdmissionDeposit::find($transactionId);
                if ($deposit && $deposit->status !== 'success') {
                    $deposit->update(['status' => 'success', 'gateway_transaction_id' => $session->payment_intent]);
                    // $deposit->admission->increment('deposit_amount', $deposit->amount); // Column does not exist
                    Log::info("Admission Deposit #$transactionId confirmed via Webhook");
                }
            } elseif ($type === 'invoice') {
                $payment = Payment::find($transactionId);
                if ($payment && $payment->status !== 'success') {
                    $payment->update(['status' => 'success', 'gateway_transaction_id' => $session->payment_intent]);

                    $invoice = $payment->invoice;
                    // Calculate total paid amount including this new payment
                    $totalPaid = $invoice->payments()->where('status', 'success')->sum('amount');

                    if ($totalPaid >= $invoice->total_amount) {
                        $invoice->update(['status' => 'paid']);
                    } else {
                        $invoice->update(['status' => 'partial']);
                    }
                    Log::info("Invoice Payment #$transactionId confirmed via Webhook");
                }
            }
        } catch (\Exception $e) {
            Log::error('Stripe Webhook Fulfill Error: ' . $e->getMessage());
        }
    }
}
