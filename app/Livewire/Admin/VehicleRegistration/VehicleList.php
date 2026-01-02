<?php

namespace App\Livewire\Admin\VehicleRegistration;

use App\Enums\VehicleStatus;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class VehicleList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterOrigin = '';
    public $filterMake = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    public $selectedVehicle = null;
    public $showStatusModal = false;
    public $newStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterOrigin' => ['except' => ''],
        'filterMake' => ['except' => ''],
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

    public function openStatusModal($vehicleId)
    {
        $this->selectedVehicle = Vehicle::findOrFail($vehicleId);
        $this->newStatus = $this->selectedVehicle->status->value;
        $this->showStatusModal = true;
    }

    public function closeStatusModal()
    {
        $this->showStatusModal = false;
        $this->selectedVehicle = null;
        $this->newStatus = '';
    }

    public function updateStatus()
    {
        if (!$this->selectedVehicle) {
            return;
        }

        $this->validate([
            'newStatus' => 'required|string',
        ]);

        $this->selectedVehicle->update([
            'status' => $this->newStatus,
        ]);

        // If approved, set approval details
        if ($this->newStatus === VehicleStatus::APPROVED->value) {
            $this->selectedVehicle->update([
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);
        }

        // If sold, set sold date
        if ($this->newStatus === VehicleStatus::SOLD->value) {
            $this->selectedVehicle->update([
                'sold_at' => now(),
            ]);
        }

        session()->flash('success', 'Vehicle status updated successfully!');
        
        $this->closeStatusModal();
    }

    public function deleteVehicle($vehicleId)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        $vehicle->delete();

        session()->flash('success', 'Vehicle deleted successfully!');
    }

    public function render()
    {
        $query = Vehicle::with(['make', 'model', 'entity', 'registeredBy'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('registration_number', 'like', '%' . $this->search . '%')
                        ->orWhere('vin', 'like', '%' . $this->search . '%')
                        ->orWhereHas('make', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('model', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->when($this->filterOrigin, function ($q) {
                $q->where('origin', $this->filterOrigin);
            })
            ->when($this->filterMake, function ($q) {
                $q->where('vehicle_make_id', $this->filterMake);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $vehicles = $query->paginate(15);

        return view('livewire.admin.vehicle-registration.vehicle-list', [
            'vehicles' => $vehicles,
        ]);
    }
}
