<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $sujet,
        public string $contenu,
        public string $email,
    ) {
    }

    public function build(): self
    {
        return $this->subject($this->sujet)
            ->view('emails.newsletter')
            ->with([
                'sujet' => $this->sujet,
                'contenu' => $this->contenu,
                'email' => $this->email,
            ]);
    }
}
