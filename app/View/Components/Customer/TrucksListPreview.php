<?php

namespace App\View\Components\Customer;

use App\Enums\VehicleStatus;
use App\Models\Truck;
use Illuminate\View\Component;

class TrucksListPreview extends Component
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
        $trucks = Truck::with(['make', 'model', 'entity'])
            ->where('status', VehicleStatus::APPROVED)
            ->latest()
            ->limit(8)
            ->get();

        return view('components.customer.trucks-list-preview', compact('trucks'));
    }
}
