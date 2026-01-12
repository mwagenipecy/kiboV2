<?php

namespace App\Livewire\Admin\LeasingCars;

use App\Models\LeasingCar;
use Livewire\Component;

class LeasingCarView extends Component
{
    public LeasingCar $car;

    public function mount($id)
    {
        $this->car = LeasingCar::with(['make', 'model', 'entity', 'registeredBy', 'approvedBy'])
            ->findOrFail($id);
            
        // Increment view count
        $this->car->incrementViews();
    }

    public function render()
    {
        return view('livewire.admin.leasing-cars.leasing-car-view');
    }
}
