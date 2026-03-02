<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UniversalPaymentLinkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Webhook for payment link provider.
 * When the provider sends payment events (paid/partial), update PaymentLinkItem status here.
 * Implement the payload handling once the webhook API spec is provided.
 */
class PaymentLinkWebhookController extends Controller
{
    public function __construct(
        protected UniversalPaymentLinkService $paymentLinkService
    ) {}

    /**
     * Handle incoming webhook from payment link provider.
     * TODO: Map provider payload to link_id/short_code and item_code, then call recordPayment on items.
     */
    public function handle(Request $request): JsonResponse
    {
        Log::info('Payment link webhook received', [
            'payload' => $request->all(),
        ]);

        // Placeholder: when webhook spec is given, parse payload and update items, e.g.:
        // $linkIdOrShortCode = $request->input('link_id'); // or short_code
        // $itemCode = $request->input('item_code');
        // $amount = (float) $request->input('amount_paid');
        // $link = $this->paymentLinkService->findLinkByExternalId($linkIdOrShortCode);
        // if ($link) {
        //     $item = $link->items()->where('item_code', $itemCode)->first();
        //     if ($item) {
        //         $item->recordPayment($amount);
        //     }
        // }

        return response()->json(['status' => 'received']);
    }
}
