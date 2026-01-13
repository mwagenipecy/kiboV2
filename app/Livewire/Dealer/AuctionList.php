<?php

namespace App\Livewire\Dealer;

use App\Models\AuctionOffer;
use App\Models\AuctionVehicle;
use App\Models\VehicleMake;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dealer')]
class AuctionList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterMake = '';
    public $filterCondition = '';
    public $sortBy = 'newest';

    // Offer modal
    public $showOfferModal = false;
    public $selectedAuction = null;
    public $offerAmount;
    public $offerMessage;
    public $offerTerms;

    // My offers modal
    public $showMyOffersModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterMake' => ['except' => ''],
        'filterCondition' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openOfferModal($auctionId)
    {
        $this->selectedAuction = AuctionVehicle::with(['make', 'model', 'user'])->findOrFail($auctionId);
        
        // Check if dealer already has an offer
        $existingOffer = AuctionOffer::where('auction_vehicle_id', $auctionId)
            ->where('dealer_id', Auth::id())
            ->where('status', 'pending')
            ->first();
        
        if ($existingOffer) {
            $this->offerAmount = $existingOffer->offer_amount;
            $this->offerMessage = $existingOffer->message;
            $this->offerTerms = $existingOffer->terms;
        } else {
            $this->offerAmount = null;
            $this->offerMessage = null;
            $this->offerTerms = null;
        }
        
        $this->showOfferModal = true;
    }

    public function closeOfferModal()
    {
        $this->showOfferModal = false;
        $this->selectedAuction = null;
        $this->offerAmount = null;
        $this->offerMessage = null;
        $this->offerTerms = null;
    }

    public function submitOffer()
    {
        $this->validate([
            'offerAmount' => 'required|numeric|min:1',
        ]);

        if (!$this->selectedAuction) {
            return;
        }

        $user = Auth::user();
        $entity = $user->entities->first();

        // Check for existing pending offer
        $existingOffer = AuctionOffer::where('auction_vehicle_id', $this->selectedAuction->id)
            ->where('dealer_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existingOffer) {
            // Update existing offer
            $existingOffer->update([
                'offer_amount' => $this->offerAmount,
                'message' => $this->offerMessage,
                'terms' => $this->offerTerms,
            ]);
            session()->flash('success', 'Your offer has been updated.');
        } else {
            // Create new offer
            AuctionOffer::create([
                'auction_vehicle_id' => $this->selectedAuction->id,
                'dealer_id' => $user->id,
                'entity_id' => $entity?->id,
                'offer_amount' => $this->offerAmount,
                'currency' => $this->selectedAuction->currency,
                'message' => $this->offerMessage,
                'terms' => $this->offerTerms,
                'dealer_name' => $user->name,
                'dealer_phone' => $user->phone,
                'dealer_email' => $user->email,
                'company_name' => $entity?->name,
                'valid_until' => now()->addDays(7),
            ]);
            session()->flash('success', 'Your offer has been submitted. The seller will be notified.');
        }

        $this->closeOfferModal();
    }

    public function viewMyOffers()
    {
        $this->showMyOffersModal = true;
    }

    public function closeMyOffersModal()
    {
        $this->showMyOffersModal = false;
    }

    public function withdrawOffer($offerId)
    {
        $offer = AuctionOffer::where('dealer_id', Auth::id())->findOrFail($offerId);
        $offer->withdraw();
        
        session()->flash('success', 'Offer withdrawn.');
    }

    public function render()
    {
        $query = AuctionVehicle::forDealer()
            ->with(['make', 'model', 'offers'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhereHas('make', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('model', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->filterMake, function ($q) {
                $q->where('vehicle_make_id', $this->filterMake);
            })
            ->when($this->filterCondition, function ($q) {
                $q->where('condition', $this->filterCondition);
            });

        // Sorting
        switch ($this->sortBy) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_high':
                $query->orderBy('asking_price', 'desc');
                break;
            case 'price_low':
                $query->orderBy('asking_price', 'asc');
                break;
            case 'offers':
                $query->orderBy('offer_count', 'desc');
                break;
        }

        $auctions = $query->paginate(12);

        // Get my offers
        $myOffers = AuctionOffer::with(['auctionVehicle.make', 'auctionVehicle.model'])
            ->where('dealer_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Get makes for filter
        $makes = VehicleMake::where('status', 'active')->orderBy('name')->get(['id', 'name']);

        return view('livewire.dealer.auction-list', [
            'auctions' => $auctions,
            'myOffers' => $myOffers,
            'makes' => $makes,
        ]);
    }
}

