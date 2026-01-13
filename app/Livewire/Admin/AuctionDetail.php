<?php

namespace App\Livewire\Admin;

use App\Models\AuctionOffer;
use App\Models\AuctionVehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AuctionDetail extends Component
{
    public $auctionId;
    public $auction;
    public $adminNotes = '';
    
    // Offer form fields
    public $offerAmount;
    public $offerMessage;
    public $offerTerms;
    public $companyName = 'Kibo Auto (Admin)';
    
    // Modal state
    public $showOfferFormModal = false;

    public function mount($id)
    {
        $this->auctionId = $id;
        $this->loadAuction();
        $this->adminNotes = $this->auction->admin_notes ?? '';
        
        // Check if admin already has an offer
        $existingOffer = AuctionOffer::where('auction_vehicle_id', $id)
            ->where('dealer_id', Auth::id())
            ->where('status', 'pending')
            ->first();
        
        if ($existingOffer) {
            $this->offerAmount = $existingOffer->offer_amount;
            $this->offerMessage = $existingOffer->message;
            $this->offerTerms = $existingOffer->terms;
            $this->companyName = $existingOffer->company_name;
        }
    }

    public function loadAuction()
    {
        $this->auction = AuctionVehicle::with([
            'user', 
            'make', 
            'model', 
            'approvedBy',
            'offers' => function($q) {
                $q->orderBy('offer_amount', 'desc');
            },
            'offers.dealer'
        ])->findOrFail($this->auctionId);
    }

    public function approve()
    {
        $this->auction->update([
            'admin_approved' => true,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'status' => 'active',
            'auction_start' => now(),
        ]);
        
        $this->loadAuction();
        session()->flash('success', 'Auction approved and is now live for dealers.');
    }

    public function reject()
    {
        $this->auction->update([
            'admin_approved' => false,
            'status' => 'cancelled',
            'admin_notes' => $this->adminNotes,
        ]);
        
        session()->flash('success', 'Auction rejected.');
        return redirect()->route('admin.auctions');
    }

    public function toggleVisibility()
    {
        $this->auction->update(['is_visible' => !$this->auction->is_visible]);
        $this->loadAuction();
        
        session()->flash('success', $this->auction->is_visible ? 'Auction is now visible.' : 'Auction is now hidden.');
    }

    public function updateStatus($status)
    {
        $this->auction->update(['status' => $status]);

        if ($status === 'closed' || $status === 'cancelled') {
            $this->auction->offers()->where('status', 'pending')->update([
                'status' => 'rejected',
                'responded_at' => now(),
                'response_message' => 'Auction ' . $status . ' by admin',
            ]);
        }

        $this->loadAuction();
        session()->flash('success', 'Auction status updated to ' . $status . '.');
    }

    public function saveNotes()
    {
        $this->auction->update(['admin_notes' => $this->adminNotes]);
        session()->flash('success', 'Notes saved.');
    }

    public function openOfferForm()
    {
        $this->showOfferFormModal = true;
    }

    public function closeOfferFormModal()
    {
        $this->showOfferFormModal = false;
    }

    public function submitOffer()
    {
        $this->validate([
            'offerAmount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();

        // Check for existing pending offer
        $existingOffer = AuctionOffer::where('auction_vehicle_id', $this->auction->id)
            ->where('dealer_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existingOffer) {
            $existingOffer->update([
                'offer_amount' => $this->offerAmount,
                'message' => $this->offerMessage,
                'terms' => $this->offerTerms,
                'company_name' => $this->companyName,
            ]);
            $this->auction->updateOfferStats();
            session()->flash('success', 'Your offer has been updated.');
        } else {
            AuctionOffer::create([
                'auction_vehicle_id' => $this->auction->id,
                'dealer_id' => $user->id,
                'offer_amount' => $this->offerAmount,
                'currency' => $this->auction->currency,
                'message' => $this->offerMessage,
                'terms' => $this->offerTerms,
                'dealer_name' => $user->name,
                'dealer_phone' => $user->phone,
                'dealer_email' => $user->email,
                'company_name' => $this->companyName ?? 'Kibo Auto (Admin)',
                'valid_until' => now()->addDays(7),
            ]);
            session()->flash('success', 'Your offer has been submitted!');
        }

        $this->closeOfferFormModal();
        $this->loadAuction();
    }

    public function render()
    {
        return view('livewire.admin.auction-detail');
    }
}

