<?php

namespace App\Livewire\Admin;

use App\Models\AgizaImportRequest;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AgizaImportRequests extends Component
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
        $requests = AgizaImportRequest::with(['user', 'assignedAgent'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('request_number', 'like', '%'.$this->search.'%')
                        ->orWhere('customer_name', 'like', '%'.$this->search.'%')
                        ->orWhere('customer_email', 'like', '%'.$this->search.'%')
                        ->orWhere('vehicle_link', 'like', '%'.$this->search.'%')
                        ->orWhere('vehicle_make', 'like', '%'.$this->search.'%')
                        ->orWhere('vehicle_model', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('request_type', $this->typeFilter);
            })
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => AgizaImportRequest::count(),
            'pending' => AgizaImportRequest::where('status', 'pending')->count(),
            'under_review' => AgizaImportRequest::where('status', 'under_review')->count(),
            'quote_provided' => AgizaImportRequest::where('status', 'quote_provided')->count(),
            'in_progress' => AgizaImportRequest::where('status', 'in_progress')->count(),
        ];

        return view('livewire.admin.agiza-import-requests', [
            'requests' => $requests,
            'stats' => $stats,
        ]);
    }
}
