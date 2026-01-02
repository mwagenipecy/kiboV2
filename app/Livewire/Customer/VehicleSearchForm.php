<?php

namespace App\Livewire\Customer;

use App\Enums\VehicleStatus;
use App\Models\Vehicle;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Livewire\Component;

class VehicleSearchForm extends Component
{
    public $make = '';
    public $model = '';
    public $minYear = '';
    public $condition = '';
    public $models = [];
    public $vehicleType = 'cars';

    public function mount($vehicleType = 'cars', $condition = null)
    {
        $this->vehicleType = $vehicleType;
        $this->condition = $condition;
    }

    public function updatedMake($value)
    {
        $this->model = ''; // Reset model when make changes
        
        if ($value) {
            $this->models = VehicleModel::where('vehicle_make_id', $value)
                ->where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name'])
                ->toArray();
        } else {
            $this->models = [];
        }
    }

    public function search()
    {
        // If no filters are selected, just go to search page to show all vehicles
        $hasFilters = $this->make || $this->model || $this->minYear || $this->condition;
        
        if ($hasFilters) {
            // Build query to check if vehicles exist
            $query = Vehicle::where('status', VehicleStatus::APPROVED);

            if ($this->make) {
                $query->where('vehicle_make_id', $this->make);
            }

            if ($this->model) {
                $query->where('vehicle_model_id', $this->model);
            }

            if ($this->minYear) {
                $query->where('year', '>=', $this->minYear);
            }

            if ($this->condition) {
                $query->where('condition', $this->condition);
            }

            // Check if any vehicles match the filters
            $vehicleCount = $query->count();

            if ($vehicleCount === 0) {
                // Show error message and stay on current page
                session()->flash('search_error', 'No vehicles found matching your search criteria. Please try different filters.');
                return;
            }
        }

        // Redirect to search page with filters
        $queryParams = array_filter([
            'make' => $this->make,
            'model' => $this->model,
            'minYear' => $this->minYear,
            'condition' => $this->condition,
        ]);

        return redirect()->route('cars.search', $queryParams);
    }

    public function render()
    {
        $makes = VehicleMake::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('livewire.customer.vehicle-search-form', [
            'makes' => $makes,
        ]);
    }
}
