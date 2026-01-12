<?php

namespace App\View\Components\Customer;

use App\Models\VehicleLease;
use Illuminate\View\Component;

class LeaseCarousel extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        $leases = \App\Models\VehicleLease::active()
            ->featured()
            ->orderBy('priority', 'desc')
            ->limit(8)
            ->get();
            
        return view('components.customer.lease-carousel', compact('leases'));
    }
}
