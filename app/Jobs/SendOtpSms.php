<?php

namespace App\Jobs;

use App\Services\SelcomSmsService;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendOtpSms
{
    use Dispatchable;

    public function __construct(
        public string $phoneNumber,
        public string $otpCode
    ) {}

    public function handle(SelcomSmsService $smsService): void
    {
        try {
            $sent = $smsService->send(
                $this->phoneNumber,
                "Your Kibo Auto OTP code is {$this->otpCode}. It expires in 5 minutes."
            );

            if (!$sent) {
                Log::error('OTP SMS failed to send', ['to' => $this->phoneNumber]);
            }
        } catch (\Throwable $e) {
            Log::error('OTP SMS exception', [
                'to' => $this->phoneNumber,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

