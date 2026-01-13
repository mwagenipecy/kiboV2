<?php

namespace App\Livewire\Customer;

use App\Models\AuctionOffer;
use App\Models\AuctionVehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.customer', ['vehicleType' => 'cars'])]
class MyAuctions extends Component
{
    use WithPagination;

    public $filterStatus = 'all';
    
    // Modal states
    public $showOffersModal = false;
    public $showOfferDetailModal = false;
    public $selectedAuction = null;
    public $selectedOffer = null;
    public $counterAmount;
    public $counterMessage;

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('cars.index');
        }
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function viewOffers($auctionId)
    {
        $this->selectedAuction = AuctionVehicle::with(['offers.dealer', 'offers.entity', 'make', 'model'])
            ->where('user_id', Auth::id())
            ->findOrFail($auctionId);
        $this->showOffersModal = true;
    }

    public function closeOffersModal()
    {
        $this->showOffersModal = false;
        $this->selectedAuction = null;
    }

    public function viewOfferDetail($offerId)
    {
        $this->selectedOffer = AuctionOffer::with(['dealer', 'entity', 'auctionVehicle.make', 'auctionVehicle.model'])
            ->whereHas('auctionVehicle', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->findOrFail($offerId);
        $this->counterAmount = $this->selectedOffer->counter_amount;
        $this->showOfferDetailModal = true;
    }

    public function closeOfferDetailModal()
    {
        $this->showOfferDetailModal = false;
        $this->selectedOffer = null;
        $this->counterAmount = null;
        $this->counterMessage = null;
    }

    public function acceptOffer($offerId)
    {
        $offer = AuctionOffer::with('auctionVehicle')
            ->whereHas('auctionVehicle', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->findOrFail($offerId);
        
        $offer->auctionVehicle->acceptOffer($offer);
        
        session()->flash('success', 'Offer accepted! The dealer will be notified and will contact you to finalize the deal.');
        
        $this->closeOfferDetailModal();
        $this->closeOffersModal();
    }

    public function rejectOffer($offerId)
    {
        $offer = AuctionOffer::whereHas('auctionVehicle', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->findOrFail($offerId);
        
        $offer->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);
        
        $offer->auctionVehicle->updateOfferStats();
        
        session()->flash('success', 'Offer rejected.');
        
        $this->closeOfferDetailModal();
        
        // Refresh offers
        if ($this->selectedAuction) {
            $this->viewOffers($this->selectedAuction->id);
        }
    }

    public function counterOffer($offerId)
    {
        $this->validate([
            'counterAmount' => 'required|numeric|min:0',
        ]);

        $offer = AuctionOffer::whereHas('auctionVehicle', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->findOrFail($offerId);
        
        $offer->counter($this->counterAmount, $this->counterMessage);
        
        session()->flash('success', 'Counter offer sent to the dealer.');
        
        $this->closeOfferDetailModal();
        
        // Refresh offers
        if ($this->selectedAuction) {
            $this->viewOffers($this->selectedAuction->id);
        }
    }

    public function toggleVisibility($auctionId)
    {
        $auction = AuctionVehicle::where('user_id', Auth::id())->findOrFail($auctionId);
        $auction->update(['is_visible' => !$auction->is_visible]);
        
        session()->flash('success', $auction->is_visible ? 'Auction is now visible to dealers.' : 'Auction is now hidden from dealers.');
    }

    public function cancelAuction($auctionId)
    {
        $auction = AuctionVehicle::where('user_id', Auth::id())->findOrFail($auctionId);
        $auction->update(['status' => 'cancelled']);
        
        // Reject all pending offers
        $auction->offers()->where('status', 'pending')->update([
            'status' => 'rejected',
            'responded_at' => now(),
            'response_message' => 'Auction cancelled by owner',
        ]);
        
        session()->flash('success', 'Auction cancelled.');
    }

    public function render()
    {
        $query = AuctionVehicle::with(['make', 'model', 'offers'])
            ->where('user_id', Auth::id());

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        $auctions = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get counts for filters
        $statusCounts = [
            'all' => AuctionVehicle::where('user_id', Auth::id())->count(),
            'pending' => AuctionVehicle::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'active' => AuctionVehicle::where('user_id', Auth::id())->where('status', 'active')->count(),
            'sold' => AuctionVehicle::where('user_id', Auth::id())->where('status', 'sold')->count(),
        ];

        return view('livewire.customer.my-auctions', [
            'auctions' => $auctions,
            'statusCounts' => $statusCounts,
        ]);
    }
}

