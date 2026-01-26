<?php

namespace App\Livewire\Admin;

use App\Models\GarageServiceOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]

class GarageOrders extends Component
{
    use WithPagination;

    public $filter = 'all';
    public $search = '';
    public $selectedOrder = null;
    public $showDetailModal = false;
    public $showQuoteModal = false;
    public $quotedPrice = '';
    public $quotationNotes = '';
    public $rejectionReason = '';

    protected $queryString = ['filter', 'search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function viewOrder($orderId)
    {
        $user = Auth::user();
        $userRole = $user->role ?? null;
        
        $query = GarageServiceOrder::with(['agent', 'user', 'processedBy'])->where('id', $orderId);
        
        // For non-admin users, ensure they can only view their garage's orders
        if ($userRole !== 'admin') {
            $agent = \App\Models\Agent::where('user_id', $user->id)->first();
            if ($agent) {
                $query->where('agent_id', $agent->id);
            } else {
                abort(403, 'You do not have permission to view this order.');
            }
        }
        
        $this->selectedOrder = $query->firstOrFail();
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedOrder = null;
        $this->quotedPrice = '';
        $this->quotationNotes = '';
        $this->rejectionReason = '';
    }

    public function openQuoteModal($orderId)
    {
        $user = Auth::user();
        $userRole = $user->role ?? null;
        
        $query = GarageServiceOrder::where('id', $orderId);
        
        // For non-admin users, ensure they can only quote their garage's orders
        if ($userRole !== 'admin') {
            $agent = \App\Models\Agent::where('user_id', $user->id)->first();
            if ($agent) {
                $query->where('agent_id', $agent->id);
            } else {
                abort(403, 'You do not have permission to quote this order.');
            }
        }
        
        $this->selectedOrder = $query->firstOrFail();
        $this->quotedPrice = $this->selectedOrder->quoted_price ?? '';
        $this->quotationNotes = $this->selectedOrder->quotation_notes ?? '';
        $this->showQuoteModal = true;
    }

    public function closeQuoteModal()
    {
        $this->showQuoteModal = false;
        $this->quotedPrice = '';
        $this->quotationNotes = '';
    }

    public function confirmOrder($orderId)
    {
        $user = Auth::user();
        $userRole = $user->role ?? null;
        
        $query = GarageServiceOrder::where('id', $orderId);
        
        // For non-admin users, ensure they can only confirm their garage's orders
        if ($userRole !== 'admin') {
            $agent = \App\Models\Agent::where('user_id', $user->id)->first();
            if ($agent) {
                $query->where('agent_id', $agent->id);
            } else {
                abort(403, 'You do not have permission to confirm this order.');
            }
        }
        
        $order = $query->firstOrFail();
        $order->update([
            'status' => 'confirmed',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        session()->flash('success', 'Order confirmed successfully!');
        $this->closeDetailModal();
    }

    public function rejectOrder($orderId)
    {
        $this->validate([
            'rejectionReason' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $userRole = $user->role ?? null;
        
        $query = GarageServiceOrder::where('id', $orderId);
        
        // For non-admin users, ensure they can only reject their garage's orders
        if ($userRole !== 'admin') {
            $agent = \App\Models\Agent::where('user_id', $user->id)->first();
            if ($agent) {
                $query->where('agent_id', $agent->id);
            } else {
                abort(403, 'You do not have permission to reject this order.');
            }
        }

        $order = $query->firstOrFail();
        $order->update([
            'status' => 'rejected',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
            'rejection_reason' => $this->rejectionReason,
        ]);

        session()->flash('success', 'Order rejected successfully!');
        $this->closeDetailModal();
        $this->rejectionReason = '';
    }

    public function submitQuote()
    {
        $this->validate([
            'quotedPrice' => 'required|numeric|min:0',
            'quotationNotes' => 'nullable|string|max:1000',
        ]);

        // Ensure the order belongs to the user's garage if not admin
        if (!$this->selectedOrder) {
            session()->flash('error', 'Order not found.');
            return;
        }

        $user = Auth::user();
        $userRole = $user->role ?? null;
        
        if ($userRole !== 'admin') {
            $agent = \App\Models\Agent::where('user_id', $user->id)->first();
            if (!$agent || $this->selectedOrder->agent_id !== $agent->id) {
                abort(403, 'You do not have permission to quote this order.');
            }
        }

        $this->selectedOrder->update([
            'quoted_price' => $this->quotedPrice,
            'quotation_notes' => $this->quotationNotes,
            'quoted_at' => now(),
            'status' => 'quoted',
        ]);

        session()->flash('success', 'Quotation sent successfully!');
        $this->closeQuoteModal();
    }

    public function render()
    {
        $user = Auth::user();
        $userRole = $user->role ?? null;

        $query = GarageServiceOrder::with(['agent', 'user'])
            ->when($userRole !== 'admin', function ($q) use ($user) {
                // For non-admin users (agents), show only orders for their garage
                $agent = \App\Models\Agent::where('user_id', $user->id)->first();
                if ($agent) {
                    $q->where('agent_id', $agent->id);
                } else {
                    // If no agent found, show no orders
                    $q->whereRaw('1 = 0');
                }
            })
            ->latest();

        // Apply filters
        if ($this->filter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($this->filter === 'confirmed') {
            $query->where('status', 'confirmed');
        } elseif ($this->filter === 'rejected') {
            $query->where('status', 'rejected');
        } elseif ($this->filter === 'quoted') {
            $query->whereNotNull('quoted_price');
        } elseif ($this->filter === 'in_progress') {
            $query->where('status', 'in_progress');
        } elseif ($this->filter === 'completed') {
            $query->where('status', 'completed');
        }

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $this->search . '%')
                  ->orWhere('service_type', 'like', '%' . $this->search . '%')
                  ->orWhereHas('agent', function ($q) {
                      $q->where('company_name', 'like', '%' . $this->search . '%')
                        ->orWhere('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $orders = $query->paginate(15);

        // Get counts for filters (filtered by agent for non-admin)
        $countsQuery = GarageServiceOrder::query()
            ->when($userRole !== 'admin', function ($q) use ($user) {
                $agent = \App\Models\Agent::where('user_id', $user->id)->first();
                if ($agent) {
                    $q->where('agent_id', $agent->id);
                } else {
                    $q->whereRaw('1 = 0');
                }
            });

        $counts = [
            'all' => (clone $countsQuery)->count(),
            'pending' => (clone $countsQuery)->where('status', 'pending')->count(),
            'confirmed' => (clone $countsQuery)->where('status', 'confirmed')->count(),
            'rejected' => (clone $countsQuery)->where('status', 'rejected')->count(),
            'quoted' => (clone $countsQuery)->whereNotNull('quoted_price')->count(),
            'in_progress' => (clone $countsQuery)->where('status', 'in_progress')->count(),
            'completed' => (clone $countsQuery)->where('status', 'completed')->count(),
        ];

        return view('livewire.admin.garage-orders', [
            'orders' => $orders,
            'counts' => $counts,
        ]);
    }
}
