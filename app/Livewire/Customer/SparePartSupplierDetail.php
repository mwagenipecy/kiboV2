<?php

namespace App\Livewire\Customer;

use App\Models\Agent;
use Livewire\Component;

class SparePartSupplierDetail extends Component
{
    public $supplierId;
    public $supplier;

    public function mount($id)
    {
        $this->supplierId = $id;
        $this->supplier = Agent::with([])
            ->where('agent_type', 'spare_part')
            ->where('approval_status', 'approved')
            ->where('status', 'active')
            ->findOrFail($id);
    }

    public function render()
    {
        // Get vehicle makes
        $makes = [];
        if ($this->supplier->vehicle_makes && count($this->supplier->vehicle_makes) > 0) {
            $makes = \App\Models\VehicleMake::whereIn('id', $this->supplier->vehicle_makes)->get();
        }

        return view('livewire.customer.spare-part-supplier-detail', [
            'makes' => $makes,
        ])->layout('layouts.customer', ['vehicleType' => 'spare-parts']);
    }
}

