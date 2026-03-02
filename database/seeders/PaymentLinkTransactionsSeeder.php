<?php

namespace Database\Seeders;

use App\Models\PaymentLink;
use App\Models\PaymentLinkTransaction;
use Illuminate\Database\Seeder;

class PaymentLinkTransactionsSeeder extends Seeder
{
    protected static array $methods = ['TZ-MPESA-C2B', 'TZ-AIRTEL-C2B', 'TZ-TIGO-C2B', 'TZ-HALOPESA-C2B'];

    /**
     * Seed transactions for payment links that have partial or paid status.
     */
    public function run(): void
    {
        $links = PaymentLink::with('items')
            ->whereHas('items', fn ($q) => $q->whereIn('payment_status', ['partial', 'paid']))
            ->get();

        $count = 0;
        foreach ($links as $link) {
            $totalPaid = (float) $link->items->sum('paid_amount');
            if ($totalPaid <= 0) {
                continue;
            }
            // Create 1–3 transactions that sum to totalPaid (approximate)
            $n = rand(1, min(3, (int) ceil($totalPaid / 50000)));
            $perTxn = $totalPaid / $n;
            $remaining = $totalPaid;
            for ($i = 0; $i < $n; $i++) {
                $amount = $i === $n - 1 ? round($remaining, 2) : round($perTxn, 2);
                $remaining -= $amount;
                PaymentLinkTransaction::create([
                    'payment_link_id' => $link->id,
                    'amount' => $amount,
                    'currency' => $link->currency ?? 'TZS',
                    'reference' => 'TXN-' . strtoupper(uniqid()),
                    'status' => 'completed',
                    'payment_method' => self::$methods[array_rand(self::$methods)],
                    'paid_at' => $link->created_at->addMinutes(rand(60, 10080)),
                ]);
                $count++;
            }
        }

        $this->command->info("Created {$count} transactions for partial/paid links.");
    }
}
