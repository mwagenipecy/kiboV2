<?php

namespace App\Livewire\Admin;

use App\Models\CarVisitationRequest;
use Livewire\Component;

class VisitationRequests extends Component
{
    public string $filter = 'all';

    public function render()
    {
        $query = CarVisitationRequest::with(['vehicle.make', 'vehicle.model'])
            ->latest();

        if ($this->filter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($this->filter === 'scheduled') {
            $query->where('status', 'scheduled');
        } elseif ($this->filter === 'completed') {
            $query->where('status', 'completed');
        } elseif ($this->filter === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        $requests = $query->get();

        $counts = [
            'all' => CarVisitationRequest::count(),
            'pending' => CarVisitationRequest::where('status', 'pending')->count(),
            'scheduled' => CarVisitationRequest::where('status', 'scheduled')->count(),
            'completed' => CarVisitationRequest::where('status', 'completed')->count(),
            'cancelled' => CarVisitationRequest::where('status', 'cancelled')->count(),
        ];

        return view('livewire.admin.visitation-requests', [
            'requests' => $requests,
            'counts' => $counts,
        ])->layout('layouts.admin');
    }
}
