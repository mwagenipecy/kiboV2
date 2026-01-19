<?php

namespace App\Livewire\Admin;

use App\Models\CarRequest;
use Livewire\Component;

class CarRequests extends Component
{
    public function render()
    {
        $requests = CarRequest::withCount('offers')
            ->with(['make', 'model'])
            ->latest()
            ->get();

        return view('livewire.admin.car-requests', [
            'requests' => $requests,
        ])->layout('layouts.admin');
    }
}


