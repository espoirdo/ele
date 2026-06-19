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
     * Store a new booking for a free event (legacy method)
     */
    public function store(Request $request, Event $event)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
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

        // Check if there are available places
        if ($event->nb_places <= 0) {
            return redirect()->route('events.show', ['event' => $event->slug])
                ->with('error', 'Cet evenement est complet.');
        }

        // Create the booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'nb_places' => 1,
            'total' => 0,
            'status' => 'confirmee',
        ]);

        // Decrement the number of available places
        $event->decrement('nb_places');

        return redirect()->route('booking.success', $booking)
            ->with('success', 'Votre place est confirmee! Votre ticket est pret a telecharger.');
    }

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

        // Check if there are available places
        if ($event->nb_places <= 0) {
            return redirect()->route('events.show', ['event' => $event->slug])
                ->with('error', 'Cet evenement est complet.');
        }

        return view('booking.confirm', compact('event'));
    }

    /**
     * Store the booking after confirmation
     */
    public function confirmStore(Request $request, Event $event, TicketGeneratorService $ticketGenerator)
    {
        // Validate the request
        $validated = $request->validate([
            'nb_places' => 'required|integer|min:1|max:5',
        ]);

        // Check if user already has a booking for this event
        $existingBooking = Booking::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->whereIn('status', ['confirmee', 'en_attente'])
            ->first();

        if ($existingBooking) {
            return redirect()->route('events.show', ['event' => $event->slug])
                ->with('error', 'Vous avez deja une reservation pour cet evenement.');
        }

        // Check if there are enough available places
        if ($event->nb_places < $validated['nb_places']) {
            return back()->with('error', 'Il ne reste que ' . $event->nb_places . ' places disponibles.');
        }

        // Generate unique reservation number
        $numeroReservation = 'ELD-' . strtoupper(uniqid());

        // Create the booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'nb_places' => $validated['nb_places'],
            'total' => 0,
            'status' => 'confirmee',
            'numero_reservation' => $numeroReservation,
        ]);

        // Decrement the number of available places
        $event->decrement('nb_places', $validated['nb_places']);

        // Generate ticket PDF for free events
        $ticketGenerator->generateTicket($booking);

        // Send confirmation email
        $user = Auth::user();
        Mail::to($user->email)->send(new ParticipationConfirmee($booking));

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
