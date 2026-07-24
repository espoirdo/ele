<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayGateService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('paygate.api_key');
        $this->baseUrl = config('paygate.base_url');
    }

    /**
     * Initie un paiement TMoney ou Flooz
     * Retourne ['success' => bool, 'tx_reference' => string, 'status' => int, 'message' => string]
     */
    public function initiatePaiement(
        string $phoneNumber,
        int    $amount,
        string $identifier,
        string $network,
        string $description = ''
    ): array {
        $payload = [
            'auth_token'   => $this->apiKey,
            'phone_number' => $phoneNumber,
            'amount'       => $amount,
            'identifier'   => $identifier,
            'network'      => strtoupper($network), // 'TMONEY' ou 'FLOOZ'
            'description'  => $description,
        ];

        try {
            $response = Http::timeout(30)
                ->post($this->baseUrl . '/api/v1/pay', $payload);

            $data = $response->json();

            Log::info('PayGate initiatePaiement', [
                'identifier' => $identifier,
                'network'    => $network,
                'amount'     => $amount,
                'response'   => $data,
            ]);

            // status 0 = transaction enregistrée avec succès
            $success = isset($data['status']) && $data['status'] === 0;

            return [
                'success'      => $success,
                'tx_reference' => $data['tx_reference'] ?? null,
                'status'       => $data['status'] ?? null,
                'message'      => $this->getStatusMessage($data['status'] ?? -1),
                'raw'          => $data,
            ];

        } catch (\Exception $e) {
            Log::error('PayGate erreur initiatePaiement', [
                'identifier' => $identifier,
                'error'      => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Erreur de connexion au service de paiement.',
                'raw'     => [],
            ];
        }
    }

    /**
     * Vérifie le statut d'une transaction par identifier (ta référence interne)
     */
    public function verifierStatut(string $identifier): array
    {
        try {
            $response = Http::timeout(15)
                ->post($this->baseUrl . '/api/v2/status', [
                    'auth_token' => $this->apiKey,
                    'identifier' => $identifier,
                ]);

            $data = $response->json();

            Log::info('PayGate verifierStatut', [
                'identifier' => $identifier,
                'response'   => $data,
            ]);

            return $data;

        } catch (\Exception $e) {
            Log::error('PayGate erreur verifierStatut', ['error' => $e->getMessage()]);
            return ['status' => -1];
        }
    }

    /**
     * Traduit le code status PayGate en message lisible
     */
    public function getStatusMessage(int $status): string
    {
        return match($status) {
            0  => 'Transaction enregistrée avec succès.',
            1  => 'Paramètres invalides.',
            2  => 'Jeton d\'authentification invalide.',
            4  => 'Paramètres invalides.',
            5  => 'Paramètres invalides.',
            6  => 'Une transaction avec cet identifiant existe déjà.',
            -1 => 'Erreur inconnue.',
            default => 'Statut inconnu : ' . $status,
        };
    }

    /**
     * Traduit le code status de VÉRIFICATION en message lisible
     */
    public function getPaymentStatusMessage(int $status): string
    {
        return match($status) {
            0 => 'Paiement réussi.',
            2 => 'Paiement en cours.',
            4 => 'Paiement expiré.',
            6 => 'Paiement annulé.',
            default => 'Statut inconnu.',
        };
    }

    /**
     * Vérifie si un statut de paiement correspond à un succès
     */
    public function isPaymentSuccessful(int $status): bool
    {
        return $status === 0;
    }
}
