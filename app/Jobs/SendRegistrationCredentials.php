<?php

namespace App\Jobs;

use App\Mail\RegistrationCredentialsMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendRegistrationCredentials implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public string $email,
        public string $name,
        public string $password,
        public string $registrationType
    ) {}

    public function handle(): void
    {
        Mail::to($this->email)->send(
            new RegistrationCredentialsMail(
                $this->name,
                $this->email,
                $this->password,
                $this->registrationType
            )
        );
    }
}
