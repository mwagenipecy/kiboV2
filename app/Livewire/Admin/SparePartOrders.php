<?php

namespace App\Livewire\Admin;

use App\Models\SparePartOrder;
use Livewire\Component;
use Livewire\WithPagination;

class SparePartOrders extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $selectedOrder = null;
    public $showDetailModal = false;
    public $showQuoteModal = false;
    public $quotedPrice = '';
    public $adminNotes = '';
    public $assignedTo = '';

    protected $queryString = ['search', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function viewOrder($orderId)
    {
        $this->selectedOrder = SparePartOrder::with(['vehicleMake', 'vehicleModel', 'user', 'assignedTo'])->findOrFail($orderId);
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedOrder = null;
    }

    public function openQuoteModal($orderId)
    {
        $this->selectedOrder = SparePartOrder::findOrFail($orderId);
        $this->quotedPrice = $this->selectedOrder->quoted_price ?? '';
        $this->adminNotes = $this->selectedOrder->admin_notes ?? '';
        $this->assignedTo = $this->selectedOrder->assigned_to ?? '';
        $this->showQuoteModal = true;
    }

    public function closeQuoteModal()
    {
        $this->showQuoteModal = false;
        $this->selectedOrder = null;
        $this->quotedPrice = '';
        $this->adminNotes = '';
        $this->assignedTo = '';
    }

    public function updateStatus($orderId, $status)
    {
        $order = SparePartOrder::findOrFail($orderId);
        $order->update(['status' => $status]);
        
        session()->flash('success', 'Order status updated successfully!');
        $this->closeDetailModal();
    }

    public function submitQuote()
    {
        $this->validate([
            'quotedPrice' => 'nullable|numeric|min:0',
            'adminNotes' => 'nullable|string',
            'assignedTo' => 'nullable|exists:users,id',
        ]);

        $this->selectedOrder->update([
            'quoted_price' => $this->quotedPrice ?: null,
            'admin_notes' => $this->adminNotes,
            'assigned_to' => $this->assignedTo ?: null,
            'status' => $this->quotedPrice ? 'quoted' : 'processing',
        ]);

        session()->flash('success', 'Quote updated successfully!');
        $this->closeQuoteModal();
    }

    public function render()
    {
        $orders = SparePartOrder::with(['vehicleMake', 'vehicleModel', 'user'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('order_number', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_email', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_phone', 'like', '%' . $this->search . '%')
                        ->orWhere('part_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $statuses = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'quoted' => 'Quoted',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];

        $users = \App\Models\User::where('role', 'admin')->orWhere('role', 'staff')->get();

        return view('livewire.admin.spare-part-orders', [
            'orders' => $orders,
            'statuses' => $statuses,
            'users' => $users,
        ]);
    }
}

