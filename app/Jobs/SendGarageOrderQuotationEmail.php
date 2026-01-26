<?php

namespace App\Jobs;

use App\Mail\GarageOrderQuotationMail;
use App\Models\GarageServiceOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendGarageOrderQuotationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public GarageServiceOrder $order
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->order->customer_email)->send(new GarageOrderQuotationMail($this->order));
    }
}
