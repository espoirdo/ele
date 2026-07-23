<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PremiumPayment;
use Illuminate\Http\Request;

class AdminPremiumController extends Controller
{
    /**
     * Display Premium payments list
     */
    public function payments(Request $request)
    {
        $payments = PremiumPayment::with(['user', 'event'])
            ->when($request->statut, function ($query, $statut) {
                return $query->where('statut', $statut);
            })
            ->when($request->event_id, function ($query, $eventId) {
                return $query->where('event_id', $eventId);
            })
            ->latest()
            ->paginate(20);

        // Stats
        $stats = [
            'total' => PremiumPayment::where('statut', 'confirme')->sum('total'),
            'this_month' => PremiumPayment::where('statut', 'confirme')
                ->whereMonth('created_at', now()->month)
                ->sum('total'),
            'pending' => PremiumPayment::where('statut', 'en_attente')->count(),
        ];

        return view('admin.premium.payments', compact('payments', 'stats', 'request'));
    }

    /**
     * Display a single Premium payment
     */
    public function show(PremiumPayment $payment)
    {
        $payment->load('user', 'event');
        return view('admin.premium.show', compact('payment'));
    }

    /**
     * Manually confirm a pending Premium payment
     */
    public function confirmPayment(PremiumPayment $payment)
    {
        if ($payment->statut !== 'en_attente') {
            return back()->with('error', 'Ce paiement n\'est pas en attente.');
        }

        // Update payment status
        $payment->update(['statut' => 'confirme']);

        // Activate premium options on the event
        $event = $payment->event;
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

        return back()->with('success', 'Paiement premium confirmé et options activées pour l\'événement.');
    }

    /**
     * Cancel a Premium payment
     */
    public function cancelPayment(PremiumPayment $payment)
    {
        if ($payment->statut !== 'en_attente') {
            return back()->with('error', 'Ce paiement ne peut pas être annulé.');
        }

        $payment->update(['statut' => 'annule']);

        return back()->with('success', 'Paiement premium annulé.');
    }
}
