<?php

namespace App\Jobs;

use App\Services\SelcomSmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPasswordResetEmail implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $userName,
        public string $userEmail,
        public string $newPassword,
        public ?string $phoneNumber = null
    ) {}

    public function handle(): void
    {
        if (!empty($this->phoneNumber)) {
            try {
                app(SelcomSmsService::class)->send(
                    $this->phoneNumber,
                    "Your Kibo Auto password has been reset. New password: {$this->newPassword}"
                );
            } catch (\Throwable $e) {
                Log::error('Password reset SMS failed', [
                    'to' => $this->phoneNumber,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Mail::send('emails.password-reset-by-admin', [
            'name' => $this->userName,
            'email' => $this->userEmail,
            'password' => $this->newPassword,
        ], function ($message) {
            $message->to($this->userEmail)
                ->subject('Your Password Has Been Reset - Kibo');
        });
    }
}
