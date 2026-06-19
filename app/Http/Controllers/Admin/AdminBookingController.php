<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    /**
     * Display a listing of bookings.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'event']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by event
        if ($request->has('event_id') && $request->event_id) {
            $query->where('event_id', $request->event_id);
        }

        // Search by reservation number
        if ($request->has('search') && $request->search) {
            $query->where('numero_reservation', 'like', '%' . $request->search . '%');
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);
        $events = Event::orderBy('titre')->get();

        // Stats
        $stats = [
            'total' => Booking::count(),
            'confirmees' => Booking::where('status', 'confirmee')->count(),
            'en_attente' => Booking::where('status', 'en_attente')->count(),
            'annulees' => Booking::where('status', 'annulee')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'events', 'stats'));
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'event', 'payments']);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Update the specified booking status.
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:confirmee,en_attente,annulee',
        ]);

        $oldStatus = $booking->status;
        $newStatus = $validated['status'];

        $booking->update(['status' => $newStatus]);

        // If changing from confirmee to annulee, increment places back
        if ($oldStatus === 'confirmee' && $newStatus === 'annulee') {
            $booking->event->increment('nb_places', $booking->nb_places);
        }

        // If changing from annulee to confirmee, decrement places
        if ($oldStatus === 'annulee' && $newStatus === 'confirmee') {
            $booking->event->decrement('nb_places', $booking->nb_places);
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Statut de la reservation mis a jour.');
    }

    /**
     * Confirm a pending payment and update booking status.
     */
    public function confirmPayment(Booking $booking)
    {
        if ($booking->status !== 'en_attente') {
            return redirect()->route('admin.bookings.index')
                ->with('error', 'Cette reservation n\'est pas en attente de paiement.');
        }

        $booking->update(['status' => 'confirmee']);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Paiement confirme. Reservation mise a jour.');
    }
}