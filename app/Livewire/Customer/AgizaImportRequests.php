<?php

namespace App\Livewire\Customer;

use App\Models\AgizaImportRequest;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.customer', ['vehicleType' => 'agiza-import'])]
class AgizaImportRequests extends Component
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
        $requests = AgizaImportRequest::where('user_id', auth()->id())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('request_number', 'like', '%' . $this->search . '%')
                      ->orWhere('vehicle_make', 'like', '%' . $this->search . '%')
                      ->orWhere('vehicle_model', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        $notifications = \App\Models\AgizaImportNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.customer.agiza-import-requests', [
            'requests' => $requests,
            'notifications' => $notifications,
        ]);
    }
}
