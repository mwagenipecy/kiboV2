<?php

namespace App\Livewire\Admin;

use App\Models\CarExchangeRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ExchangeRequests extends Component
{
    public $filter = 'all'; // all, pending, admin_approved, sent_to_dealers, completed, rejected

    public function mount()
    {
        // Get filter from query parameter
        $this->filter = request()->get('filter', 'all');
    }

    public function render()
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();
        $userEntityId = $user->entity_id ?? null;

        $query = CarExchangeRequest::withCount('quotations')
            ->with(['desiredMake', 'desiredModel', 'user']);

        // If dealer, only show requests sent to dealers (status = sent_to_dealers)
        // and optionally filter by their entity_id if the request was specifically sent to them
        if (!$isAdmin && $user->isDealer()) {
            $query->where('status', 'sent_to_dealers');
            // Optionally filter by sent_to_dealer_id if you want dealers to only see requests sent specifically to them
            // For now, we'll show all sent_to_dealers requests so dealers can see all available opportunities
        }

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        $requests = $query->latest()->get();
        
        return view('livewire.admin.exchange-requests', [
            'requests' => $requests,
        ])->layout('layouts.admin');
    }
}
