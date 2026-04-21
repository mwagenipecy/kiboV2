<?php

namespace App\Providers;

use App\Mail\Transport\KiboMailerRelayTransport;
use Illuminate\Mail\MailManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->make(MailManager::class)->extend('kibomailer_relay', function () {
            return new KiboMailerRelayTransport();
        });
    }
}
