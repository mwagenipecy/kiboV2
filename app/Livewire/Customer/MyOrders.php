<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.customer')]
class MyOrders extends Component
{
    use WithPagination;

    public $filterStatus = 'all';
    public $filterType = 'all';
    
    // Modal states
    public $showViewModal = false;
    public $showPaymentModal = false;
    public $selectedOrder = null;

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function viewOrder($orderId)
    {
        $this->selectedOrder = Order::with(['vehicle.make', 'vehicle.model', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);
        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->selectedOrder = null;
    }

    public function payOrder($orderId)
    {
        $this->selectedOrder = Order::with(['vehicle.make', 'vehicle.model'])
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->selectedOrder = null;
    }

    public function processPayment($paymentMethod)
    {
        if (!$this->selectedOrder) {
            return;
        }

        // Mark order as paid
        $this->selectedOrder->markAsPaid($paymentMethod, 'PAY-' . strtoupper(uniqid()));

        session()->flash('success', 'Payment successful! Your order has been confirmed.');
        
        $this->closePaymentModal();
        $this->dispatch('orderPaid');
    }

    public function render()
    {
        $query = Order::with(['vehicle.make', 'vehicle.model'])
            ->where('user_id', Auth::id())
            ->latest();

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterType !== 'all') {
            $query->where('order_type', $this->filterType);
        }

        $orders = $query->paginate(10);

        // Get counts for filters
        $statusCounts = [
            'all' => Order::where('user_id', Auth::id())->count(),
            'pending' => Order::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'processing' => Order::where('user_id', Auth::id())->where('status', 'processing')->count(),
            'approved' => Order::where('user_id', Auth::id())->where('status', 'approved')->count(),
            'completed' => Order::where('user_id', Auth::id())->where('status', 'completed')->count(),
        ];

        return view('livewire.customer.my-orders', [
            'orders' => $orders,
            'statusCounts' => $statusCounts,
        ]);
    }
}

