<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class CinetPayService
{
    public function createPayment(array $payload): array
    {
        $defaults = [
            'apikey' => Config::get('cinetpay.api_key'),
            'site_id' => Config::get('cinetpay.site_id'),
            'transaction_id' => $payload['transaction_id'] ?? 'event_' . time(),
            'amount' => $payload['amount'] ?? 0,
            'currency' => $payload['currency'] ?? 'XOF',
            'description' => $payload['description'] ?? 'Paiement événement',
            'customer_name' => $payload['customer_name'] ?? 'Client ELEDJI',
            'customer_email' => $payload['customer_email'] ?? 'client@example.com',
            'return_url' => $payload['return_url'] ?? url('/'),
            'notify_url' => $payload['notify_url'] ?? url('/'),
        ];

        $endpoint = Config::get('cinetpay.endpoint', 'https://api-checkout.cinetpay.com/v2/?method=CHECKOUT');
        $response = Http::asForm()->post($endpoint, array_merge($defaults, $payload));

        if (! $response->successful()) {
            return [
                'success' => false,
                'message' => $response->body(),
            ];
        }

        return array_merge(['success' => true], $response->json());
    }
}
