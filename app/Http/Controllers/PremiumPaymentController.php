<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PremiumPayment;
use App\Services\PzgateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PremiumPaymentController extends Controller
{
    public function __construct(private PzgateService $pzgate) {}

    /**
     * Display the premium payment page
     */
    public function show(Request $request)
    {
        $premiumPayment = session('premium_payment');

        if (!$premiumPayment) {
            return redirect()->route('home')->with('error', 'Aucune option premium à payer.');
        }

        $event = Event::findOrFail($premiumPayment['event_id']);

        // Prepare options with prices
        $prixOptions = [
            'mise_en_avant'   => setting('premium_mise_en_avant_prix', 5000),
            'newsletter'      => setting('premium_newsletter_prix', 3000),
            'reseaux_sociaux' => setting('premium_reseaux_prix', 2000),
        ];

        $optionsDetails = [];
        foreach ($premiumPayment['options'] as $option) {
            $labels = [
                'mise_en_avant'   => 'Mise en avant page d\'accueil',
                'newsletter'      => 'Publication newsletter',
                'reseaux_sociaux' => 'Partage réseaux sociaux',
            ];
            $optionsDetails[] = [
                'key'    => $option,
                'label'  => $labels[$option] ?? $option,
                'prix'   => $prixOptions[$option] ?? 0,
            ];
        }

        return view('events.premium-payment', compact('event', 'optionsDetails', 'premiumPayment'));
    }

    /**
     * Process the premium payment
     */
    public function process(Request $request)
    {
        $premiumPayment = session('premium_payment');

        if (!$premiumPayment) {
            return redirect()->route('home')->with('error', 'Aucune option premium à payer.');
        }

        $validated = $request->validate([
            'methode' => 'required|in:tmoney,flooz,carte',
        ]);

        // Validate based on payment method
        if ($validated['methode'] === 'carte') {
            $request->validate([
                'numero_carte' => 'required|string|min:16',
                'expiration' => 'required|string',
                'cvv' => 'required|string|min:3',
                'nom_titulaire' => 'required|string',
            ]);
        } else {
            $request->validate([
                'telephone' => 'required|regex:/^[0-9]{8}$/',
            ]);
        }

        $event = Event::findOrFail($premiumPayment['event_id']);

        // Generate unique reference for PZGate
        $reference = 'ELD-PREM-' . strtoupper(Str::random(8)) . '-' . time();

        // Create premium payment record
        $payment = PremiumPayment::create([
            'event_id'        => $event->id,
            'user_id'         => Auth::id(),
            'options'         => json_encode($premiumPayment['options']),
            'total'           => $premiumPayment['total'],
            'moyen_paiement'  => $validated['methode'],
            'statut'          => 'en_attente',
            'transaction_id'  => $reference,
            'pzgate_reference' => $reference,
        ]);

        $optionsLabels = implode(', ', $premiumPayment['options']);
        $description = "Options Premium - {$event->titre} ({$optionsLabels})";

        // Initiate PZGate payment based on method
        if (in_array($validated['methode'], ['tmoney', 'flooz'])) {
            // Mobile money payment
            $provider = $validated['methode'] === 'tmoney' ? 'TMONEY' : 'FLOOZ';

            $result = $this->pzgate->initiateMobileMoney([
                'amount'      => $premiumPayment['total'],
                'phone'       => '+228' . $request->telephone,
                'provider'    => $provider,
                'reference'   => $reference,
                'description' => $description,
            ]);
        } else {
            // Card payment - use custom return_url for premium callback
            $returnUrl = route('premium.callback');

            $result = $this->pzgate->initiateCard([
                'amount'       => $premiumPayment['total'],
                'reference'    => $reference,
                'description' => $description,
                'card_number'  => str_replace(' ', '', $request->numero_carte),
                'card_expiry'  => $request->expiration,
                'card_cvv'     => $request->cvv,
                'card_holder'  => $request->nom_titulaire,
                'return_url'   => $returnUrl,
            ]);
        }

        Log::info('PZGate Premium Response', $result);

        // Update payment with PZGate response
        $payment->update([
            'pzgate_transaction_id' => $result['transaction_id'] ?? null,
            'pzgate_status'        => $result['status'] ?? 'pending',
            'pzgate_response'      => $result,
        ]);

        // Check if PZGate returned an immediate error
        if (isset($result['success']) && $result['success'] === false) {
            $payment->update(['statut' => 'annule']);
            session()->forget('premium_payment');
            return back()->withErrors([
                'paiement' => 'Le paiement a échoué : ' . ($result['message'] ?? 'Erreur inconnue')
            ])->withInput();
        }

        // If PZGate returns a payment URL (for card), redirect to it
        if (isset($result['data']['payment_url'])) {
            return redirect($result['data']['payment_url']);
        }

        // If there's a checkout URL (alternative)
        if (isset($result['checkout_url'])) {
            return redirect($result['checkout_url']);
        }

        // For mobile money, redirect to waiting page
        if (in_array($validated['methode'], ['tmoney', 'flooz'])) {
            return redirect()->route('premium.waiting', $payment->id)
                ->with('info', 'Votre paiement est en cours de traitement. Confirmez sur votre téléphone.');
        }

        // Fallback
        $payment->update(['statut' => 'annule']);
        session()->forget('premium_payment');
        return back()->with('error', 'Erreur lors de l\'initialisation du paiement. Veuillez réessayer.');
    }

    /**
     * Page d'attente pour paiement Premium mobile money
     */
    public function waiting(PremiumPayment $payment)
    {
        abort_if($payment->user_id !== Auth::id(), 403);
        return view('events.premium-waiting', compact('payment'));
    }

    /**
     * Vérifier le statut du paiement Premium (AJAX)
     */
    public function status(PremiumPayment $payment)
    {
        abort_if($payment->user_id !== Auth::id(), 403);
        return response()->json(['statut' => $payment->fresh()->statut]);
    }

    /**
     * Handle Premium payment callback (for card payments)
     */
    public function callback(Request $request)
    {
        $transactionId = $request->query('transaction_id');

        if (!$transactionId) {
            return redirect()->route('home')->with('error', 'Transaction invalide.');
        }

        $payment = PremiumPayment::where('transaction_id', $transactionId)->first();

        if (!$payment) {
            return redirect()->route('home')->with('error', 'Paiement premium non trouvé.');
        }

        $event = $payment->event;

        // Check if payment was successful
        $status = $request->query('status', 'FAILED');

        if ($status === 'SUCCESS' || $request->query('cpm_trans_status') === 'ACCEPTED') {
            // Mark payment as confirmed
            $payment->update(['statut' => 'confirme']);

            // Activate premium options on the event
            $options = is_array($payment->options) ? $payment->options : json_decode($payment->options, true);

            $columnMap = [
                'mise_en_avant'   => 'premium_mise_en_avant',
                'newsletter'      => 'premium_newsletter',
                'reseaux_sociaux' => 'premium_reseaux_sociaux',
            ];

            foreach ($options as $option) {
                if (isset($columnMap[$option])) {
                    $event->update([$columnMap[$option] => true]);
                }
            }

            return redirect()->route('events.show', $event->slug)
                ->with('success', 'Paiement confirmé! Vos options premium ont été activées.');
        }

        // Payment failed
        $payment->update(['statut' => 'annule']);

        return redirect()->route('events.show', $event->slug)
            ->with('error', 'Le paiement a échoué. Vos options premium n\'ont pas été activées.');
    }

    /**
     * Webhook pour confirmer le paiement Premium
     */
    public function webhook(Request $request, PzgateService $pzgate)
    {
        $payload   = $request->getContent();
        $signature = $request->header('X-PZGate-Signature', '');

        // Vérifie la signature
        if (!$pzgate->verifyWebhookSignature($payload, $signature)) {
            Log::warning('PZGate Premium webhook signature invalide', [
                'signature' => $signature,
                'ip'        => $request->ip(),
            ]);
            return response()->json(['error' => 'Signature invalide'], 401);
        }

        $data = $request->json()->all();

        Log::info('PZGate Premium webhook reçu', $data);

        $reference = $data['reference'] ?? null;
        $status    = $data['status']    ?? null;

        if (!$reference || !$status) {
            return response()->json(['error' => 'Données manquantes'], 400);
        }

        // Trouve le paiement Premium correspondant
        $payment = PremiumPayment::where('pzgate_reference', $reference)->first();

        if (!$payment) {
            Log::warning('PZGate Premium webhook: paiement non trouvé', ['reference' => $reference]);
            return response()->json(['error' => 'Paiement non trouvé'], 404);
        }

        // Évite le double traitement
        if ($payment->statut === 'confirme') {
            return response()->json(['message' => 'Déjà traité'], 200);
        }

        // Met à jour le statut
        if (in_array(strtolower($status), ['success', 'successful', 'completed'])) {
            $payment->update([
                'statut'         => 'confirme',
                'pzgate_status'  => $status,
                'pzgate_response'=> $data,
            ]);

            // Activate premium options on the event
            $event = $payment->event;
            $options = is_array($payment->options) ? $payment->options : json_decode($payment->options, true);

            foreach ($options as $option) {
                $columnMap = [
                    'mise_en_avant'   => 'premium_mise_en_avant',
                    'newsletter'      => 'premium_newsletter',
                    'reseaux_sociaux' => 'premium_reseaux_sociaux',
                ];

                if (isset($columnMap[$option])) {
                    $event->update([$columnMap[$option] => true]);
                }
            }

            Log::info('Premium activé via PZGate', [
                'payment_id' => $payment->id,
                'event_id'  => $event->id,
            ]);

        } elseif (in_array(strtolower($status), ['failed', 'cancelled', 'rejected'])) {
            $payment->update([
                'statut'          => 'annule',
                'pzgate_status'   => $status,
                'pzgate_response' => $data,
            ]);
        }

        return response()->json(['message' => 'Webhook traité'], 200);
    }
}
