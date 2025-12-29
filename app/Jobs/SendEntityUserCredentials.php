<?php

namespace App\Jobs;

use App\Mail\EntityUserCredentials;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendEntityUserCredentials implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public Entity $entity,
        public string $password
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)
            ->send(new EntityUserCredentials($this->user, $this->entity, $this->password));
    }
}
