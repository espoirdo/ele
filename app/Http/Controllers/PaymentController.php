<?php

namespace App\Http\Controllers;

use App\Mail\ParticipationConfirmee;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Payment;
use App\Services\PayGateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(private PayGateService $paygate) {}

    public function show(string $slug, Request $request)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        $billets = [];
        if ($event->billet_classique_actif) $billets['classique'] = $event->billet_classique_prix;
        if ($event->billet_vip_actif)       $billets['vip']       = $event->billet_vip_prix;
        if ($event->billet_vvip_actif)      $billets['vvip']      = $event->billet_vvip_prix;

        // Get selected ticket type from query string or default to classique
        $typeBillet = $request->query('type_billet', 'classique');
        if (!isset($billets[$typeBillet])) {
            $typeBillet = array_key_first($billets) ?: 'classique';
        }
        $price = $billets[$typeBillet] ?? 0;

        return view('payment.show', compact('event', 'billets', 'typeBillet', 'price'));
    }

    public function process(Request $request, string $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        $request->validate([
            'moyen_paiement' => 'required|in:tmoney,flooz',
            'type_billet'    => 'required|in:classique,vip,vvip',
            'telephone'      => ['required', 'digits:8'],
        ], [
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'telephone.digits'   => 'Le numéro doit contenir exactement 8 chiffres.',
        ]);

        $typeBillet = $request->type_billet;
        $montant    = (int) ($event->{"billet_{$typeBillet}_prix"} ?? 0);
        $network    = strtoupper($request->moyen_paiement); // TMONEY ou FLOOZ

        // Génère un identifier unique pour cette transaction
        $identifier = 'ELD-' . strtoupper(Str::random(8)) . '-' . time();

        // Crée le booking en attente AVANT d'appeler PayGate
        $booking = Booking::create([
            'user_id'              => auth()->id(),
            'event_id'             => $event->id,
            'type_billet'          => $typeBillet,
            'total'                => $montant,
            'status'               => 'en_attente',
            'moyen_paiement'       => $request->moyen_paiement,
            'numero_reservation'   => 'ELD-' . strtoupper(uniqid()),
            'paygate_identifier'   => $identifier,
        ]);

        // Appel API PayGateGlobal
        $result = $this->paygate->initiatePaiement(
            phoneNumber: $request->telephone,
            amount:      $montant,
            identifier:  $identifier,
            network:     $network,
            description: "Billet {$typeBillet} - {$event->titre}"
        );

        // Enregistre la réponse PayGate sur le booking
        $booking->update([
            'paygate_tx_reference' => $result['tx_reference'] ?? null,
            'paygate_status'       => $result['status']       ?? null,
            'paygate_response'     => $result['raw']          ?? [],
        ]);

        // Si erreur immédiate (mauvais token, doublon, etc.)
        if (!$result['success']) {
            $booking->update(['status' => 'annule']);
            return back()->withErrors([
                'paiement' => 'Erreur : ' . $result['message']
            ])->withInput();
        }

        // Succès : transaction enregistrée chez PayGate, en attente confirmation téléphone
        return redirect()->route('payment.waiting', $booking->id)
            ->with('info', 'Demande envoyée ! Confirmez sur votre téléphone ' . $request->telephone . '.');
    }

    public function waiting(Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        return view('payment.waiting', compact('booking'));
    }

    public function checkStatus(Booking $booking, PayGateService $paygate)
    {
        abort_if($booking->user_id !== auth()->id(), 403);

        if ($booking->status === 'confirmee') {
            return response()->json(['statut' => 'confirmee', 'redirect' => route('booking.success', $booking->id)]);
        }

        if ($booking->status === 'annulee') {
            return response()->json(['statut' => 'annulee']);
        }

        // Interroge PayGate pour le statut actuel
        if ($booking->paygate_identifier) {
            $result = $paygate->verifierStatut($booking->paygate_identifier);
            $status = $result['status'] ?? -1;

            if ($paygate->isPaymentSuccessful($status)) {
                // Paiement confirmé par PayGate
                $booking->update([
                    'status'                    => 'confirmee',
                    'paygate_status'            => $status,
                    'paygate_payment_reference' => $result['payment_reference'] ?? null,
                    'paygate_response'          => $result,
                ]);

                // Enregistre aussi dans la table `payments` pour la cohérence (dashboard admin)
                Payment::updateOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'user_id'        => $booking->user_id,
                        'event_id'       => $booking->event_id,
                        'transaction_id' => $booking->paygate_identifier ?? $booking->paygate_tx_reference,
                        'montant'        => $booking->total,
                        'type'           => 'ticket',
                        'statut'         => 'success',
                        'methode'        => $booking->moyen_paiement,
                    ]
                );

                // Envoie le billet par email
                try {
                    Mail::to($booking->user->email)->send(new ParticipationConfirmee($booking));
                } catch (\Exception $e) {}

                return response()->json([
                    'statut'   => 'confirmee',
                    'redirect' => route('booking.success', $booking->id)
                ]);
            }

            if (in_array($status, [4, 6])) { // Expiré ou annulé
                $booking->update(['status' => 'annulee', 'paygate_status' => $status]);
                return response()->json(['statut' => 'annulee']);
            }
        }

        return response()->json(['statut' => 'en_attente']);
    }
}
