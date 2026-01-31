<?php

namespace App\Livewire\Admin\TruckManagement;

use App\Enums\VehicleStatus;
use App\Models\Truck;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TruckDetail extends Component
{
    public $truckId;
    public $truck;
    public $showStatusModal = false;
    public $newStatus;
    public $statusNotes = '';
    public $selectedImage = null;

    public function mount($truckId)
    {
        $this->truckId = $truckId;
        $this->loadTruck();
    }

    public function loadTruck()
    {
        $this->truck = Truck::with([
            'make',
            'model',
            'entity',
            'registeredBy',
            'approvedBy',
        ])->findOrFail($this->truckId);

        $this->newStatus = $this->truck->status->value;
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

        // If approved, set approval details
        if ($this->newStatus === VehicleStatus::APPROVED->value) {
            $updateData['approved_at'] = now();
            $updateData['approved_by'] = Auth::id();
        }

        // If sold, set sold date
        if ($this->newStatus === VehicleStatus::SOLD->value) {
            $updateData['sold_at'] = now();
        }

        // Add notes if provided
        if ($this->statusNotes) {
            $updateData['notes'] = ($this->truck->notes ? $this->truck->notes . "\n\n" : '') . 
                now()->format('Y-m-d H:i:s') . ': ' . $this->statusNotes;
        }

        $this->truck->update($updateData);

        session()->flash('success', 'Truck status updated successfully!');
        
        $this->loadTruck();
        $this->closeStatusModal();
    }

    public function deleteTruck()
    {
        $this->truck->delete();
        
        session()->flash('success', 'Truck deleted successfully!');
        
        return redirect()->route('admin.trucks.index');
    }

    public function render()
    {
        return view('livewire.admin.truck-management.truck-detail');
    }
}
