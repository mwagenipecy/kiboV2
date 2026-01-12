<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FinancingOrderDetail extends Component
{
    public Order $order;
    public $showApproveModal = false;
    public $showRejectModal = false;
    public $rejectionReason = '';
    public $adminNotes = '';

    public function mount($id)
    {
        $this->order = Order::with(['vehicle.make', 'vehicle.model', 'vehicle.entity', 'user.customer', 'processedBy'])
            ->where('order_type', OrderType::FINANCING_APPLICATION->value)
            ->findOrFail($id);
        
        $this->adminNotes = $this->order->admin_notes ?? '';
    }

    public function openApproveModal()
    {
        $this->showApproveModal = true;
    }

    public function closeApproveModal()
    {
        $this->showApproveModal = false;
    }

    public function openRejectModal()
    {
        $this->showRejectModal = true;
        $this->rejectionReason = '';
    }

    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->rejectionReason = '';
    }

    public function approveOrder()
    {
        if (!$this->order->isPending()) {
            session()->flash('error', 'Only pending applications can be approved.');
            return;
        }

        DB::transaction(function () {
            // Get current order data
            $orderData = $this->order->order_data ?? [];
            
            // Update order_data to track dealer approval
            $orderData['dealer_approval'] = 'approved';
            $orderData['dealer_approved_at'] = now()->toDateTimeString();
            $orderData['dealer_approved_by'] = Auth::id();
            $orderData['dealer_approved_by_name'] = Auth::user()->name;
            
            // Update order status - approved by dealer, now pending lender approval
            $this->order->update([
                'status' => OrderStatus::APPROVED->value,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
                'admin_notes' => $this->adminNotes,
                'order_data' => $orderData,
            ]);
            
            // Put vehicle on HOLD when dealer approves
            $this->order->vehicle->update([
                'status' => 'hold',
            ]);
        });

        session()->flash('success', 'Financing application approved, vehicle put on hold, and forwarded to lender.');
        $this->closeApproveModal();
        $this->mount($this->order->id);
    }

    public function rejectOrder()
    {
        if (!$this->order->isPending()) {
            session()->flash('error', 'Only pending applications can be rejected.');
            return;
        }

        $this->validate([
            'rejectionReason' => 'required|string|min:10',
        ], [
            'rejectionReason.required' => 'Please provide a reason for rejection.',
            'rejectionReason.min' => 'Rejection reason must be at least 10 characters.',
        ]);

        DB::transaction(function () {
            // Get current order data
            $orderData = $this->order->order_data ?? [];
            
            // Update order_data to track dealer rejection
            $orderData['dealer_approval'] = 'rejected';
            $orderData['dealer_rejected_at'] = now()->toDateTimeString();
            $orderData['dealer_rejected_by'] = Auth::id();
            $orderData['dealer_rejected_by_name'] = Auth::user()->name;
            
            $this->order->update([
                'status' => OrderStatus::REJECTED->value,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
                'admin_notes' => $this->adminNotes,
                'rejection_reason' => $this->rejectionReason,
                'order_data' => $orderData,
            ]);
            
            // Vehicle status remains the same (no change needed)
        });

        session()->flash('success', 'Financing application rejected. Vehicle status unchanged.');
        $this->closeRejectModal();
        $this->mount($this->order->id);
    }

    public function completeOrder()
    {
        if (!$this->order->isApproved()) {
            session()->flash('error', 'Only approved applications can be completed.');
            return;
        }

        DB::transaction(function () {
            $this->order->update([
                'status' => OrderStatus::COMPLETED->value,
                'completed_at' => now(),
                'admin_notes' => $this->adminNotes,
            ]);

            // Mark vehicle as sold when financing is completed
            $this->order->vehicle->update([
                'status' => 'sold',
                'sold_at' => now(),
            ]);
        });

        session()->flash('success', 'Financing application completed successfully.');
        $this->mount($this->order->id);
    }

    public function saveNotes()
    {
        $this->order->update([
            'admin_notes' => $this->adminNotes,
        ]);

        session()->flash('success', 'Notes saved successfully.');
    }

    public function render()
    {
        return view('livewire.admin.financing-order-detail');
    }
}
