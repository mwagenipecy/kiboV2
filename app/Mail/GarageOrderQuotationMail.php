<?php

namespace App\Mail;

use App\Models\GarageServiceOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GarageOrderQuotationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public GarageServiceOrder $order)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Quotation for Your Garage Service Order #' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.garage.quotation',
            with: [
                'order' => $this->order,
            ],
        );
    }
}
