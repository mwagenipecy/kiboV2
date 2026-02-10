<?php

namespace App\Jobs;

use App\Mail\SparePartOrderConfirmationMail;
use App\Models\SparePartOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSparePartOrderConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public SparePartOrder $order
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->order->customer_email)
            ->send(new SparePartOrderConfirmationMail($this->order));
    }
}

