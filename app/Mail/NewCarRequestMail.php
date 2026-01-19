<?php

namespace App\Mail;

use App\Models\CarRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCarRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CarRequest $carRequest)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New car request received',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dealer.new-car-request',
            with: [
                'carRequest' => $this->carRequest,
            ],
        );
    }
}


