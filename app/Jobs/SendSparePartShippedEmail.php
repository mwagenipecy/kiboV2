<?php

namespace App\Jobs;

use App\Mail\SparePartShippedMail;
use App\Models\SparePartOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSparePartShippedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public SparePartOrder $order
    ) {}

    public function handle(): void
    {
        Mail::to($this->order->customer_email)
            ->send(new SparePartShippedMail($this->order));
    }
}

