<?php

namespace App\Console\Commands;

use App\Models\AuctionOffer;
use App\Models\AuctionVehicle;
use Illuminate\Console\Command;

class CompleteExpiredAuctions extends Command
{
    protected $signature = 'auctions:complete-expired';

    protected $description = 'Complete active auctions whose end date has passed: accept highest offer or close if none';

    public function handle(): int
    {
        $expired = AuctionVehicle::query()
            ->where('status', 'active')
            ->whereNotNull('auction_end')
            ->where('auction_end', '<=', now())
            ->get();

        $sold = 0;
        $closed = 0;

        foreach ($expired as $auction) {
            $highestOffer = AuctionOffer::where('auction_vehicle_id', $auction->id)
                ->where('status', 'pending')
                ->orderByDesc('offer_amount')
                ->first();

            if ($highestOffer) {
                $auction->acceptOffer($highestOffer);
                $sold++;
                $this->info("Auction {$auction->auction_number}: accepted highest offer " . number_format($highestOffer->offer_amount, 0) . " {$auction->currency}");
            } else {
                $auction->closeAuction('Auto-closed: auction period ended with no pending offers.');
                $closed++;
                $this->info("Auction {$auction->auction_number}: closed (no offers)");
            }
        }

        if ($sold + $closed > 0) {
            $this->info("Completed {$sold} auction(s) as sold, {$closed} as closed.");
        }

        return self::SUCCESS;
    }
}
