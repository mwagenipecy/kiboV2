<?php

namespace App\Livewire\Admin\TruckManagement;

use App\Enums\VehicleStatus;
use App\Models\Truck;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TruckList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterOrigin = '';
    
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterOrigin' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
    ];

    public function mount($filterStatus = null)
    {
        if ($filterStatus) {
            $this->filterStatus = $filterStatus;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterOrigin()
    {
        $this->resetPage();
    }

    public function sortByField($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'desc' ? 'asc' : 'desc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function render()
    {
        $user = Auth::user();
        $userRole = $user->role ?? null;
        
        $query = Truck::with(['make', 'model', 'entity', 'registeredBy'])
            // Filter by entity_id if user is not admin
            ->when($userRole !== 'admin', function ($q) use ($user) {
                if ($user->entity_id) {
                    // Show only trucks with matching entity_id
                    $q->where('entity_id', $user->entity_id);
                } else {
                    // If no entity_id, show no trucks (impossible condition)
                    $q->whereRaw('1 = 0');
                }
            });

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('registration_number', 'like', '%' . $this->search . '%')
                  ->orWhere('vin', 'like', '%' . $this->search . '%')
                  ->orWhereHas('make', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('model', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filters
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterOrigin) {
            $query->where('origin', $this->filterOrigin);
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $trucks = $query->paginate(15);

        return view('livewire.admin.truck-management.truck-list', [
            'trucks' => $trucks,
        ]);
    }
}
