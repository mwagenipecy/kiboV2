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

// ============================================
// UNIVERSAL PAYMENT LINKS (Saccos / bills)
// ============================================
Route::prefix('payment-links')->name('payment-links.')->group(function () {
    Route::post('/generate', [App\Http\Controllers\Api\PaymentLinkController::class, 'generate'])->name('generate');
    Route::get('/', [App\Http\Controllers\Api\PaymentLinkController::class, 'index'])->name('index');
    Route::get('/{id}', [App\Http\Controllers\Api\PaymentLinkController::class, 'show'])->name('show');
});
Route::post('/webhook/payment-link', [App\Http\Controllers\Api\PaymentLinkWebhookController::class, 'handle'])
    ->name('payment-link.webhook');


Route::get('/health', function () {
    return response('ok', 200)
        ->header('Cache-Control', 'no-cache');
        
        });


