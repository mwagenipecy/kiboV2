<?php

namespace App\Mail;

use App\Models\CarVisitationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VisitationScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CarVisitationRequest $visitation)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your car visitation has been scheduled – Kibo Auto',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.visitation.scheduled',
        );
    }
}
