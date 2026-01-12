<?php

namespace App\Livewire\Admin\Leasing;

use App\Models\VehicleLease;
use Livewire\Component;

class LeaseView extends Component
{
    public $lease;

    public function mount($id)
    {
        $this->lease = VehicleLease::with(['entity'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.admin.leasing.lease-view');
    }
}
