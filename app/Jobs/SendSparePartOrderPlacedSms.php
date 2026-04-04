<?php

namespace App\Jobs;

use App\Models\SparePartOrder;
use App\Services\SelcomSmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendSparePartOrderPlacedSms implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $sparePartOrderId
    ) {}

    public function handle(SelcomSmsService $smsService): void
    {
        $order = SparePartOrder::query()->find($this->sparePartOrderId);
        if (! $order || empty($order->customer_phone)) {
            return;
        }

        $url = route('spare-parts.track', ['token' => $order->public_token], absolute: true);
        $message = "Kibo Auto: Your spare parts order {$order->order_number} was received. Track your order: {$url}";

        try {
            $sent = $smsService->send($order->customer_phone, $message);
            if (! $sent) {
                Log::warning('Spare part order SMS failed', ['order_id' => $order->id]);
            }
        } catch (\Throwable $e) {
            Log::error('Spare part order SMS exception', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
