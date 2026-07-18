<?php

namespace App\Http\Controllers;

use App\Mail\ParticipationConfirmee;
use App\Models\Booking;
use App\Models\Event;
use App\Services\TicketGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    /**
     * Show confirmation page for free event participation
     */
    public function confirmShow(Request $request, Event $event)
    {
        // Check if user already has a confirmed booking for this event
        $existingBooking = Booking::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->where('status', 'confirmee')
            ->first();

        if ($existingBooking) {
            return redirect()->route('events.show', ['event' => $event->slug])
                ->with('info', 'Vous participez deja a cet evenement.');
        }

        // Get active tickets for the event
        $ticketsActifs = $event->tickets_actifs;

        // If no tickets are active, it's a free event
        if (empty($ticketsActifs)) {
            return view('booking.confirm', compact('event'));
        }

        // Show ticket selection
        return view('booking.confirm', compact('event', 'ticketsActifs'));
    }

    /**
     * Store the booking after confirmation
     */
    public function confirmStore(Request $request, Event $event, TicketGeneratorService $ticketGenerator)
    {
        // Get active tickets
        $ticketsActifs = $event->tickets_actifs;

        // If no tickets are active, validate as free event
        if (empty($ticketsActifs)) {
            $validated = $request->validate([]);
        } else {
            // Validate ticket type selection
            $validated = $request->validate([
                'type_billet' => 'required|in:classique,vip,vvip',
            ]);

            // Verify the selected ticket type is active for this event
            $type = $validated['type_billet'];
            if (!$event->{"billet_{$type}_actif"}) {
                return back()->with('error', 'Ce type de billet n\'est pas disponible pour cet evenement.');
            }
        }

        // Check if user already has a booking for this event
        $existingBooking = Booking::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->whereIn('status', ['confirmee', 'en_attente'])
            ->first();

        if ($existingBooking) {
            return redirect()->route('events.show', ['event' => $event->slug])
                ->with('error', 'Vous avez deja une reservation pour cet evenement.');
        }

        // Generate unique reservation number
        $numeroReservation = 'ELD-' . strtoupper(uniqid());

        // Determine total based on ticket type
        $typeBillet = $validated['type_billet'] ?? null;
        $total = 0;

        if ($typeBillet && $event->{"billet_{$typeBillet}_actif"}) {
            $total = $event->{"billet_{$typeBillet}_prix"} ?? 0;
        }

        // Create the booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'type_billet' => $typeBillet,
            'total' => $total,
            'status' => 'confirmee',
            'numero_reservation' => $numeroReservation,
        ]);

        // Generate ticket PDF for free events
        if ($total == 0) {
            $ticketGenerator->generateTicket($booking);

            // Send confirmation email
            $user = Auth::user();
            Mail::to($user->email)->send(new ParticipationConfirmee($booking));
        }

        // Redirect to success page
        return redirect()->route('booking.success', $booking)
            ->with('success', 'Votre place est confirmee! Votre ticket est pret a telecharger.');
    }

    /**
     * Show success page with ticket
     */
    public function success(Booking $booking)
    {
        // Ensure the user owns this booking
        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('home');
        }

        $event = $booking->event;

        return view('booking.success', compact('booking', 'event'));
    }

    /**
     * Show user's bookings list
     */
    public function myBookings(Request $request)
    {
        $user = Auth::user();

        $query = Booking::with('event')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(10);

        return view('bookings.index', compact('bookings'));
    }
}