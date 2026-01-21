<?php

namespace App\Jobs;

use App\Mail\NewCarRequestMail;
use App\Models\CarRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewCarRequestEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $email,
        public int $carRequestId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $carRequest = CarRequest::find($this->carRequestId);
        
        if ($carRequest) {
            Mail::to($this->email)->send(new NewCarRequestMail($carRequest));
        }
    }
}

