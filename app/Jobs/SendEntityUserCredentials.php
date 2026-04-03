<?php

namespace App\Jobs;

use App\Mail\EntityUserCredentials;
use App\Models\Entity;
use App\Models\User;
use App\Services\SelcomSmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEntityUserCredentials implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user,
        public Entity $entity,
        public string $password
    ) {}

    public function handle(): void
    {
        $phone = $this->user->getPhoneNumber();

        if (!empty($phone)) {
            try {
                app(SelcomSmsService::class)->send(
                    $phone,
                    "Welcome to Kibo Auto! Your {$this->entity->type->value} account has been approved. Email: {$this->user->email} Password: {$this->password}"
                );
            } catch (\Throwable $e) {
                Log::error('Entity credentials SMS failed', [
                    'to' => $phone,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Mail::to($this->user->email)
            ->send(new EntityUserCredentials($this->user, $this->entity, $this->password));
    }
}
