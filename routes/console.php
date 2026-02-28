<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Spare part orders: auto-cancel no quotation (24h) and no next step after quote (48h)
Schedule::command('spare-part-orders:auto-cancel')->hourly();
