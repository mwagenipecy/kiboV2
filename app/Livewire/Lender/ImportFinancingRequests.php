<?php

namespace App\Livewire\Lender;

use App\Models\ImportFinancingRequest;
use Livewire\Component;
use Livewire\WithPagination;

class ImportFinancingRequests extends Component
{
    use WithPagination;

    public string $search = '';
    public string $typeFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Lenders only see requests that have been approved and sent to lenders
        $requests = ImportFinancingRequest::whereIn('status', ['with_lenders', 'offer_received', 'accepted', 'completed'])
            ->withCount('offers')
            ->with(['user'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('reference_number', 'like', '%' . $this->search . '%')
                      ->orWhere('vehicle_make', 'like', '%' . $this->search . '%')
                      ->orWhere('vehicle_model', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('request_type', $this->typeFilter);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.lender.import-financing-requests', [
            'requests' => $requests,
        ])->layout('layouts.lender');
    }
}

