<?php

namespace App\Livewire\Lender;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class FinancingApplications extends Component
{
    use WithPagination;

    public $filter = 'pending';
    public $search = '';

    protected $queryString = ['filter', 'search'];

    public function mount($filter = 'pending')
    {
        $this->filter = $filter;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        $lenderEntityId = $user->entity_id;

        $query = Order::with(['vehicle.make', 'vehicle.model', 'user'])
            ->where('order_type', OrderType::FINANCING_APPLICATION->value)
            ->where('status', OrderStatus::APPROVED->value) // Only show dealer-approved applications
            ->whereJsonContains('order_data->lender_entity_id', $lenderEntityId)
            ->latest();

        // Filter by lender approval status
        if ($this->filter === 'pending') {
            $query->where(function ($q) {
                $q->whereJsonContains('order_data->lender_approval', 'pending')
                  ->orWhereNull('order_data->lender_approval');
            });
        } elseif ($this->filter === 'approved') {
            $query->whereJsonContains('order_data->lender_approval', 'approved');
        } elseif ($this->filter === 'rejected') {
            $query->whereJsonContains('order_data->lender_approval', 'rejected');
        }

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('vehicle', function ($q) {
                      $q->whereHas('make', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      })->orWhereHas('model', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
                  });
            });
        }

        $orders = $query->paginate(15);

        // Get counts for filters
        $baseQuery = Order::where('order_type', OrderType::FINANCING_APPLICATION->value)
            ->where('status', OrderStatus::APPROVED->value)
            ->whereJsonContains('order_data->lender_entity_id', $lenderEntityId);

        $counts = [
            'all' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where(function ($q) {
                $q->whereJsonContains('order_data->lender_approval', 'pending')
                  ->orWhereNull('order_data->lender_approval');
            })->count(),
            'approved' => (clone $baseQuery)->whereJsonContains('order_data->lender_approval', 'approved')->count(),
            'rejected' => (clone $baseQuery)->whereJsonContains('order_data->lender_approval', 'rejected')->count(),
        ];

        return view('livewire.lender.financing-applications', [
            'orders' => $orders,
            'counts' => $counts,
        ]);
    }
}
