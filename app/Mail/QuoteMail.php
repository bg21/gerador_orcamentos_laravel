<?php

namespace App\Mail;

use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Quote $quote,
        public string $customMessage = ''
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $setting    = $this->quote->user->companySetting;
        $senderName = $setting?->company_name ?? $this->quote->user->name;

        return new Envelope(
            subject: "Orçamento {$this->quote->quote_number} — {$senderName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.quotes.send',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $setting  = $this->quote->user->companySetting;
        $template = $setting?->pdf_template ?? 'classic';

        $allowedTemplates = ['classic', 'modern', 'tabular', 'premium'];
        if (!in_array($template, $allowedTemplates)) {
            $template = 'classic';
        }

        $viewName = "quotes.pdf_templates.{$template}";

        $pdf = Pdf::loadView($viewName, ['quote' => $this->quote]);

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                "{$this->quote->quote_number}.pdf"
            )->withMime('application/pdf'),
        ];
    }
}
