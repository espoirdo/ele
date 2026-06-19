<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaiementEnCours extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public string $methode,
        public ?string $telephone = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Paiement en attente - Eledji',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.paiement-en-cours',
        );
    }
}