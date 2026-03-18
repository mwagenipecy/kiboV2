<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendPasswordResetEmail implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $userName,
        public string $userEmail,
        public string $newPassword
    ) {}

    public function handle(): void
    {
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
