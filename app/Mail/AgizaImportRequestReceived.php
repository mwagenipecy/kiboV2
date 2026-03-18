<?php

namespace App\Mail;

use App\Models\AgizaImportRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgizaImportRequestReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AgizaImportRequest $request
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Import Request Received - ' . $this->request->request_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.agiza-import-request-received',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
