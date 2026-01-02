<?php

namespace App\Livewire\Admin\VehicleRegistration;

use App\Enums\VehicleStatus;
use App\Models\Vehicle;
use Livewire\Component;
use Livewire\WithPagination;

class SoldVehicles extends Component
{
    use WithPagination;

    public $search = '';
    public $filterOrigin = '';
    public $sortField = 'sold_at';
    public $sortDirection = 'desc';

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

    public function render()
    {
        $query = Vehicle::with(['make', 'model', 'entity', 'registeredBy'])
            ->where('status', VehicleStatus::SOLD)
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

        return view('livewire.admin.vehicle-registration.sold-vehicles', [
            'vehicles' => $vehicles,
        ]);
    }
}
