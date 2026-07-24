<?php

namespace App\Http\Controllers;

use App\Mail\ParticipationConfirmee;
use App\Mail\PaymentSuccessMail;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\PzgateService;
use App\Services\PayGateService;
use App\Services\TicketGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WebhookController extends Controller
{
    /**
     * Webhook pour PayGateGlobal
     */
    public function paygate(Request $request)
    {
        $data = $request->all();

        Log::info('PayGate webhook reçu', $data);

        $identifier       = $data['identifier']        ?? null;
        $txReference     = $data['tx_reference']      ?? null;
        $paymentReference = $data['payment_reference'] ?? null;
        $amount          = $data['amount']           ?? null;

        if (!$identifier) {
            Log::warning('PayGate webhook: identifier manquant');
            return response()->json(['error' => 'identifier manquant'], 400);
        }

        $booking = Booking::where('paygate_identifier', $identifier)->first();

        if (!$booking) {
            Log::warning('PayGate webhook: booking non trouvé', ['identifier' => $identifier]);
            return response()->json(['error' => 'Booking non trouvé'], 404);
        }

        // Évite le double traitement
        if ($booking->status === 'confirmee') {
            return response()->json(['message' => 'Déjà confirmé'], 200);
        }

        // Le webhook de PayGate est envoyé uniquement en cas de succès
        // Vérifie que le montant correspond pour éviter la fraude
        if ((int)$amount !== (int)$booking->total) {
            Log::warning('PayGate webhook: montant incorrect', [
                'attendu' => $booking->total,
                'reçu'    => $amount,
            ]);
            return response()->json(['error' => 'Montant incorrect'], 400);
        }

        // Confirme le booking
        $booking->update([
            'status'                    => 'confirmee',
            'paygate_tx_reference'      => $txReference,
            'paygate_payment_reference' => $paymentReference,
            'paygate_status'           => 0,
            'paygate_response'         => $data,
        ]);

        // Envoie le billet par email
        try {
            Mail::to($booking->user->email)->send(new ParticipationConfirmee($booking));
            Log::info('Email billet envoyé', ['booking_id' => $booking->id]);
        } catch (\Exception $e) {
            Log::error('Erreur email billet', ['error' => $e->getMessage()]);
        }

        return response()->json(['message' => 'Paiement confirmé'], 200);
    }

    /**
     * Webhook pour PZGate (garde la compatibilité)
     */
    public function pzgate(Request $request, PzgateService $pzgate)
    {
        $payload   = $request->getContent();
        $signature = $request->header('X-PZGate-Signature', '');

        // Vérifie la signature pour sécuriser le webhook
        if (!$pzgate->verifyWebhookSignature($payload, $signature)) {
            Log::warning('PZGate webhook signature invalide', [
                'signature' => $signature,
                'ip'        => $request->ip(),
            ]);
            return response()->json(['error' => 'Signature invalide'], 401);
        }

        $data = $request->json()->all();

        Log::info('PZGate webhook reçu', $data);

        $reference = $data['reference'] ?? null;
        $status    = $data['status']    ?? null;

        if (!$reference || !$status) {
            return response()->json(['error' => 'Données manquantes'], 400);
        }

        // Trouve le booking correspondant
        $booking = Booking::where('pzgate_reference', $reference)->first();

        if (!$booking) {
            Log::warning('PZGate webhook: booking non trouvé', ['reference' => $reference]);
            return response()->json(['error' => 'Booking non trouvé'], 404);
        }

        // Évite le double traitement
        if ($booking->status === 'confirmee') {
            return response()->json(['message' => 'Déjà traité'], 200);
        }

        // Met à jour le statut selon la réponse PZGate
        if (in_array(strtolower($status), ['success', 'successful', 'completed'])) {

            $booking->update([
                'status'         => 'confirmee',
                'pzgate_status'  => $status,
                'pzgate_response'=> $data,
            ]);

            // Update payment status
            $payment = $booking->payment;
            if ($payment) {
                $payment->update([
                    'statut' => 'success',
                ]);
            }

            // Generate ticket
            try {
                $ticketGenerator = new TicketGeneratorService();
                $ticketGenerator->generateTicket($booking);
            } catch (\Exception $e) {
                Log::error('Erreur génération ticket', ['error' => $e->getMessage()]);
            }

            // Send confirmation email
            try {
                Mail::to($booking->user->email)
                    ->send(new ParticipationConfirmee($booking));
            } catch (\Exception $e) {
                Log::error('Erreur envoi email billet', ['error' => $e->getMessage()]);
            }

            Log::info('Paiement confirmé via PZGate', [
                'booking_id' => $booking->id,
                'reference'  => $reference,
            ]);

        } elseif (in_array(strtolower($status), ['failed', 'cancelled', 'rejected'])) {

            $booking->update([
                'status'          => 'annulee',
                'pzgate_status'   => $status,
                'pzgate_response' => $data,
            ]);

            // Update payment status
            $payment = $booking->payment;
            if ($payment) {
                $payment->update([
                    'statut' => 'failed',
                ]);
            }

            Log::info('Paiement échoué via PZGate', [
                'booking_id' => $booking->id,
                'reference'  => $reference,
            ]);
        }

        return response()->json(['message' => 'Webhook traité'], 200);
    }
}
