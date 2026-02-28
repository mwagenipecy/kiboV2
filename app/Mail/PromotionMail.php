<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PromotionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $recipientName,
        public string $subjectLine,
        public string $bodyHtml
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    public function content(): Content
    {
        $appUrl = rtrim(config('app.url'), '/');
        return new Content(
            view: 'emails.promotion',
            with: [
                'recipientName' => $this->recipientName,
                'bodyHtml' => $this->bodyHtml,
                'appUrl' => $appUrl,
                'logoUrl' => $appUrl . '/logo/green.png',
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
