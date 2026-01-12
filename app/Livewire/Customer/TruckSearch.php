<?php

namespace App\Livewire\Customer;

use App\Enums\VehicleStatus;
use App\Models\Truck;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Livewire\Component;
use Livewire\WithPagination;

class TruckSearch extends Component
{
    use WithPagination;

    // Filter parameters
    public $search = '';
    public $make = '';
    public $model = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $minYear = '';
    public $maxYear = '';
    public $minMileage = '';
    public $maxMileage = '';
    public $transmission = [];
    public $bodyType = [];
    public $fuelType = [];
    public $truckType = [];
    public $condition = '';
    
    // Sort
    public $sortBy = 'relevance';
    
    // UI states
    public $showFilters = false;
    public $savedTrucks = [];
    
    // Order modals
    public $showValuationModal = false;
    public $showFinancingModal = false;
    public $showCashPurchaseModal = false;
    public $selectedTruckId = null;
    
    public $expandedSections = [
        'sort' => true,
        'makeModel' => false,
        'condition' => false,
        'price' => false,
        'year' => false,
        'mileage' => false,
        'gearbox' => false,
        'bodyType' => false,
        'fuelType' => false,
        'truckType' => false,
    ];
    
    protected $queryString = [
        'search' => ['except' => ''],
        'make' => ['except' => ''],
        'model' => ['except' => ''],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
        'minYear' => ['except' => ''],
        'maxYear' => ['except' => ''],
        'sortBy' => ['except' => 'relevance'],
        'condition' => ['except' => ''],
    ];

    public function mount()
    {
        // Load saved trucks from session
        $this->savedTrucks = session()->get('saved_trucks', []);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMake()
    {
        $this->model = ''; // Reset model when make changes
        $this->resetPage();
    }

    public function updatedAnyFilter()
    {
        $this->resetPage();
    }

    public function toggleSave($truckId)
    {
        if (in_array($truckId, $this->savedTrucks)) {
            $this->savedTrucks = array_diff($this->savedTrucks, [$truckId]);
        } else {
            $this->savedTrucks[] = $truckId;
        }
        session()->put('saved_trucks', $this->savedTrucks);
    }

    public function toggleSection($section)
    {
        $this->expandedSections[$section] = !$this->expandedSections[$section];
    }

    public function clearFilters()
    {
        $this->reset([
            'search', 'make', 'model', 'minPrice', 'maxPrice', 
            'minYear', 'maxYear', 'minMileage', 'maxMileage',
            'transmission', 'bodyType', 'fuelType', 'truckType', 'condition', 'sortBy'
        ]);
        $this->showFilters = false;
        $this->resetPage();
    }

    public function openValuationModal($truckId)
    {
        $this->selectedTruckId = $truckId;
        $this->showValuationModal = true;
        $this->dispatch('open-valuation-modal', truckId: $truckId);
    }

    public function openFinancingModal($truckId)
    {
        $this->selectedTruckId = $truckId;
        $this->showFinancingModal = true;
        $this->dispatch('open-financing-modal', truckId: $truckId);
    }

    public function openCashPurchaseModal($truckId)
    {
        $this->selectedTruckId = $truckId;
        $this->showCashPurchaseModal = true;
        $this->dispatch('open-cash-purchase-modal', truckId: $truckId);
    }

    public function closeModals()
    {
        $this->showValuationModal = false;
        $this->showFinancingModal = false;
        $this->showCashPurchaseModal = false;
        $this->selectedTruckId = null;
    }

    public function render()
    {
        $query = Truck::with(['make', 'model', 'entity'])
            ->where('status', VehicleStatus::APPROVED);

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

        // Apply filters
        if ($this->make) {
            $query->where('vehicle_make_id', $this->make);
        }

        if ($this->model) {
            $query->where('vehicle_model_id', $this->model);
        }

        if ($this->minPrice) {
            $query->where('price', '>=', $this->minPrice);
        }

        if ($this->maxPrice) {
            $query->where('price', '<=', $this->maxPrice);
        }

        if ($this->minYear) {
            $query->where('year', '>=', $this->minYear);
        }

        if ($this->maxYear) {
            $query->where('year', '<=', $this->maxYear);
        }

        if ($this->minMileage) {
            $query->where('mileage', '>=', $this->minMileage);
        }

        if ($this->maxMileage) {
            $query->where('mileage', '<=', $this->maxMileage);
        }

        if (!empty($this->transmission)) {
            $query->whereIn('transmission', $this->transmission);
        }

        if (!empty($this->bodyType)) {
            $query->whereIn('body_type', $this->bodyType);
        }

        if (!empty($this->fuelType)) {
            $query->whereIn('fuel_type', $this->fuelType);
        }

        if (!empty($this->truckType)) {
            $query->whereIn('truck_type', $this->truckType);
        }

        if ($this->condition) {
            $query->where('condition', $this->condition);
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'year_new':
                $query->orderBy('year', 'desc');
                break;
            case 'year_old':
                $query->orderBy('year', 'asc');
                break;
            case 'mileage_low':
                $query->orderBy('mileage', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $trucks = $query->paginate(16);
        $totalCount = $trucks->total();

        // Get available makes and models for filters
        $makes = VehicleMake::where('status', 'active')->orderBy('name')->get();
        
        $models = collect();
        if ($this->make) {
            $models = VehicleModel::where('vehicle_make_id', $this->make)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        }

        // Get unique values for filters
        $availableTruckTypes = Truck::whereNotNull('truck_type')
            ->where('status', VehicleStatus::APPROVED)
            ->distinct()
            ->pluck('truck_type')
            ->filter()
            ->sort()
            ->values();

        $availableBodyTypes = Truck::whereNotNull('body_type')
            ->where('status', VehicleStatus::APPROVED)
            ->distinct()
            ->pluck('body_type')
            ->filter()
            ->sort()
            ->values();

        $availableFuelTypes = Truck::whereNotNull('fuel_type')
            ->where('status', VehicleStatus::APPROVED)
            ->distinct()
            ->pluck('fuel_type')
            ->filter()
            ->sort()
            ->values();

        $availableTransmissions = Truck::whereNotNull('transmission')
            ->where('status', VehicleStatus::APPROVED)
            ->distinct()
            ->pluck('transmission')
            ->filter()
            ->sort()
            ->values();

        return view('livewire.customer.truck-search', [
            'trucks' => $trucks,
            'totalCount' => $totalCount,
            'makes' => $makes,
            'models' => $models,
            'availableTruckTypes' => $availableTruckTypes,
            'availableBodyTypes' => $availableBodyTypes,
            'availableFuelTypes' => $availableFuelTypes,
            'availableTransmissions' => $availableTransmissions,
        ])->layout('layouts.customer', ['vehicleType' => 'trucks']);
    }
}

