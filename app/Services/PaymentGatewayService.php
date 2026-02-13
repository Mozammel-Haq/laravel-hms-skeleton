<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentGatewayService
{
    protected $stripeSecret;

    protected $sslStoreId;

    protected $sslStorePassword;

    protected $sslIsSandbox;

    public function __construct()
    {
        $this->stripeSecret = config('services.stripe.secret');
        $this->sslStoreId = config('services.sslcommerz.store_id');
        $this->sslStorePassword = config('services.sslcommerz.store_password');
        $this->sslIsSandbox = config('services.sslcommerz.sandbox', true);
    }

    /**
     * Create a Stripe Checkout Session
     */
    public function createStripeSession($amount, $currency, $description, $successUrl, $cancelUrl, $metadata = [])
    {
        Stripe::setApiKey($this->stripeSecret);

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => $description,
                        ],
                        'unit_amount' => $amount * 100, // Stripe expects cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'metadata' => $metadata,
            ]);

            return [
                'success' => true,
                'url' => $session->url,
                'transaction_id' => $session->id,
            ];
        } catch (\Exception $e) {
            Log::error('Stripe Session Creation Failed: '.$e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Initiate SSLCommerz Payment
     */
    public function initiateSslCommerzPayment($amount, $currency, $description, $tran_id, $successUrl, $failUrl, $cancelUrl, $customerDetails = [], $extraParams = [])
    {
        $url = $this->sslIsSandbox
            ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'
            : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';

        $postData = [
            'store_id' => $this->sslStoreId,
            'store_passwd' => $this->sslStorePassword,
            'total_amount' => $amount,
            'currency' => $currency,
            'tran_id' => $tran_id,
            'success_url' => $successUrl,
            'fail_url' => $failUrl,
            'cancel_url' => $cancelUrl,
            'ipn_url' => str_replace('success', 'ipn', $successUrl), // Basic heuristic, or pass explicit IPN
            'cus_name' => $customerDetails['name'] ?? 'Guest',
            'cus_email' => $customerDetails['email'] ?? 'guest@example.com',
            'cus_add1' => $customerDetails['address'] ?? 'N/A',
            'cus_add2' => '',
            'cus_city' => 'Dhaka',
            'cus_state' => 'Dhaka',
            'cus_postcode' => '1000',
            'cus_country' => 'Bangladesh',
            'cus_phone' => $customerDetails['phone'] ?? '01700000000',
            'cus_fax' => '',
            'shipping_method' => 'NO',
            'product_name' => $description,
            'product_category' => 'Healthcare',
            'product_profile' => 'general',
        ];

        // Merge extra parameters (e.g., value_a for redirect_url)
        if (! empty($extraParams)) {
            $postData = array_merge($postData, $extraParams);
        }

        try {
            $response = Http::asForm()->post($url, $postData);

            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['status']) && $result['status'] == 'SUCCESS') {
                    return [
                        'success' => true,
                        'url' => $result['GatewayPageURL'],
                        'transaction_id' => $tran_id,
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => $result['failedreason'] ?? 'Unknown SSLCommerz error',
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to connect to SSLCommerz',
                ];
            }
        } catch (\Exception $e) {
            Log::error('SSLCommerz Init Failed: '.$e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
