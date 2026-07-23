<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PzgateService
{
    private string $apiKey;
    private string $secretKey;
    private string $baseUrl;
    private string $currency;

    public function __construct()
    {
        $this->apiKey    = config('pzgate.api_key');
        $this->secretKey = config('pzgate.secret_key');
        $this->baseUrl   = config('pzgate.base_url');
        $this->currency  = config('pzgate.currency');
    }

    /**
     * Initie un paiement mobile money (TMoney ou Flooz)
     */
    public function initiateMobileMoney(array $data): array
    {
        $payload = [
            'amount'       => $data['amount'],
            'currency'     => $this->currency,
            'phone'        => $data['phone'],
            'provider'     => $data['provider'], // 'TMONEY' ou 'FLOOZ'
            'reference'    => $data['reference'],
            'description'  => $data['description'],
            'callback_url' => config('pzgate.callback_url'),
            'return_url'   => config('pzgate.return_url'),
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])->post($this->baseUrl . '/payments/mobile', $payload);

            Log::info('PZGate initiateMobileMoney', [
                'payload'  => $payload,
                'response' => $response->json(),
            ]);

            return $response->json();

        } catch (\Exception $e) {
            Log::error('PZGate error', ['message' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Initie un paiement carte bancaire
     */
    public function initiateCard(array $data): array
    {
        $payload = [
            'amount'      => $data['amount'],
            'currency'    => $this->currency,
            'reference'   => $data['reference'],
            'description' => $data['description'],
            'card'        => [
                'number'     => $data['card_number'],
                'expiry'     => $data['card_expiry'],
                'cvv'        => $data['card_cvv'],
                'holder'     => $data['card_holder'],
            ],
            'callback_url' => config('pzgate.callback_url'),
            'return_url'   => config('pzgate.return_url'),
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])->post($this->baseUrl . '/payments/card', $payload);

            Log::info('PZGate initiateCard', [
                'payload'  => array_merge($payload, ['card' => '***masqué***']),
                'response' => $response->json(),
            ]);

            return $response->json();

        } catch (\Exception $e) {
            Log::error('PZGate card error', ['message' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Vérifie la signature d'un webhook PZGate
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $expected = hash_hmac('sha256', $payload, $this->secretKey);
        return hash_equals($expected, $signature);
    }

    /**
     * Vérifie le statut d'une transaction
     */
    public function checkTransaction(string $transactionId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept'        => 'application/json',
            ])->get($this->baseUrl . '/payments/' . $transactionId);

            return $response->json();

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
