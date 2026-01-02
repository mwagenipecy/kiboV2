<?php

namespace App\Livewire\Customer;

use App\Enums\VehicleStatus;
use App\Models\Vehicle;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Livewire\Component;
use Livewire\WithPagination;

class VehicleSearch extends Component
{
    use WithPagination;

    // Filter parameters
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
    public $condition = '';
    
    // Sort
    public $sortBy = 'relevance';
    
    // UI states
    public $showFilters = false;
    public $savedVehicles = [];
    
    protected $queryString = [
        'make',
        'model',
        'minPrice',
        'maxPrice',
        'minYear',
        'maxYear',
        'sortBy',
    ];

    public function mount()
    {
        // Load saved vehicles from session
        $this->savedVehicles = session()->get('saved_vehicles', []);
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

    public function toggleSave($vehicleId)
    {
        if (in_array($vehicleId, $this->savedVehicles)) {
            $this->savedVehicles = array_diff($this->savedVehicles, [$vehicleId]);
        } else {
            $this->savedVehicles[] = $vehicleId;
        }
        session()->put('saved_vehicles', $this->savedVehicles);
    }

    public function clearFilters()
    {
        $this->reset([
            'make', 'model', 'minPrice', 'maxPrice', 
            'minYear', 'maxYear', 'minMileage', 'maxMileage',
            'transmission', 'bodyType', 'fuelType', 'condition', 'sortBy'
        ]);
        $this->showFilters = false;
        $this->resetPage();
    }

    public function render()
    {
        $query = Vehicle::with(['make', 'model', 'entity'])
            ->where('status', VehicleStatus::APPROVED);

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

        $vehicles = $query->paginate(15);
        $totalCount = $vehicles->total();

        // Get available makes and models for filters
        $makes = VehicleMake::where('status', 'active')->orderBy('name')->get();
        
        $models = collect();
        if ($this->make) {
            $models = VehicleModel::where('vehicle_make_id', $this->make)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        }

        return view('livewire.customer.vehicle-search', [
            'vehicles' => $vehicles,
            'totalCount' => $totalCount,
            'makes' => $makes,
            'models' => $models,
        ])->layout('layouts.customer');
    }
}
