<?php

namespace App\Console\Commands;

use App\Models\SparePartOrder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoCancelSparePartOrders extends Command
{
    protected $signature = 'spare-part-orders:auto-cancel';

    protected $description = 'Auto-cancel spare part orders: no quotation within 24h, or no next step after 48h when quoted';

    public function handle(): int
    {
        $cancelledNoQuote = $this->cancelPendingWithNoQuotationAfter24Hours();
        $cancelledNoNextStep = $this->cancelQuotedWithNoAcceptanceAfter48Hours();

        $total = $cancelledNoQuote + $cancelledNoNextStep;
        if ($total > 0) {
            $this->info("Auto-cancelled {$cancelledNoQuote} order(s) with no quotation (24h), {$cancelledNoNextStep} order(s) with no next step (48h).");
        }

        return self::SUCCESS;
    }

    /**
     * Cancel orders that are still pending with zero quotations after 24 hours.
     * They will no longer appear in "Open Requests".
     */
    private function cancelPendingWithNoQuotationAfter24Hours(): int
    {
        $cutoff = Carbon::now()->subHours(24);

        $orders = SparePartOrder::query()
            ->where('status', 'pending')
            ->whereNull('accepted_quotation_id')
            ->where('created_at', '<', $cutoff)
            ->whereDoesntHave('quotations')
            ->get();

        $reason = 'Auto-cancelled: No quotation received within 24 hours.';
        foreach ($orders as $order) {
            $order->update([
                'status' => 'cancelled',
                'admin_notes' => trim(($order->admin_notes ?? '') . "\n\n" . $reason),
            ]);
        }

        return $orders->count();
    }

    /**
     * Cancel orders that are quoted but have no accepted quotation after 48 hours.
     * "Quoted since" = order.quoted_at or earliest quotation created_at.
     */
    private function cancelQuotedWithNoAcceptanceAfter48Hours(): int
    {
        $cutoff = Carbon::now()->subHours(48);
        $cancelled = 0;

        $orders = SparePartOrder::query()
            ->with('quotations')
            ->where('status', 'quoted')
            ->whereNull('accepted_quotation_id')
            ->whereHas('quotations')
            ->get();

        $reason = 'Auto-cancelled: No acceptance or next step within 48 hours of quotation.';
        foreach ($orders as $order) {
            $quotedSince = $order->quoted_at ?? $order->quotations->min('created_at');
            if (!$quotedSince) {
                continue;
            }
            $quotedSince = $quotedSince instanceof \DateTimeInterface ? Carbon::parse($quotedSince) : $quotedSince;
            if ($quotedSince->gte($cutoff)) {
                continue;
            }
            $order->update([
                'status' => 'cancelled',
                'admin_notes' => trim(($order->admin_notes ?? '') . "\n\n" . $reason),
            ]);
            $cancelled++;
        }

        return $cancelled;
    }
}
