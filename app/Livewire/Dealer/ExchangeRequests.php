<?php

namespace App\Livewire\Dealer;

use App\Models\CarExchangeRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ExchangeRequests extends Component
{
    public function render()
    {
        $user = Auth::user();
        abort_unless($user && $user->isDealer(), 403);

        // Get exchange requests that have been sent to dealers (or all if admin wants to see all)
        $requests = CarExchangeRequest::with(['desiredMake', 'desiredModel', 'quotations'])
            ->whereIn('status', ['sent_to_dealers', 'admin_approved'])
            ->latest()
            ->get();

        return view('livewire.dealer.exchange-requests', [
            'requests' => $requests,
        ])->layout('layouts.dealer');
    }
}
