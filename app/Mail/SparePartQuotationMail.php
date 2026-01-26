<?php

namespace App\Mail;

use App\Models\SparePartOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SparePartQuotationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SparePartOrder $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Quotation for Your Spare Part Order - ' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.spare-parts.quotation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

