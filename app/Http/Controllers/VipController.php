<?php

namespace App\Http\Controllers;

use App\Models\VipPayment;
use App\Services\PzgateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VipController extends Controller
{
    public function __construct(private PzgateService $pzgate) {}

    /**
     * Display VIP subscription page
     */
    public function show(Request $request)
    {
        $user = $request->user();

        // If already VIP with active subscription, redirect to marketplace
        if ($user->isVip()) {
            return redirect()->route('marketplace.index');
        }

        $vipPrice = (int) setting('vip_price', 5000);
        $vipDuration = (int) setting('vip_duration_days', 30);
        $vipPageTitle = setting('vip_page_title', 'Devenez VIP Eledji');
        $vipAdvantagesText = setting('vip_advantages_text', 'Accédez à la Marketplace exclusive, obtenir un badge VIP et bien plus encore!');

        return view('vip.subscribe', compact('vipPrice', 'vipDuration', 'vipPageTitle', 'vipAdvantagesText'));
    }

    /**
     * Process VIP subscription payment
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'methode' => 'required|in:tmoney,flooz,carte',
        ]);

        $user = $request->user();

        // If already VIP, just extend
        if ($user->isVip()) {
            return redirect()->route('marketplace.index');
        }

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

        $vipPrice = (int) setting('vip_price', 5000);
        $vipDuration = (int) setting('vip_duration_days', 30);

        // Generate unique reference for PZGate
        $reference = 'ELD-VIP-' . strtoupper(Str::random(8)) . '-' . time();

        // Create VIP payment record
        $vipPayment = VipPayment::create([
            'user_id' => Auth::id(),
            'montant' => $vipPrice,
            'methode' => $validated['methode'],
            'statut' => 'en_attente',
            'transaction_id' => $reference,
            'pzgate_reference' => $reference,
        ]);

        $description = "Abonnement VIP Eledji - {$vipDuration} jours";

        // Initiate PZGate payment based on method
        if (in_array($validated['methode'], ['tmoney', 'flooz'])) {
            // Mobile money payment
            $provider = $validated['methode'] === 'tmoney' ? 'TMONEY' : 'FLOOZ';

            $result = $this->pzgate->initiateMobileMoney([
                'amount'      => $vipPrice,
                'phone'       => '+228' . $request->telephone,
                'provider'    => $provider,
                'reference'   => $reference,
                'description' => $description,
            ]);
        } else {
            // Card payment
            $result = $this->pzgate->initiateCard([
                'amount'       => $vipPrice,
                'reference'    => $reference,
                'description'  => $description,
                'card_number'  => str_replace(' ', '', $request->numero_carte),
                'card_expiry'  => $request->expiration,
                'card_cvv'     => $request->cvv,
                'card_holder'  => $request->nom_titulaire,
            ]);
        }

        Log::info('PZGate VIP Response', $result);

        // Update VIP payment with PZGate response
        $vipPayment->update([
            'pzgate_transaction_id' => $result['transaction_id'] ?? null,
            'pzgate_status'        => $result['status'] ?? 'pending',
            'pzgate_response'      => $result,
        ]);

        // Check if PZGate returned an immediate error
        if (isset($result['success']) && $result['success'] === false) {
            $vipPayment->update(['statut' => 'echoue']);
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
            return redirect()->route('vip.waiting', $vipPayment->id)
                ->with('info', 'Votre paiement est en cours de traitement. Confirmez sur votre téléphone.');
        }

        // Fallback
        $vipPayment->update(['statut' => 'echoue']);
        return back()->with('error', 'Erreur lors de l\'initialisation du paiement. Veuillez réessayer.');
    }

    /**
     * Page d'attente pour paiement VIP mobile money
     */
    public function waiting(VipPayment $vipPayment)
    {
        abort_if($vipPayment->user_id !== Auth::id(), 403);
        return view('vip.waiting', compact('vipPayment'));
    }

    /**
     * Vérifier le statut du paiement VIP (AJAX)
     */
    public function status(VipPayment $vipPayment)
    {
        abort_if($vipPayment->user_id !== Auth::id(), 403);
        return response()->json(['statut' => $vipPayment->fresh()->statut]);
    }

    /**
     * Handle VIP payment callback
     */
    public function callback(Request $request)
    {
        $transactionId = $request->query('transaction_id');

        if (!$transactionId) {
            return redirect()->route('user.profile')->with('error', 'Transaction invalide.');
        }

        $vipPayment = VipPayment::where('transaction_id', $transactionId)->first();

        if (!$vipPayment) {
            return redirect()->route('user.profile')->with('error', 'Paiement VIP non trouvé.');
        }

        $user = $vipPayment->user;

        // Check if payment was successful
        $status = $request->query('status', 'FAILED');

        if ($status === 'SUCCESS' || $request->query('cpm_trans_status') === 'ACCEPTED') {
            // Mark payment as successful
            $vipPayment->update(['statut' => 'confirme']);

            // Calculate VIP duration
            $vipDuration = (int) setting('vip_duration_days', 30);

            // Update user as VIP
            $user->update([
                'is_vip' => true,
                'vip_subscribed_at' => now(),
                'vip_expires_at' => now()->addDays($vipDuration),
            ]);

            return redirect()->route('user.profile')
                ->with('success', 'Bienvenue dans le club VIP Eledji! Vous avez maintenant accès à la Marketplace exclusive.');
        }

        // Payment failed
        $vipPayment->update(['statut' => 'echoue']);

        return redirect()->route('vip.subscribe.show')
            ->with('error', 'Le paiement a échoué. Votre abonnement VIP n\'a pas été activé.');
    }

    /**
     * Webhook pour confirmer le paiement VIP
     */
    public function webhook(Request $request, PzgateService $pzgate)
    {
        $payload   = $request->getContent();
        $signature = $request->header('X-PZGate-Signature', '');

        // Vérifie la signature
        if (!$pzgate->verifyWebhookSignature($payload, $signature)) {
            Log::warning('PZGate VIP webhook signature invalide', [
                'signature' => $signature,
                'ip'        => $request->ip(),
            ]);
            return response()->json(['error' => 'Signature invalide'], 401);
        }

        $data = $request->json()->all();

        Log::info('PZGate VIP webhook reçu', $data);

        $reference = $data['reference'] ?? null;
        $status    = $data['status']    ?? null;

        if (!$reference || !$status) {
            return response()->json(['error' => 'Données manquantes'], 400);
        }

        // Trouve le paiement VIP correspondant
        $vipPayment = VipPayment::where('pzgate_reference', $reference)->first();

        if (!$vipPayment) {
            Log::warning('PZGate VIP webhook: paiement non trouvé', ['reference' => $reference]);
            return response()->json(['error' => 'Paiement non trouvé'], 404);
        }

        // Évite le double traitement
        if ($vipPayment->statut === 'confirme') {
            return response()->json(['message' => 'Déjà traité'], 200);
        }

        // Met à jour le statut
        if (in_array(strtolower($status), ['success', 'successful', 'completed'])) {
            $vipPayment->update([
                'statut'         => 'confirme',
                'pzgate_status'  => $status,
                'pzgate_response'=> $data,
            ]);

            // Activate VIP
            $vipDuration = (int) setting('vip_duration_days', 30);
            $user = $vipPayment->user;

            $user->update([
                'is_vip' => true,
                'vip_subscribed_at' => now(),
                'vip_expires_at' => now()->addDays($vipDuration),
            ]);

            Log::info('VIP activé via PZGate', [
                'vip_payment_id' => $vipPayment->id,
                'user_id'       => $user->id,
            ]);

        } elseif (in_array(strtolower($status), ['failed', 'cancelled', 'rejected'])) {
            $vipPayment->update([
                'statut'          => 'echoue',
                'pzgate_status'   => $status,
                'pzgate_response' => $data,
            ]);
        }

        return response()->json(['message' => 'Webhook traité'], 200);
    }

    /**
     * Activate VIP manually (admin or free upgrade)
     */
    public function activateManually(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $targetUser = \App\Models\User::findOrFail($request->user_id);
        $vipDuration = (int) setting('vip_duration_days', 30);

        // If already VIP, extend the subscription
        $newExpiresAt = $targetUser->isVip()
            ? $targetUser->vip_expires_at->addDays($vipDuration)
            : now()->addDays($vipDuration);

        $targetUser->update([
            'is_vip' => true,
            'vip_subscribed_at' => $targetUser->isVip() ? $targetUser->vip_subscribed_at : now(),
            'vip_expires_at' => $newExpiresAt,
        ]);

        return back()->with('success', 'VIP activé pour ' . $targetUser->name);
    }

    /**
     * Revoke VIP manually
     */
    public function revoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $targetUser = \App\Models\User::findOrFail($request->user_id);

        $targetUser->update([
            'is_vip' => false,
            'vip_expires_at' => null,
        ]);

        return back()->with('success', 'VIP révoqué pour ' . $targetUser->name);
    }
}
