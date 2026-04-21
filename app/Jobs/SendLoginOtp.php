<?php

namespace App\Jobs;

use App\Mail\LoginOtpMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendLoginOtp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $email,
        public string $userName,
        public string $otpCode
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('OTP email job started', [
            'job' => self::class,
            'to' => $this->email,
            'queue' => $this->queue,
            'mailer' => config('mail.default'),
        ]);

        // Create a temporary user-like object for the mailable
        $user = new \stdClass();
        $user->name = $this->userName;
        $user->email = $this->email;

        try {
            Mail::to($this->email)->send(new LoginOtpMail($user, $this->otpCode));

            Log::info('OTP email sent', [
                'job' => self::class,
                'to' => $this->email,
                'queue' => $this->queue,
                'mailer' => config('mail.default'),
                'from' => config('mail.from.address'),
                'transport' => config('mail.default'),
            ]);
        } catch (\Throwable $e) {
            Log::error('OTP email failed to send', [
                'job' => self::class,
                'to' => $this->email,
                'queue' => $this->queue,
                'mailer' => config('mail.default'),
                'from' => config('mail.from.address'),
                'transport' => config('mail.default'),
                'error' => $e->getMessage(),
            ]);

            // Re-throw so the job is marked as failed / retried by the worker.
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('OTP email job permanently failed', [
            'job' => self::class,
            'to' => $this->email,
            'queue' => $this->queue,
            'error' => $exception->getMessage(),
        ]);
    }
}

