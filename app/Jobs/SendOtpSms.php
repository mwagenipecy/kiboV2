<?php

namespace App\Jobs;

use App\Services\SelcomSmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOtpSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public string $phoneNumber,
        public string $otpCode
    ) {}

    public function handle(SelcomSmsService $smsService): void
    {
        $smsService->send(
            $this->phoneNumber,
            "Your Kibo Auto OTP code is {$this->otpCode}. It expires in 5 minutes."
        );
    }
}

