<?php

namespace App\Livewire\Customer;

use App\Models\Vehicle;
use Livewire\Component;

class VehicleDetail extends Component
{
    public $vehicleId;
    public $vehicle;
    public $isSaved = false;
    public $expandedSections = [];
    public $showImageModal = false;
    public $currentImage = null;
    public $allImages = [];
    public $showInfoModal = false;
    public $modalContent = null;

    public function mount($id)
    {
        $this->vehicleId = $id;
        $this->vehicle = Vehicle::with(['make', 'model', 'entity'])
            ->findOrFail($id);
        
        // Check if vehicle is saved
        $savedVehicles = session()->get('saved_vehicles', []);
        $this->isSaved = in_array($id, $savedVehicles);
        
        // Prepare all images for gallery
        $this->allImages = [];
        if ($this->vehicle->image_front) {
            $this->allImages[] = $this->vehicle->image_front;
        }
        if ($this->vehicle->other_images && count($this->vehicle->other_images) > 0) {
            $this->allImages = array_merge($this->allImages, $this->vehicle->other_images);
        }
        
        // Initialize expanded sections
        $this->expandedSections = [
            'rareFeatures' => false,
            'fullDescription' => false,
            'stolen' => false,
            'scrapped' => false,
            'imported' => false,
            'exported' => false,
            'writtenOff' => false,
        ];
    }

    public function toggleSave()
    {
        $savedVehicles = session()->get('saved_vehicles', []);
        
        if ($this->isSaved) {
            $savedVehicles = array_diff($savedVehicles, [$this->vehicleId]);
            $this->isSaved = false;
        } else {
            $savedVehicles[] = $this->vehicleId;
            $this->isSaved = true;
        }
        
        session()->put('saved_vehicles', $savedVehicles);
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

    public function openValuationModal($vehicleId)
    {
        $this->dispatch('open-valuation-modal', vehicleId: $vehicleId);
    }

    public function openFinancingModal($vehicleId)
    {
        $this->dispatch('open-financing-modal', vehicleId: $vehicleId);
    }

    public function openCashPurchaseModal($vehicleId)
    {
        $this->dispatch('open-cash-purchase-modal', vehicleId: $vehicleId);
    }

    public function render()
    {
        return view('livewire.customer.vehicle-detail')
            ->layout('layouts.customer');
    }
}
