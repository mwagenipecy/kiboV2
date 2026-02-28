<?php

namespace App\View\Components\Customer;

use App\Enums\VehicleStatus;
use App\Models\Vehicle;
use Illuminate\View\Component;

class CarsListPreview extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents of the component.
     */
    public function render()
    {
        $vehicles = Vehicle::with(['make', 'model', 'entity'])
            ->where('status', VehicleStatus::APPROVED)
            ->latest()
            ->limit(8)
            ->get();

        return view('components.customer.cars-list-preview', compact('vehicles'));
    }
}
