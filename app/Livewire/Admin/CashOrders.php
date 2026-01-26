<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\VehicleStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CashOrders extends Component
{
    use WithPagination;

    public $filter = 'all';
    public $search = '';

    protected $queryString = ['filter', 'search'];

    public function mount($filter = 'all')
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
        $userRole = $user->role ?? null;
        
        $query = Order::with(['vehicle.make', 'vehicle.model', 'user'])
            ->where('order_type', OrderType::CASH_PURCHASE->value)
            // Filter by vehicle's entity_id if user is not admin
            ->when($userRole !== 'admin', function ($q) use ($user) {
                if ($user->entity_id) {
                    // Show only orders where vehicle's entity_id matches user's entity_id
                    $q->whereHas('vehicle', function ($vehicleQuery) use ($user) {
                        $vehicleQuery->where('entity_id', $user->entity_id);
                    });
                } else {
                    // If no entity_id, show no orders (impossible condition)
                    $q->whereRaw('1 = 0');
                }
            })
            ->latest();

        // Apply filters
        if ($this->filter === 'pending') {
            $query->where('status', OrderStatus::PENDING->value);
        } elseif ($this->filter === 'approved') {
            $query->where('status', OrderStatus::APPROVED->value);
        } elseif ($this->filter === 'completed') {
            $query->where('status', OrderStatus::COMPLETED->value);
        } elseif ($this->filter === 'rejected') {
            $query->where('status', OrderStatus::REJECTED->value);
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

        // Get counts for filters (filtered by entity for non-admin)
        $countsQuery = Order::where('order_type', OrderType::CASH_PURCHASE->value)
            ->when($userRole !== 'admin', function ($q) use ($user) {
                if ($user->entity_id) {
                    $q->whereHas('vehicle', function ($vehicleQuery) use ($user) {
                        $vehicleQuery->where('entity_id', $user->entity_id);
                    });
                } else {
                    $q->whereRaw('1 = 0');
                }
            });
        
        $counts = [
            'all' => (clone $countsQuery)->count(),
            'pending' => (clone $countsQuery)->where('status', OrderStatus::PENDING->value)->count(),
            'approved' => (clone $countsQuery)->where('status', OrderStatus::APPROVED->value)->count(),
            'completed' => (clone $countsQuery)->where('status', OrderStatus::COMPLETED->value)->count(),
            'rejected' => (clone $countsQuery)->where('status', OrderStatus::REJECTED->value)->count(),
        ];

        return view('livewire.admin.cash-orders', [
            'orders' => $orders,
            'counts' => $counts,
        ]);
    }
}

