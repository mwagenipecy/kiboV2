<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use App\Models\VehicleLease;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class LeasingOrders extends Component
{
    use WithPagination;

    public $filter = 'all';
    public $search = '';

    protected $queryString = ['filter', 'search'];

    public function mount($filter = 'all')
    {
        $this->filter = $filter ?? 'all';
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
        
        $query = Order::with(['user.customer'])
            ->where('order_type', OrderType::LEASING_APPLICATION->value)
            // Filter by lease's entity_id if user is not admin
            ->when($userRole !== 'admin', function ($q) use ($user) {
                if ($user->entity_id) {
                    // Get lease IDs that belong to user's entity
                    $leaseIds = VehicleLease::where('entity_id', $user->entity_id)->pluck('id')->toArray();
                    if (empty($leaseIds)) {
                        // If no leases found, show no orders
                        $q->whereRaw('1 = 0');
                    } else {
                        // Filter orders where lease_id in order_data matches user's entity leases
                        $q->where(function ($orderQuery) use ($leaseIds) {
                            foreach ($leaseIds as $leaseId) {
                                $orderQuery->orWhere('order_data->lease_id', $leaseId);
                            }
                        });
                    }
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
            $query->where('status', OrderStatus::APPROVED->value)
                  ->where('order_data->approval_status', 'approved')
                  ->where(function($q) {
                      $q->where('order_data->lease_started', false)
                        ->orWhereNull('order_data->lease_started');
                  });
        } elseif ($this->filter === 'active') {
            $query->where('status', OrderStatus::APPROVED->value)
                  ->where('order_data->lease_started', true)
                  ->where(function($q) {
                      $q->where('order_data->lease_terminated', false)
                        ->orWhereNull('order_data->lease_terminated');
                  });
        } elseif ($this->filter === 'completed') {
            $query->where(function ($q) {
                $q->where('status', OrderStatus::COMPLETED->value)
                  ->orWhere('order_data->lease_terminated', true);
            });
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
                  ->orWhere('order_data->full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('order_data->vehicle_title', 'like', '%' . $this->search . '%')
                  ->orWhere('order_data->vehicle_make', 'like', '%' . $this->search . '%')
                  ->orWhere('order_data->vehicle_model', 'like', '%' . $this->search . '%');
            });
        }

        $orders = $query->paginate(15);

        // Get counts for filters (filtered by entity for non-admin)
        $countsQuery = Order::where('order_type', OrderType::LEASING_APPLICATION->value)
            ->when($userRole !== 'admin', function ($q) use ($user) {
                if ($user->entity_id) {
                    $leaseIds = VehicleLease::where('entity_id', $user->entity_id)->pluck('id')->toArray();
                    if (empty($leaseIds)) {
                        $q->whereRaw('1 = 0');
                    } else {
                        $q->where(function ($orderQuery) use ($leaseIds) {
                            foreach ($leaseIds as $leaseId) {
                                $orderQuery->orWhere('order_data->lease_id', $leaseId);
                            }
                        });
                    }
                } else {
                    $q->whereRaw('1 = 0');
                }
            });
        
        $counts = [
            'all' => (clone $countsQuery)->count(),
            'pending' => (clone $countsQuery)->where('status', OrderStatus::PENDING->value)->count(),
            'approved' => (clone $countsQuery)->where('status', OrderStatus::APPROVED->value)
                ->where('order_data->approval_status', 'approved')
                ->where(function($q) {
                    $q->where('order_data->lease_started', false)
                      ->orWhereNull('order_data->lease_started');
                })
                ->count(),
            'active' => (clone $countsQuery)->where('status', OrderStatus::APPROVED->value)
                ->where('order_data->lease_started', true)
                ->where(function($q) {
                    $q->where('order_data->lease_terminated', false)
                      ->orWhereNull('order_data->lease_terminated');
                })
                ->count(),
            'completed' => (clone $countsQuery)->where(function ($q) {
                $q->where('status', OrderStatus::COMPLETED->value)
                  ->orWhere('order_data->lease_terminated', true);
            })->count(),
            'rejected' => (clone $countsQuery)->where('status', OrderStatus::REJECTED->value)->count(),
        ];

        return view('livewire.admin.leasing-orders', [
            'orders' => $orders,
            'counts' => $counts,
        ]);
    }
}

