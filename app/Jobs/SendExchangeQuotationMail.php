<?php

namespace App\Jobs;

use App\Mail\ExchangeQuotationMail;
use App\Models\DealerExchangeQuotation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendExchangeQuotationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public DealerExchangeQuotation $quotation
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->quotation->exchangeRequest->customer_email)
                ->send(new ExchangeQuotationMail($this->quotation));
            
            // Update quotation status
            $this->quotation->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log the error but don't fail the job immediately
            \Log::error('Failed to send exchange quotation email: ' . $e->getMessage(), [
                'quotation_id' => $this->quotation->id,
                'customer_email' => $this->quotation->exchangeRequest->customer_email,
            ]);
            
            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }
}
