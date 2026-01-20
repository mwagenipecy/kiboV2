<?php

namespace App\Livewire\Admin;

use App\Models\ImportFinancingRequest;
use Livewire\Component;
use Livewire\WithPagination;

class ImportFinancingRequests extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $typeFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $requests = ImportFinancingRequest::with(['user', 'reviewer'])
            ->withCount('offers')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('reference_number', 'like', '%' . $this->search . '%')
                      ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                      ->orWhere('customer_email', 'like', '%' . $this->search . '%')
                      ->orWhere('vehicle_make', 'like', '%' . $this->search . '%')
                      ->orWhere('vehicle_model', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('request_type', $this->typeFilter);
            })
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => ImportFinancingRequest::count(),
            'pending' => ImportFinancingRequest::where('status', 'pending')->count(),
            'with_lenders' => ImportFinancingRequest::where('status', 'with_lenders')->count(),
            'completed' => ImportFinancingRequest::where('status', 'completed')->count(),
        ];

        return view('livewire.admin.import-financing-requests', [
            'requests' => $requests,
            'stats' => $stats,
        ])->layout('layouts.admin');
    }
}

