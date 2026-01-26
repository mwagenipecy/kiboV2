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

    public function toggleStatus($leaseId)
    {
        $lease = VehicleLease::findOrFail($leaseId);
        $lease->update([
            'status' => $lease->status === 'active' ? 'inactive' : 'active',
        ]);

        session()->flash('success', 'Lease status updated successfully.');
    }

    public function toggleFeatured($leaseId)
    {
        $lease = VehicleLease::findOrFail($leaseId);
        $lease->update([
            'is_featured' => !$lease->is_featured,
        ]);

        session()->flash('success', 'Featured status updated successfully.');
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
