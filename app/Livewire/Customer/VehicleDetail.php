<?php

namespace App\Livewire\Customer;

use App\Jobs\SendVisitationRequestReceivedEmail;
use App\Models\CarVisitationRequest;
use App\Models\LendingCriteria;
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

    public $matchingLenders = [];

    /** @var bool Visitation request bottom sheet */
    public $showVisitationSheet = false;

    public $visitationName = '';

    public $visitationEmail = '';

    public $visitationPhone = '';

    public $visitationReason = '';

    public $visitationSubmitted = false;

    public function mount($id)
    {
        $this->vehicleId = $id;
        $this->vehicle = Vehicle::with(['make', 'model', 'entity', 'country'])
            ->findOrFail($id);

        // Check if vehicle is saved
        $savedVehicles = session()->get('saved_vehicles', []);
        $this->isSaved = in_array($id, $savedVehicles);

        // Prepare all images for gallery
        $this->allImages = [];
        if ($this->vehicle->image_front) {
            $this->allImages[] = $this->vehicle->image_front;
        }

        if ($this->vehicle->image_side) {
            $this->allImages[] = $this->vehicle->image_side;
        }

        if ($this->vehicle->image_back) {
            $this->allImages[] = $this->vehicle->image_back;
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

        // Get matching lenders for financing options
        $this->matchingLenders = $this->getMatchingLenders();
    }

    /**
     * Get lenders whose criteria match this vehicle
     */
    public function getMatchingLenders()
    {
        $criteria = LendingCriteria::with('entity')
            ->active()
            ->orderBy('priority', 'desc')
            ->get();

        $matching = $criteria->filter(function ($criterion) {
            return $criterion->vehicleMeetsCriteria($this->vehicle);
        });

        return $matching->values();
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
        $this->expandedSections[$section] = ! $this->expandedSections[$section];
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

    public function openVisitationSheet()
    {
        $this->showVisitationSheet = true;
        $this->visitationSubmitted = false;
        $this->visitationName = auth()->user()?->name ?? '';
        $this->visitationEmail = auth()->user()?->email ?? '';
        $this->visitationPhone = '';
        $this->visitationReason = '';
    }

    public function closeVisitationSheet()
    {
        $this->showVisitationSheet = false;
        $this->resetValidation();
    }

    public function submitVisitationRequest()
    {
        $this->validate([
            'visitationName' => 'required|string|max:255',
            'visitationEmail' => 'required|email',
            'visitationPhone' => 'nullable|string|max:50',
            'visitationReason' => 'nullable|string|max:1000',
        ], [], [
            'visitationName' => 'name',
            'visitationEmail' => 'email',
            'visitationPhone' => 'phone',
            'visitationReason' => 'visit reason',
        ]);

        $visitation = CarVisitationRequest::create([
            'vehicle_id' => $this->vehicleId,
            'user_id' => auth()->id(),
            'name' => $this->visitationName,
            'email' => $this->visitationEmail,
            'phone' => $this->visitationPhone ?: null,
            'visit_reason' => $this->visitationReason ?: null,
            'status' => 'pending',
        ]);

        SendVisitationRequestReceivedEmail::dispatch($visitation->id);

        $this->visitationSubmitted = true;
        $this->visitationName = '';
        $this->visitationEmail = '';
        $this->visitationPhone = '';
        $this->visitationReason = '';
    }

    public function render()
    {
        return view('livewire.customer.vehicle-detail')
            ->layout('layouts.customer');
    }
}
