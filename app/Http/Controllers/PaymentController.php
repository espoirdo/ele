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
        // If free event AND no tickets, redirect to booking (free participation)
        // But if event has tickets, allow payment even if main price is 0
        if ($event->est_gratuit && $event->tickets->count() === 0 && $event->prix == 0) {
            return redirect()->route('booking.confirm.show', $event->slug);
        }

        // Get ticket_id from query string if provided
        $ticketId = $request->query('ticket_id');
        $selectedTicket = null;

        if ($ticketId) {
            $selectedTicket = $event->tickets->find($ticketId);
        }

        // Calculate price based on selected ticket or event price
        $price = 0;
        if ($selectedTicket) {
            $price = $selectedTicket->prix;
        } elseif ($event->tickets->count() > 0) {
            // Use the first ticket's price as default
            $price = $event->tickets->first()->prix ?? ($event->prix ?? 0);
        } else {
            $price = $event->prix ?? 0;
        }

        // If still free and no tickets, redirect to booking
        if ($price == 0 && $event->tickets->count() === 0) {
            return redirect()->route('booking.confirm.show', $event->slug);
        }

        return view('payment.show', compact('event', 'price', 'selectedTicket'));
    }

    /**
     * Process the payment - User selected payment method (TMoney/Flooz/Carte) and phone or card
     */
    public function process(Request $request, Event $event, CinetPayService $cinetPayService)
    {
        // Validate input
        $validated = $request->validate([
            'methode' => 'required|in:tmoney,flooz,carte',
            'nb_places' => 'nullable|integer|min:1|max:5',
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

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Calculate price
        $nbPlaces = $validated['nb_places'] ?? 1;
        $totalPrice = ($event->prix ?? 0) * $nbPlaces;

        if ($totalPrice <= 0) {
            return redirect()->route('booking.confirm.show', $event->slug);
        }

        // Check available places
        if ($event->nb_places < $nbPlaces) {
            return back()->with('error', 'Il ne reste que ' . $event->nb_places . ' places disponibles.');
        }

        // Check if user already has a booking for this event
        $existingBooking = Booking::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->whereIn('status', ['confirmee', 'en_attente'])
            ->first();

        if ($existingBooking) {
            return back()->with('error', 'Vous avez déjà une réservation pour cet événement.');
        }

        // Generate transaction ID and reservation number
        $transactionId = 'eledji_' . strtoupper(uniqid()) . '_' . now()->timestamp;
        $numeroReservation = 'ELD-' . strtoupper(uniqid());

        // Create booking with status 'en_attente' (pending)
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'nb_places' => $nbPlaces,
            'total' => $totalPrice,
            'status' => 'en_attente',
            'numero_reservation' => $numeroReservation,
        ]);

        // Decrement available places
        $event->decrement('nb_places', $nbPlaces);

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
        $event->increment('nb_places', $nbPlaces);
        $booking->delete();

        return back()->with('error', 'Erreur lors de l\'initialisation du paiement. Veuillez réessayer.');
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
            return redirect()->route('home')->with('error', 'Paiement non trouvé.');
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
                ->with('success', 'Paiement réussi! Votre ticket a été généré.');
        }

        // Payment failed
        $payment->update(['statut' => 'failed']);
        $booking->update(['status' => 'annulee']);

        // Restore available places
        $booking->event->increment('nb_places', $booking->nb_places);

        return redirect()->route('events.show', $booking->event)
            ->with('error', 'Le paiement a échoué. Votre réservation a été annulée.');
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
                ->with('error', 'Vous ne pouvez télécharger le ticket qu\'une fois la réservation confirmée.');
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