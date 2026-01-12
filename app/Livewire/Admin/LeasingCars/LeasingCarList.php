<?php

namespace App\Livewire\Admin\LeasingCars;

use App\Models\LeasingCar;
use App\Models\Entity;
use App\Models\VehicleMake;
use Livewire\Component;
use Livewire\WithPagination;

class LeasingCarList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'all';
    public $filterEntity = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => 'all'],
    ];

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

    public function toggleStatus($carId)
    {
        $car = LeasingCar::findOrFail($carId);
        
        if ($car->status === 'available') {
            $car->update(['status' => 'unavailable']);
            session()->flash('success', 'Leasing car marked as unavailable.');
        } else if ($car->status === 'unavailable') {
            $car->update(['status' => 'available']);
            session()->flash('success', 'Leasing car marked as available.');
        }
    }

    public function approveCar($carId)
    {
        $car = LeasingCar::findOrFail($carId);
        
        $car->update([
            'status' => 'available',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);
        
        session()->flash('success', 'Leasing car approved successfully.');
    }

    public function deleteCar($carId)
    {
        $car = LeasingCar::findOrFail($carId);
        $car->delete();
        
        session()->flash('success', 'Leasing car deleted successfully.');
    }

    public function render()
    {
        $query = LeasingCar::with(['make', 'model', 'entity', 'registeredBy'])
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
            ->when($this->filterStatus !== 'all', function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->when($this->filterEntity, function ($q) {
                $q->where('entity_id', $this->filterEntity);
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        $leasingCars = $query->paginate(15);
        $entities = Entity::all();
        
        // Get counts for status badges
        $counts = [
            'all' => LeasingCar::count(),
            'pending' => LeasingCar::where('status', 'pending')->count(),
            'available' => LeasingCar::where('status', 'available')->count(),
            'leased' => LeasingCar::where('status', 'leased')->count(),
            'maintenance' => LeasingCar::where('status', 'maintenance')->count(),
        ];

        return view('livewire.admin.leasing-cars.leasing-car-list', [
            'leasingCars' => $leasingCars,
            'entities' => $entities,
            'counts' => $counts,
        ]);
    }
}
