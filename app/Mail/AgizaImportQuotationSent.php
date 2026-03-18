<?php

namespace App\Mail;

use App\Models\AgizaImportRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgizaImportQuotationSent extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AgizaImportRequest $request
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Import Quotation for ' . $this->request->request_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.agiza-import-quotation-sent',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
