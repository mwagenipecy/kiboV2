<?php

namespace App\Jobs;

use App\Mail\RegistrationCredentialsMail;
use App\Services\SelcomSmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
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
        public string $registrationType,
        public ?string $phoneNumber = null
    ) {}

    public function handle(): void
    {
        if (!empty($this->phoneNumber)) {
            try {
                app(SelcomSmsService::class)->send(
                    $this->phoneNumber,
                    "Welcome to Kibo Auto! Your account has been approved. Email: {$this->email} Password: {$this->password}"
                );
            } catch (\Throwable $e) {
                Log::error('Registration credentials SMS failed', [
                    'to' => $this->phoneNumber,
                    'error' => $e->getMessage(),
                ]);
            }
        }

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
