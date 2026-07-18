<?php

namespace App\Http\Controllers;

use App\Mail\PaymentSuccessMail;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Ticket;
use App\Services\CinetPayService;
use App\Services\TicketGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    /**
     * Display payment method selection page
     */
    public function show(Request $request, Event $event)
    {
        // Get ticket types from event
        $ticketsActifs = $event->tickets_actifs;

        // If free event AND no active ticket types, redirect to booking (free participation)
        if ($event->est_gratuit && empty($ticketsActifs)) {
            return redirect()->route('booking.confirm.show', $event->slug);
        }

        // Get type_billet from query string if provided
        $typeBillet = $request->query('type_billet');
        $selectedTicket = null;
        $price = 0;

        if ($typeBillet && in_array($typeBillet, ['classique', 'vip', 'vvip'])) {
            if ($event->{"billet_{$typeBillet}_actif"}) {
                $price = $event->{"billet_{$typeBillet}_prix"} ?? 0;
            }
        }

        // If still free, redirect to booking
        if ($price == 0 && empty($ticketsActifs)) {
            return redirect()->route('booking.confirm.show', $event->slug);
        }

        return view('payment.show', compact('event', 'price', 'typeBillet', 'ticketsActifs'));
    }

    /**
     * Process the payment - User selected payment method (TMoney/Flooz/Carte) and phone or card
     */
    public function process(Request $request, Event $event, CinetPayService $cinetPayService)
    {
        // Get active tickets
        $ticketsActifs = $event->tickets_actifs;

        // Validate input
        $rules = [
            'methode' => 'required|in:tmoney,flooz,carte',
        ];

        // If there are active tickets, require type_billet
        if (!empty($ticketsActifs)) {
            $rules['type_billet'] = 'required|in:classique,vip,vvip';
        }

        $validated = $request->validate($rules);

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

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Calculate price based on ticket type
        $typeBillet = $validated['type_billet'] ?? null;
        $totalPrice = 0;

        if ($typeBillet && $event->{"billet_{$typeBillet}_actif"}) {
            $totalPrice = $event->{"billet_{$typeBillet}_prix"} ?? 0;
        }

        if ($totalPrice <= 0) {
            return redirect()->route('booking.confirm.show', $event->slug);
        }

        // Check if user already has a booking for this event
        $existingBooking = Booking::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->whereIn('status', ['confirmee', 'en_attente'])
            ->first();

        if ($existingBooking) {
            return back()->with('error', 'Vous avez deja une reservation pour cet evenement.');
        }

        // Generate transaction ID and reservation number
        $transactionId = 'eledji_' . strtoupper(uniqid()) . '_' . now()->timestamp;
        $numeroReservation = 'ELD-' . strtoupper(uniqid());

        // Create booking with status 'en_attente' (pending)
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'type_billet' => $typeBillet,
            'total' => $totalPrice,
            'status' => 'en_attente',
            'numero_reservation' => $numeroReservation,
        ]);

        // Create payment record
        $payment = Payment::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'booking_id' => $booking->id,
            'transaction_id' => $transactionId,
            'montant' => (int)$totalPrice,
            'type' => 'ticket',
            'statut' => 'pending',
            'methode' => $validated['methode'],
        ]);

        // Initiate CinetPay payment
        $cinetPayResponse = $cinetPayService->createPayment([
            'transaction_id' => $transactionId,
            'amount' => (int)$totalPrice,
            'currency' => 'XOF',
            'description' => 'Paiement pour: ' . $event->titre,
            'customer_name' => Auth::user()->name,
            'customer_email' => Auth::user()->email,
            'return_url' => route('payment.callback') . '?transaction_id=' . $transactionId,
            'notify_url' => route('payment.callback'),
        ]);

        // Log the response for debugging
        Log::info('CinetPay Response', $cinetPayResponse);

        // If CinetPay returns a payment URL, redirect to it
        if (isset($cinetPayResponse['data']['payment_url'])) {
            return redirect($cinetPayResponse['data']['payment_url']);
        }

        // If there's a checkout URL (alternative)
        if (isset($cinetPayResponse['checkout_url'])) {
            return redirect($cinetPayResponse['checkout_url']);
        }

        // Fallback: Mark as failed and show error
        $payment->update(['statut' => 'failed']);
        $booking->delete();

        return back()->with('error', 'Erreur lors de l\'initialisation du paiement. Veuillez reessayer.');
    }

    /**
     * Handle payment callback from CinetPay
     */
    public function callback(Request $request)
    {
        $transactionId = $request->query('transaction_id');

        if (!$transactionId) {
            return redirect()->route('home')->with('error', 'Transaction invalide.');
        }

        $payment = Payment::where('transaction_id', $transactionId)->first();

        if (!$payment) {
            return redirect()->route('home')->with('error', 'Paiement non trouve.');
        }

        $booking = $payment->booking;

        // Check if payment was successful
        $status = $request->query('status', 'FAILED');

        if ($status === 'SUCCESS' || $request->query('cpm_trans_status') === 'ACCEPTED') {
            // Mark payment as successful
            $payment->update(['statut' => 'success']);

            // Mark booking as confirmed
            $booking->update(['status' => 'confirmee']);

            // Generate ticket
            $ticketGenerator = new TicketGeneratorService();
            $ticketGenerator->generateTicket($booking);

            // Send confirmation email
            Mail::to($booking->user->email)->send(new PaymentSuccessMail($payment));

            return redirect()->route('booking.success', $booking)
                ->with('success', 'Paiement reussi! Votre ticket a ete genere.');
        }

        // Payment failed
        $payment->update(['statut' => 'failed']);
        $booking->update(['status' => 'annulee']);

        return redirect()->route('events.show', $booking->event)
            ->with('error', 'Le paiement a echoue. Votre reservation a ete annulee.');
    }

    /**
     * Display payment confirmation page
     */
    public function confirmation(Booking $booking)
    {
        // Ensure the user owns this booking
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('home');
        }

        $event = $booking->event;
        $payment = $booking->payments()->first();

        $methode = session('payment_method', $payment?->methode ?? 'carte');
        $telephone = session('telephone');

        return view('payment.confirmation', compact('booking', 'event', 'payment', 'methode', 'telephone'));
    }

    /**
     * Download ticket HTML file
     */
    public function downloadTicket(Booking $booking, TicketGeneratorService $ticketGenerator)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('home');
        }

        // Only download confirmed bookings
        if ($booking->status !== 'confirmee') {
            return redirect()->route('booking.success', $booking)
                ->with('error', 'Vous ne pouvez telecharger le ticket qu\'une fois la reservation confirmee.');
        }

        $ticketPath = $ticketGenerator->getTicketPath($booking);

        if (!$ticketPath) {
            return redirect()->route('booking.success', $booking)
                ->with('error', 'Le ticket n\'est pas disponible pour le moment.');
        }

        $fullPath = storage_path('app/public/' . $ticketPath);
        $filename = $booking->numero_reservation . '.html';

        return response()->download($fullPath, $filename);
    }
}