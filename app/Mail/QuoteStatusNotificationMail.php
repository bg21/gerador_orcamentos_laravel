<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteStatusNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Quote $quote,
        public string $status,
        public string $reason = ''
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusWord = $this->status === 'approved' ? 'APROVADO' : 'RECUSADO';
        return new Envelope(
            subject: "[Notificação] Orçamento {$this->quote->quote_number} foi {$statusWord}!",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.quotes.status_changed',
        );
    }
}
