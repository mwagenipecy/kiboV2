<?php

namespace App\Jobs;

use App\Mail\LoginOtpMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
        // Create a temporary user object for the mailable
        $user = new \stdClass();
        $user->name = $this->userName;
        $user->email = $this->email;
        
        Mail::to($this->email)->send(
            new LoginOtpMail($user, $this->otpCode)
        );
    }
}

