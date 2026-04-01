<?php

namespace App\Jobs;

use App\Services\SelcomSmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        Log::info('OTP SMS job started', [
            'job' => self::class,
            'to' => $this->phoneNumber,
            'queue' => $this->queue,
        ]);

        $sent = $smsService->send(
            $this->phoneNumber,
            "Your Kibo Auto OTP code is {$this->otpCode}. It expires in 5 minutes."
        );

        if (!$sent) {
            Log::error('OTP SMS job failed', [
                'job' => self::class,
                'to' => $this->phoneNumber,
                'queue' => $this->queue,
            ]);
            throw new \RuntimeException('Failed to send OTP SMS via Selcom.');
        }

        Log::info('OTP SMS job sent', [
            'job' => self::class,
            'to' => $this->phoneNumber,
            'queue' => $this->queue,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('OTP SMS job permanently failed', [
            'job' => self::class,
            'to' => $this->phoneNumber,
            'queue' => $this->queue,
            'error' => $exception->getMessage(),
        ]);
    }
}

