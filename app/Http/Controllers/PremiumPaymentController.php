<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PremiumPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PremiumPaymentController extends Controller
{
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

        // Create premium payment record
        $payment = PremiumPayment::create([
            'event_id'       => $event->id,
            'user_id'        => Auth::id(),
            'options'        => json_encode($premiumPayment['options']),
            'total'          => $premiumPayment['total'],
            'moyen_paiement' => $validated['methode'],
            'statut'         => 'confirme', // Direct confirmation for demo
        ]);

        // Activate premium options on the event
        foreach ($premiumPayment['options'] as $option) {
            $columnMap = [
                'mise_en_avant'   => 'premium_mise_en_avant',
                'newsletter'      => 'premium_newsletter',
                'reseaux_sociaux' => 'premium_reseaux_sociaux',
            ];

            if (isset($columnMap[$option])) {
                $event->update([$columnMap[$option] => true]);
            }
        }

        // Clear session
        session()->forget('premium_payment');

        return redirect()->route('events.show', $event->slug)
            ->with('success', 'Options premium activées ! Votre événement est maintenant mis en avant.');
    }
}
