<?php

namespace App\Http\Controllers;

use App\Models\VipPayment;
use App\Services\CinetPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VipController extends Controller
{
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
        $vipAdvantagesText = setting('vip_advantages_text', 'Accédez à la Marketplace exclusive, obtenez un badge VIP et bien plus encore!');

        return view('vip.subscribe', compact('vipPrice', 'vipDuration', 'vipPageTitle', 'vipAdvantagesText'));
    }

    /**
     * Process VIP subscription payment
     */
    public function process(Request $request, CinetPayService $cinetPayService)
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

        // Generate transaction ID
        $transactionId = 'eledji_vip_' . strtoupper(uniqid()) . '_' . now()->timestamp;

        // Create VIP payment record
        $vipPayment = VipPayment::create([
            'user_id' => Auth::id(),
            'montant' => $vipPrice,
            'methode' => $validated['methode'],
            'statut' => 'en_attente',
            'transaction_id' => $transactionId,
        ]);

        // Initiate CinetPay payment
        $cinetPayResponse = $cinetPayService->createPayment([
            'transaction_id' => $transactionId,
            'amount' => $vipPrice,
            'currency' => 'XOF',
            'description' => 'Abonnement VIP Eledji - ' . $vipDuration . ' jours',
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'return_url' => route('vip.callback') . '?transaction_id=' . $transactionId,
            'notify_url' => route('vip.callback'),
        ]);

        Log::info('CinetPay VIP Response', $cinetPayResponse);

        // If CinetPay returns a payment URL, redirect to it
        if (isset($cinetPayResponse['data']['payment_url'])) {
            return redirect($cinetPayResponse['data']['payment_url']);
        }

        // If there's a checkout URL (alternative)
        if (isset($cinetPayResponse['checkout_url'])) {
            return redirect($cinetPayResponse['checkout_url']);
        }

        // Fallback: Mark as failed
        $vipPayment->update(['statut' => 'echoue']);

        return back()->with('error', 'Erreur lors de l\'initialisation du paiement. Veuillez réessayer.');
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