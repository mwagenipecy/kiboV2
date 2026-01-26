<?php

namespace App\Livewire\Customer;

use App\Models\Agent;
use App\Models\VehicleMake;
use App\Models\GarageServiceOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class GarageSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedMake = '';
    public $sortBy = 'distance'; // distance, name, rating
    public $userLatitude = null;
    public $userLongitude = null;
    public $maxDistance = 50; // km
    public $showLocationModal = false;
    public $page = 1;

    public $vehicleMakes = [];
    
    // Order search properties
    public $showOrderModal = false;
    public $selectedOrder = null;
    public $orderNotFound = false;

    protected $queryString = ['search', 'selectedMake', 'sortBy'];

    public function mount()
    {
        $this->vehicleMakes = VehicleMake::where('status', 'active')
            ->orderBy('name')
            ->get();
        
        // Try to get user location from session or request
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
        $this->orderNotFound = false;
        $this->showOrderModal = false;
        $this->selectedOrder = null;
        
        // Check if search looks like an order number
        if ($this->isOrderNumber($this->search)) {
            $this->searchOrder();
        }
    }
    
    /**
     * Check if search string looks like an order number
     */
    private function isOrderNumber($search)
    {
        if (empty($search)) {
            return false;
        }
        
        // Check if it starts with "GS-" or is numeric/alphanumeric
        return preg_match('/^GS-?[A-Z0-9]+$/i', trim($search)) || 
               preg_match('/^[A-Z0-9]{6,}$/i', trim($search));
    }
    
    /**
     * Search for order by order number
     */
    public function searchOrder()
    {
        $this->orderNotFound = false;
        $this->selectedOrder = null;
        
        if (empty($this->search)) {
            return;
        }
        
        // Normalize order number (remove spaces, ensure GS- prefix)
        $orderNumber = strtoupper(trim($this->search));
        if (!str_starts_with($orderNumber, 'GS-')) {
            // Try to find with or without prefix
            $order = GarageServiceOrder::where(function($query) use ($orderNumber) {
                $query->where('order_number', $orderNumber)
                      ->orWhere('order_number', 'GS-' . $orderNumber)
                      ->orWhere('order_number', 'like', '%' . $orderNumber . '%');
            });
        } else {
            $order = GarageServiceOrder::where('order_number', $orderNumber);
        }
        
        // Only show orders belonging to the logged-in user
        if (Auth::check()) {
            $order->where('user_id', Auth::id());
        } else {
            // If not logged in, don't show any orders
            $this->orderNotFound = true;
            return;
        }
        
        $order = $order->with(['agent', 'user'])->first();
        
        if ($order) {
            $this->selectedOrder = $order;
            $this->showOrderModal = true;
        } else {
            $this->orderNotFound = true;
            $this->showOrderModal = true;
        }
    }
    
    /**
     * Close order modal
     */
    public function closeOrderModal()
    {
        $this->showOrderModal = false;
        $this->selectedOrder = null;
        $this->orderNotFound = false;
        $this->search = '';
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
        // If searching for an order number, skip garage search
        if ($this->search && $this->isOrderNumber($this->search)) {
            $emptyCollection = collect([]);
            $perPage = 12;
            $paginatedGarages = new \Illuminate\Pagination\LengthAwarePaginator(
                $emptyCollection,
                0,
                $perPage,
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            
            return view('livewire.customer.garage-search', [
                'garages' => $paginatedGarages,
            ]);
        }
        
        $query = Agent::where('agent_type', 'garage_owner')
            ->where('approval_status', 'approved')
            ->where('status', 'active');

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('company_name', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%');
            });
        }

        // Vehicle make filter
        if ($this->selectedMake) {
            $query->whereJsonContains('vehicle_makes', (int)$this->selectedMake);
        }

        $garages = $query->get();

        // Calculate distances and filter by max distance if location is set
        if ($this->userLatitude && $this->userLongitude) {
            $garages = $garages->map(function ($garage) {
                if ($garage->latitude && $garage->longitude) {
                    $garage->distance = $this->calculateDistance(
                        $this->userLatitude,
                        $this->userLongitude,
                        $garage->latitude,
                        $garage->longitude
                    );
                } else {
                    $garage->distance = null;
                }
                return $garage;
            })->filter(function ($garage) {
                return $garage->distance === null || $garage->distance <= $this->maxDistance;
            });

            // Sort by distance
            if ($this->sortBy === 'distance') {
                $garages = $garages->sortBy('distance');
            }
        } else {
            // If no location, set distance to null
            $garages = $garages->map(function ($garage) {
                $garage->distance = null;
                return $garage;
            });
        }

        // Sort by name if not sorting by distance
        if ($this->sortBy === 'name') {
            $garages = $garages->sortBy('name');
        }

        // Convert to collection and paginate
        $perPage = 12;
        $currentPage = request()->get('page', 1);
        $items = $garages->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedGarages = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $garages->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.customer.garage-search', [
            'garages' => $paginatedGarages,
        ]);
    }

    public function openBookingModalForGarage($garageId, $garageName, $services = [])
    {
        $garage = Agent::find($garageId);
        $availableServices = [];
        
        if ($garage && !empty($garage->services)) {
            $availableServices = is_array($garage->services) ? array_values($garage->services) : [];
        }
        
        if (!empty($services) && is_array($services)) {
            $availableServices = array_merge($availableServices, $services);
            $availableServices = array_unique($availableServices);
        }
        
        $this->dispatch('openBookingModal', [
            'agentId' => $garageId,
            'agentName' => $garageName,
            'availableServices' => array_values($availableServices),
            'services' => [],
            'serviceType' => null,
        ]);
    }
}

