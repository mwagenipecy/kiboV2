<?php

use Illuminate\Support\Facades\Route;

// ============================================
// TWILIO WEBHOOK ROUTES
// ============================================
// Note: These routes are POST-only. Twilio will send POST requests to these endpoints.
// Accessible at: /api/webhook/twilio/incoming and /api/webhook/twilio/status
Route::post('/webhook/twilio/incoming', [App\Http\Controllers\TwilioWebhookController::class, 'handleIncomingMessage'])
    ->name('twilio.webhook.incoming');
Route::post('/webhook/twilio/status', [App\Http\Controllers\TwilioWebhookController::class, 'handleStatusCallback'])
    ->name('twilio.webhook.status');

