<?php

namespace App\Livewire\Admin;

use App\Models\AuctionOffer;
use App\Models\AuctionVehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AuctionManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterApproval = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Modal states
    public $showDetailModal = false;
    public $showOffersModal = false;
    public $showOfferFormModal = false;
    public $selectedAuction = null;
    public $adminNotes = '';
    
    // Offer form fields
    public $offerAmount;
    public $offerMessage;
    public $offerTerms;
    public $companyName;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterApproval' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function viewDetail($auctionId)
    {
        $this->selectedAuction = AuctionVehicle::with(['user', 'make', 'model', 'offers.dealer'])->findOrFail($auctionId);
        $this->adminNotes = $this->selectedAuction->admin_notes;
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedAuction = null;
        $this->adminNotes = '';
    }

    public function viewOffers($auctionId)
    {
        $this->selectedAuction = AuctionVehicle::with(['offers.dealer', 'offers.entity'])->findOrFail($auctionId);
        $this->showOffersModal = true;
    }

    public function closeOffersModal()
    {
        $this->showOffersModal = false;
        $this->selectedAuction = null;
    }

    public function openOfferForm($auctionId)
    {
        $this->selectedAuction = AuctionVehicle::with(['make', 'model', 'offers' => function($q) {
            $q->orderBy('offer_amount', 'desc')->limit(5);
        }])->findOrFail($auctionId);
        
        // Check if admin already has an offer
        $existingOffer = AuctionOffer::where('auction_vehicle_id', $auctionId)
            ->where('dealer_id', Auth::id())
            ->where('status', 'pending')
            ->first();
        
        if ($existingOffer) {
            $this->offerAmount = $existingOffer->offer_amount;
            $this->offerMessage = $existingOffer->message;
            $this->offerTerms = $existingOffer->terms;
            $this->companyName = $existingOffer->company_name;
        } else {
            $this->resetOfferForm();
        }
        
        $this->showOfferFormModal = true;
    }

    public function closeOfferFormModal()
    {
        $this->showOfferFormModal = false;
        $this->selectedAuction = null;
        $this->resetOfferForm();
    }

    public function resetOfferForm()
    {
        $this->offerAmount = null;
        $this->offerMessage = null;
        $this->offerTerms = null;
        $this->companyName = 'Kibo Auto (Admin)';
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
                'company_name' => $this->companyName,
            ]);
            $this->selectedAuction->updateOfferStats();
            session()->flash('success', 'Your offer has been updated.');
        } else {
            // Create new offer
            AuctionOffer::create([
                'auction_vehicle_id' => $this->selectedAuction->id,
                'dealer_id' => $user->id,
                'offer_amount' => $this->offerAmount,
                'currency' => $this->selectedAuction->currency,
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
    }

    public function approve($auctionId)
    {
        $auction = AuctionVehicle::findOrFail($auctionId);
        $auction->update([
            'admin_approved' => true,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'status' => 'active',
            'auction_start' => now(),
        ]);

        session()->flash('success', 'Auction approved and is now live for dealers.');
    }

    public function reject($auctionId)
    {
        $auction = AuctionVehicle::findOrFail($auctionId);
        $auction->update([
            'admin_approved' => false,
            'status' => 'cancelled',
            'admin_notes' => $this->adminNotes,
        ]);

        session()->flash('success', 'Auction rejected.');
        $this->closeDetailModal();
    }

    public function toggleVisibility($auctionId)
    {
        $auction = AuctionVehicle::findOrFail($auctionId);
        $auction->update(['is_visible' => !$auction->is_visible]);

        session()->flash('success', $auction->is_visible ? 'Auction is now visible.' : 'Auction is now hidden.');
    }

    public function updateStatus($auctionId, $status)
    {
        $auction = AuctionVehicle::findOrFail($auctionId);
        $auction->update(['status' => $status]);

        if ($status === 'closed' || $status === 'cancelled') {
            $auction->offers()->where('status', 'pending')->update([
                'status' => 'rejected',
                'responded_at' => now(),
                'response_message' => 'Auction ' . $status . ' by admin',
            ]);
        }

        session()->flash('success', 'Auction status updated to ' . $status . '.');
    }

    public function saveNotes()
    {
        if ($this->selectedAuction) {
            $this->selectedAuction->update(['admin_notes' => $this->adminNotes]);
            session()->flash('success', 'Notes saved.');
        }
    }

    public function render()
    {
        $query = AuctionVehicle::with(['user', 'make', 'model', 'offers'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('auction_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%')
                              ->orWhere('email', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('make', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->when($this->filterApproval !== '', function ($q) {
                if ($this->filterApproval === 'approved') {
                    $q->where('admin_approved', true);
                } elseif ($this->filterApproval === 'pending') {
                    $q->where('admin_approved', false)->where('status', 'pending');
                }
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $auctions = $query->paginate(15);

        // Stats
        $stats = [
            'total' => AuctionVehicle::count(),
            'pending_approval' => AuctionVehicle::where('admin_approved', false)->where('status', 'pending')->count(),
            'active' => AuctionVehicle::where('status', 'active')->count(),
            'sold' => AuctionVehicle::where('status', 'sold')->count(),
            'total_offers' => AuctionOffer::count(),
        ];

        return view('livewire.admin.auction-management', [
            'auctions' => $auctions,
            'stats' => $stats,
        ]);
    }
}

