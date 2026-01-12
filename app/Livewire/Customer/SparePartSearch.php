<?php

namespace App\Livewire\Customer;

use App\Models\Agent;
use App\Models\VehicleMake;
use Livewire\Component;
use Livewire\WithPagination;

class SparePartSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedMake = '';
    public $sortBy = 'distance'; // distance, name
    public $userLatitude = null;
    public $userLongitude = null;
    public $maxDistance = 50; // km
    public $showLocationModal = false;

    public $vehicleMakes = [];

    protected $queryString = ['search', 'selectedMake', 'sortBy'];

    public function mount()
    {
        $this->vehicleMakes = VehicleMake::where('status', 'active')
            ->orderBy('name')
            ->get();
        
        // Try to get user location from session
        $this->userLatitude = session('user_latitude');
        $this->userLongitude = session('user_longitude');
        
        // Handle make filter from query string
        if (request()->has('make')) {
            $this->selectedMake = request()->get('make');
        }
    }

    public function getCurrentLocation()
    {
        $this->showLocationModal = true;
        $this->dispatch('request-location');
    }

    public function setLocation($latitude, $longitude)
    {
        $this->userLatitude = $latitude;
        $this->userLongitude = $longitude;
        session(['user_latitude' => $latitude, 'user_longitude' => $longitude]);
        $this->showLocationModal = false;
        $this->resetPage();
    }

    public function clearLocation()
    {
        $this->userLatitude = null;
        $this->userLongitude = null;
        session()->forget(['user_latitude', 'user_longitude']);
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedMake()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    /**
     * Calculate distance between two coordinates (Haversine formula)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        if (!$lat1 || !$lon1 || !$lat2 || !$lon2) {
            return null;
        }

        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return round($distance, 1);
    }

    public function render()
    {
        $query = Agent::where('agent_type', 'spare_part')
            ->where('approval_status', 'approved')
            ->where('status', 'active');

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('company_name', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%')
                    ->orWhere('spare_part_details', 'like', '%' . $this->search . '%');
            });
        }

        // Vehicle make filter
        if ($this->selectedMake) {
            $query->whereJsonContains('vehicle_makes', (int)$this->selectedMake);
        }

        $suppliers = $query->get();

        // Calculate distances and filter by max distance if location is set
        if ($this->userLatitude && $this->userLongitude) {
            $suppliers = $suppliers->map(function ($supplier) {
                if ($supplier->latitude && $supplier->longitude) {
                    $supplier->distance = $this->calculateDistance(
                        $this->userLatitude,
                        $this->userLongitude,
                        $supplier->latitude,
                        $supplier->longitude
                    );
                } else {
                    $supplier->distance = null;
                }
                return $supplier;
            })->filter(function ($supplier) {
                return $supplier->distance === null || $supplier->distance <= $this->maxDistance;
            });

            // Sort by distance
            if ($this->sortBy === 'distance') {
                $suppliers = $suppliers->sortBy('distance');
            }
        } else {
            // If no location, set distance to null
            $suppliers = $suppliers->map(function ($supplier) {
                $supplier->distance = null;
                return $supplier;
            });
        }

        // Sort by name if not sorting by distance
        if ($this->sortBy === 'name') {
            $suppliers = $suppliers->sortBy('name');
        }

        // Paginate manually
        $perPage = 12;
        $currentPage = request()->get('page', 1);
        $items = $suppliers->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedSuppliers = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $suppliers->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.customer.spare-part-search', [
            'suppliers' => $paginatedSuppliers,
        ]);
    }
}

