<?php

namespace App\Mail;

use App\Models\DealerExchangeQuotation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExchangeQuotationMail extends Mailable
{
    use Queueable, SerializesModels;

    public DealerExchangeQuotation $quotation;

    public function __construct(DealerExchangeQuotation $quotation)
    {
        $this->quotation = $quotation;
    }

    public function build()
    {
        return $this->subject('Car Exchange Quotation - ' . $this->quotation->entity->name)
            ->view('emails.customer.exchange-quotation');
    }
}
