<?php

namespace App\Livewire\Admin\VehicleRegistration;

use App\Enums\VehicleStatus;
use App\Models\Vehicle;
use App\Models\VehicleView;
use App\Models\VehicleLike;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VehicleDetail extends Component
{
    public $vehicleId;
    public $vehicle;
    public $isLiked = false;
    public $showStatusModal = false;
    public $newStatus;
    public $statusNotes = '';
    public $selectedImage = null;

    public function mount($vehicleId)
    {
        $this->vehicleId = $vehicleId;
        $this->loadVehicle();
        $this->trackView();
        $this->checkIfLiked();
    }

    public function loadVehicle()
    {
        $this->vehicle = Vehicle::with([
            'make',
            'model',
            'entity',
            'registeredBy',
            'approvedBy',
            'views.user',
            'likes.user',
        ])->findOrFail($this->vehicleId);

        $this->newStatus = $this->vehicle->status->value;
    }

    public function trackView()
    {
        $user = Auth::user();
        
        VehicleView::create([
            'vehicle_id' => $this->vehicleId,
            'user_id' => $user ? $user->id : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'viewed_at' => now(),
        ]);
    }

    public function checkIfLiked()
    {
        $user = Auth::user();
        if ($user) {
            $this->isLiked = $this->vehicle->isLikedBy($user);
        }
    }

    public function toggleLike()
    {
        $user = Auth::user();
        
        if (!$user) {
            session()->flash('error', 'You must be logged in to like a vehicle.');
            return;
        }

        $existingLike = VehicleLike::where('vehicle_id', $this->vehicleId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $this->isLiked = false;
            session()->flash('success', 'Vehicle removed from favorites.');
        } else {
            VehicleLike::create([
                'vehicle_id' => $this->vehicleId,
                'user_id' => $user->id,
            ]);
            $this->isLiked = true;
            session()->flash('success', 'Vehicle added to favorites!');
        }

        $this->loadVehicle();
    }

    public function openStatusModal()
    {
        $this->showStatusModal = true;
    }

    public function closeStatusModal()
    {
        $this->showStatusModal = false;
        $this->statusNotes = '';
    }

    public function updateStatus()
    {
        $this->validate([
            'newStatus' => 'required|string',
        ]);

        $updateData = [
            'status' => $this->newStatus,
        ];

        // Add notes if provided
        if ($this->statusNotes) {
            $updateData['notes'] = $this->vehicle->notes 
                ? $this->vehicle->notes . "\n\n" . now()->format('Y-m-d H:i') . ": " . $this->statusNotes
                : now()->format('Y-m-d H:i') . ": " . $this->statusNotes;
        }

        // If approved, set approval details
        if ($this->newStatus === VehicleStatus::APPROVED->value) {
            $updateData['approved_at'] = now();
            $updateData['approved_by'] = Auth::id();
        }

        // If sold, set sold date
        if ($this->newStatus === VehicleStatus::SOLD->value) {
            $updateData['sold_at'] = now();
        }

        $this->vehicle->update($updateData);

        session()->flash('success', 'Vehicle status updated successfully!');
        
        $this->closeStatusModal();
        $this->loadVehicle();
    }

    public function approveVehicle()
    {
        $this->vehicle->update([
            'status' => VehicleStatus::APPROVED,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        session()->flash('success', 'Vehicle approved successfully!');
        $this->loadVehicle();
    }

    public function render()
    {
        return view('livewire.admin.vehicle-registration.vehicle-detail');
    }
}
