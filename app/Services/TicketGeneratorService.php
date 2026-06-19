<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class TicketGeneratorService
{
    /**
     * Generate and save a ticket HTML for a booking
     */
    public function generateTicket(Booking $booking): string
    {
        $user = $booking->user;
        $event = $booking->event;
        $ticket = $booking->ticket;

        // Generate unique ticket number if not exists
        if (!$booking->numero_reservation) {
            $booking->numero_reservation = 'ELD-' . strtoupper(uniqid()) . '-' . $booking->id;
            $booking->save();
        }

        // Prepare data for ticket
        $data = [
            'ticket_number' => $booking->numero_reservation,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_phone' => $user->phone ?? 'N/A',
            'event_title' => $event->titre,
            'event_date' => $event->date?->format('d/m/Y') ?? 'À déterminer',
            'event_time' => $event->heure ?? 'N/A',
            'event_location' => $event->lieu ?? 'Lieu à confirmer',
            'ticket_type' => $ticket?->nom ?? 'Entrée générale',
            'ticket_price' => $booking->total,
            'nb_places' => $booking->nb_places,
            'booking_date' => $booking->created_at->format('d/m/Y H:i'),
            'status' => $booking->status,
        ];

        // Render HTML from view
        $html = View::make('tickets.ticket-html', $data)->render();

        // Save HTML file to storage
        $filename = 'tickets/' . $booking->id . '_' . $booking->numero_reservation . '.html';
        Storage::disk('public')->put($filename, $html);

        // Update booking with ticket path
        $booking->update(['ticket_path' => $filename]);

        return $filename;
    }

    /**
     * Get or generate ticket file path
     */
    public function getTicketPath(Booking $booking): ?string
    {
        if ($booking->ticket_path && Storage::disk('public')->exists($booking->ticket_path)) {
            return $booking->ticket_path;
        }

        // If confirmed, generate new ticket
        if ($booking->status === 'confirmee') {
            return $this->generateTicket($booking);
        }

        return null;
    }

    /**
     * Delete ticket file
     */
    public function deleteTicket(Booking $booking): bool
    {
        if ($booking->ticket_path && Storage::disk('public')->exists($booking->ticket_path)) {
            Storage::disk('public')->delete($booking->ticket_path);
            $booking->update(['ticket_path' => null]);
            return true;
        }
        return false;
    }
}
