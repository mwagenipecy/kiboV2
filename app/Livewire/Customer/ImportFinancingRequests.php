<?php

namespace App\Livewire\Customer;

use App\Models\ImportFinancingRequest;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.customer', ['vehicleType' => 'import-financing'])]
class ImportFinancingRequests extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $requests = ImportFinancingRequest::where('user_id', auth()->id())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('reference_number', 'like', '%' . $this->search . '%')
                      ->orWhere('vehicle_make', 'like', '%' . $this->search . '%')
                      ->orWhere('vehicle_model', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.customer.import-financing-requests', [
            'requests' => $requests,
        ]);
    }
}

