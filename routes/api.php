<?php

use Illuminate\Support\Facades\Route;

// ============================================
// TWILIO WEBHOOK ROUTES
// ============================================
Route::post('/webhook/twilio/incoming', [App\Http\Controllers\TwilioWebhookController::class, 'handleIncomingMessage'])
    ->name('twilio.webhook.incoming');
Route::post('/webhook/twilio/status', [App\Http\Controllers\TwilioWebhookController::class, 'handleStatusCallback'])
    ->name('twilio.webhook.status');

