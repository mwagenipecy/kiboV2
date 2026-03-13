<?php

namespace App\Livewire\Admin\Leasing;

use App\Models\Entity;
use App\Models\VehicleLease;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class LeaseList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'all';
    public $filterEntity = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    /** Confirm modals */
    public $showConfirmToggleStatusModal = false;
    public $leaseToToggleStatus = null;
    public $showConfirmToggleFeaturedModal = false;
    public $leaseToToggleFeatured = null;
    public $showConfirmDeleteModal = false;
    public $leaseToDelete = null;

    protected $queryString = ['search', 'filterStatus', 'filterEntity'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterEntity()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openConfirmToggleStatusModal($leaseId)
    {
        $this->leaseToToggleStatus = VehicleLease::findOrFail($leaseId);
        $this->showConfirmToggleStatusModal = true;
    }

    public function closeConfirmToggleStatusModal()
    {
        $this->showConfirmToggleStatusModal = false;
        $this->leaseToToggleStatus = null;
    }

    public function confirmToggleStatus()
    {
        if (!$this->leaseToToggleStatus) {
            $this->closeConfirmToggleStatusModal();
            return;
        }
        $this->leaseToToggleStatus->update([
            'status' => $this->leaseToToggleStatus->status === 'active' ? 'inactive' : 'active',
        ]);
        session()->flash('success', 'Lease status updated successfully.');
        $this->closeConfirmToggleStatusModal();
    }

    public function toggleStatus($leaseId)
    {
        $lease = VehicleLease::findOrFail($leaseId);
        $lease->update([
            'status' => $lease->status === 'active' ? 'inactive' : 'active',
        ]);
        session()->flash('success', 'Lease status updated successfully.');
    }

    public function openConfirmToggleFeaturedModal($leaseId)
    {
        $this->leaseToToggleFeatured = VehicleLease::findOrFail($leaseId);
        $this->showConfirmToggleFeaturedModal = true;
    }

    public function closeConfirmToggleFeaturedModal()
    {
        $this->showConfirmToggleFeaturedModal = false;
        $this->leaseToToggleFeatured = null;
    }

    public function confirmToggleFeatured()
    {
        if (!$this->leaseToToggleFeatured) {
            $this->closeConfirmToggleFeaturedModal();
            return;
        }
        $this->leaseToToggleFeatured->update([
            'is_featured' => !$this->leaseToToggleFeatured->is_featured,
        ]);
        session()->flash('success', 'Featured status updated successfully.');
        $this->closeConfirmToggleFeaturedModal();
    }

    public function toggleFeatured($leaseId)
    {
        $lease = VehicleLease::findOrFail($leaseId);
        $lease->update([
            'is_featured' => !$lease->is_featured,
        ]);
        session()->flash('success', 'Featured status updated successfully.');
    }

    public function openConfirmDeleteModal($leaseId)
    {
        $this->leaseToDelete = VehicleLease::findOrFail($leaseId);
        $this->showConfirmDeleteModal = true;
    }

    public function closeConfirmDeleteModal()
    {
        $this->showConfirmDeleteModal = false;
        $this->leaseToDelete = null;
    }

    public function confirmDeleteLease()
    {
        if (!$this->leaseToDelete) {
            $this->closeConfirmDeleteModal();
            return;
        }
        $this->leaseToDelete->delete();
        session()->flash('success', 'Lease deleted successfully.');
        $this->closeConfirmDeleteModal();
    }

    public function deleteLease($leaseId)
    {
        VehicleLease::findOrFail($leaseId)->delete();
        session()->flash('success', 'Lease deleted successfully.');
    }

    public function render()
    {
        $user = Auth::user();
        $userRole = $user->role ?? null;
        
        $query = VehicleLease::with(['entity'])
            // Filter by entity_id if user is not admin
            ->when($userRole !== 'admin', function ($q) use ($user) {
                if ($user->entity_id) {
                    // Show only leases with matching entity_id
                    $q->where('entity_id', $user->entity_id);
                } else {
                    // If no entity_id, show no leases (impossible condition)
                    $q->whereRaw('1 = 0');
                }
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('lease_title', 'like', '%' . $this->search . '%')
                  ->orWhere('vehicle_title', 'like', '%' . $this->search . '%')
                  ->orWhere('vehicle_make', 'like', '%' . $this->search . '%')
                  ->orWhere('vehicle_model', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        // Apply entity filter (only for admin)
        if ($userRole === 'admin' && $this->filterEntity) {
            $query->where('entity_id', $this->filterEntity);
        }

        $leases = $query->paginate(15);
        $entities = Entity::where('type', 'dealer')->get();

        // Get counts for status tabs (filtered by entity for non-admin)
        $countsQuery = VehicleLease::query();
        if ($userRole !== 'admin' && $user->entity_id) {
            $countsQuery->where('entity_id', $user->entity_id);
        } elseif ($userRole !== 'admin') {
            $countsQuery->whereRaw('1 = 0');
        }
        
        $counts = [
            'all' => (clone $countsQuery)->count(),
            'active' => (clone $countsQuery)->where('status', 'active')->count(),
            'inactive' => (clone $countsQuery)->where('status', 'inactive')->count(),
            'reserved' => (clone $countsQuery)->where('status', 'reserved')->count(),
        ];

        return view('livewire.admin.leasing.lease-list', [
            'leases' => $leases,
            'entities' => $entities,
            'counts' => $counts,
        ]);
    }
}
