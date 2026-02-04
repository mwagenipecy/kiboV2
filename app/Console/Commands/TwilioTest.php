<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Twilio\Rest\Client;

class TwilioTest extends Command
{
    // THIS LINE DEFINES: php artisan twilio:test
    protected $signature = 'twilio:test';

    protected $description = 'Test Twilio credentials by sending an SMS';

    public function handle()
    {
        try {
            $client = new Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );

            $message = $client->messages->create(
                '+255767582837',   // <-- PUT YOUR PHONE NUMBER
                [
                    'from' => config('services.twilio.from'),
                    'body' => 'Twilio Laravel connection test successful!'
                ]
            );

            $this->info("Message sent! SID: " . $message->sid);

        } catch (\Exception $e) {
            $this->error("Twilio Error: " . $e->getMessage());
        }
    }
}
