<?php

namespace App\Livewire\Admin\Leasing;

use App\Models\Entity;
use App\Models\VehicleLease;
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
        $query = VehicleLease::with(['entity'])
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

        // Apply entity filter
        if ($this->filterEntity) {
            $query->where('entity_id', $this->filterEntity);
        }

        $leases = $query->paginate(15);
        $entities = Entity::where('type', 'dealer')->get();

        // Get counts for status tabs
        $counts = [
            'all' => VehicleLease::count(),
            'active' => VehicleLease::where('status', 'active')->count(),
            'inactive' => VehicleLease::where('status', 'inactive')->count(),
            'reserved' => VehicleLease::where('status', 'reserved')->count(),
        ];

        return view('livewire.admin.leasing.lease-list', [
            'leases' => $leases,
            'entities' => $entities,
            'counts' => $counts,
        ]);
    }
}
