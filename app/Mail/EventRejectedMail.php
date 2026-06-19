<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Event $event)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre événement "' . $this->event->titre . '" a été rejeté',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.event-rejected',
            with: [
                'event' => $this->event,
            ],
        );
    }
}