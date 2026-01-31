<?php

namespace App\Livewire\Customer;

use App\Models\LendingCriteria;
use App\Models\Truck;
use App\Enums\VehicleStatus;
use Livewire\Component;

class TruckDetail extends Component
{
    public $truckId;
    public $truck;
    public $isSaved = false;
    public $expandedSections = [];
    public $showImageModal = false;
    public $currentImage = null;
    public $allImages = [];
    public $showInfoModal = false;
    public $modalContent = null;
    public $matchingLenders = [];

    public function mount($id)
    {
        $this->truckId = $id;
        $this->truck = Truck::with(['make', 'model', 'entity'])
            ->where('status', VehicleStatus::APPROVED)
            ->findOrFail($id);
        
        // Check if truck is saved
        $savedTrucks = session()->get('saved_trucks', []);
        $this->isSaved = in_array($id, $savedTrucks);
        
        // Prepare all images for gallery
        $this->allImages = [];
        if ($this->truck->image_front) {
            $this->allImages[] = $this->truck->image_front;
        }
        if ($this->truck->image_side) {
            $this->allImages[] = $this->truck->image_side;
        }
        if ($this->truck->image_back) {
            $this->allImages[] = $this->truck->image_back;
        }
        if ($this->truck->other_images && count($this->truck->other_images) > 0) {
            $this->allImages = array_merge($this->allImages, $this->truck->other_images);
        }
        
        // Initialize expanded sections
        $this->expandedSections = [
            'rareFeatures' => false,
            'fullDescription' => false,
        ];
        
        // Get matching lenders for financing options
        $this->matchingLenders = $this->getMatchingLenders();
    }
    
    /**
     * Get lenders whose criteria match this truck
     */
    public function getMatchingLenders()
    {
        $criteria = LendingCriteria::with('entity')
            ->active()
            ->orderBy('priority', 'desc')
            ->get();
        
        // For now, return all active lenders since truck criteria matching might be different
        // You can implement specific truck criteria matching logic here if needed
        return $criteria->values();
    }

    public function toggleSave()
    {
        $savedTrucks = session()->get('saved_trucks', []);
        
        if ($this->isSaved) {
            $savedTrucks = array_diff($savedTrucks, [$this->truckId]);
            $this->isSaved = false;
        } else {
            $savedTrucks[] = $this->truckId;
            $this->isSaved = true;
        }
        
        session()->put('saved_trucks', $savedTrucks);
    }

    public function toggleSection($section)
    {
        $this->expandedSections[$section] = !$this->expandedSections[$section];
    }

    public function openImageModal($imageIndex)
    {
        $this->currentImage = $imageIndex;
        $this->showImageModal = true;
    }

    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->currentImage = null;
    }

    public function nextImage()
    {
        if ($this->currentImage < count($this->allImages) - 1) {
            $this->currentImage++;
        } else {
            $this->currentImage = 0; // Loop back to first
        }
    }

    public function previousImage()
    {
        if ($this->currentImage > 0) {
            $this->currentImage--;
        } else {
            $this->currentImage = count($this->allImages) - 1; // Loop to last
        }
    }

    public function openInfoModal($type)
    {
        $this->modalContent = $type;
        $this->showInfoModal = true;
    }

    public function closeInfoModal()
    {
        $this->showInfoModal = false;
        $this->modalContent = null;
    }

    public function openValuationModal($truckId)
    {
        $this->dispatch('open-valuation-modal', truckId: $truckId);
    }

    public function openFinancingModal($truckId)
    {
        $this->dispatch('open-financing-modal', truckId: $truckId);
    }

    public function openCashPurchaseModal($truckId)
    {
        $this->dispatch('open-cash-purchase-modal', truckId: $truckId);
    }

    public function render()
    {
        return view('livewire.customer.truck-detail')
            ->layout('layouts.customer', ['vehicleType' => 'trucks']);
    }
}



















