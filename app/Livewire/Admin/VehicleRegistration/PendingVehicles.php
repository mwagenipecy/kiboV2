<?php

namespace App\Livewire\Admin\VehicleRegistration;

use App\Enums\VehicleStatus;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PendingVehicles extends Component
{
    use WithPagination;

    public $search = '';
    public $filterOrigin = '';
    public $sortField = 'created_at';
    public $sortDirection = 'asc'; // Oldest first for pending
    
    public $selectedVehicle = null;
    public $showApprovalModal = false;
    public $approvalNotes = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterOrigin' => ['except' => ''],
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

    public function openApprovalModal($vehicleId)
    {
        $this->selectedVehicle = Vehicle::findOrFail($vehicleId);
        $this->showApprovalModal = true;
    }

    public function closeApprovalModal()
    {
        $this->showApprovalModal = false;
        $this->selectedVehicle = null;
        $this->approvalNotes = '';
    }

    public function approveVehicle($vehicleId)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        
        $vehicle->update([
            'status' => VehicleStatus::APPROVED,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        session()->flash('success', 'Vehicle approved successfully!');
    }

    public function rejectVehicle()
    {
        if (!$this->selectedVehicle) {
            return;
        }

        $this->selectedVehicle->update([
            'status' => VehicleStatus::REMOVED,
            'notes' => $this->approvalNotes,
        ]);

        session()->flash('success', 'Vehicle rejected successfully!');
        
        $this->closeApprovalModal();
    }

    public function holdVehicle($vehicleId)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        
        $vehicle->update([
            'status' => VehicleStatus::HOLD,
        ]);

        session()->flash('success', 'Vehicle put on hold!');
    }

    public function render()
    {
        $user = Auth::user();
        $userRole = $user->role ?? null;
        
        $query = Vehicle::with(['make', 'model', 'entity', 'registeredBy'])
            ->whereIn('status', [VehicleStatus::PENDING, VehicleStatus::AWAITING_APPROVAL])
            // Filter by entity_id if user is not admin
            ->when($userRole !== 'admin', function ($q) use ($user) {
                if ($user->entity_id) {
                    // Show only vehicles with matching entity_id
                    $q->where('entity_id', $user->entity_id);
                } else {
                    // If no entity_id, show no vehicles (impossible condition)
                    $q->whereRaw('1 = 0');
                }
            })
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
            ->when($this->filterOrigin, function ($q) {
                $q->where('origin', $this->filterOrigin);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $vehicles = $query->paginate(15);

        return view('livewire.admin.vehicle-registration.pending-vehicles', [
            'vehicles' => $vehicles,
        ]);
    }
}
