<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\VehicleStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CashOrderDetail extends Component
{
    public Order $order;
    public $showApproveModal = false;
    public $showRejectModal = false;
    public $showCompleteModal = false;
    public $rejectionReason = '';
    public $adminNotes = '';

    public function mount($id)
    {
        $this->order = Order::with(['vehicle.make', 'vehicle.model', 'vehicle.entity', 'user.customer', 'processedBy'])
            ->where('order_type', OrderType::CASH_PURCHASE->value)
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

    public function openCompleteModal()
    {
        $this->showCompleteModal = true;
    }

    public function closeCompleteModal()
    {
        $this->showCompleteModal = false;
    }

    public function approveOrder()
    {
        if (!$this->order->isPending()) {
            session()->flash('error', 'Only pending orders can be approved.');
            return;
        }

        DB::transaction(function () {
            // Update order status
            $this->order->update([
                'status' => OrderStatus::APPROVED->value,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
                'admin_notes' => $this->adminNotes,
            ]);

            // Update vehicle status to HOLD (reserved)
            $this->order->vehicle->update([
                'status' => VehicleStatus::HOLD,
            ]);
        });

        session()->flash('success', 'Order approved successfully! Vehicle has been reserved.');
        $this->closeApproveModal();
        $this->order->refresh();
    }

    public function rejectOrder()
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:10|max:500',
        ]);

        if (!$this->order->isPending() && !$this->order->isApproved()) {
            session()->flash('error', 'Only pending or approved orders can be rejected.');
            return;
        }

        DB::transaction(function () {
            $previousStatus = $this->order->vehicle->status;

            // Update order status
            $this->order->update([
                'status' => OrderStatus::REJECTED->value,
                'rejection_reason' => $this->rejectionReason,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
                'admin_notes' => $this->adminNotes,
            ]);

            // If vehicle was on hold, return it to APPROVED status
            if ($previousStatus === VehicleStatus::HOLD) {
                $this->order->vehicle->update([
                    'status' => VehicleStatus::APPROVED,
                ]);
            }
        });

        session()->flash('success', 'Order rejected. Vehicle status has been restored.');
        $this->closeRejectModal();
        $this->order->refresh();
    }

    public function completeOrder()
    {
        if (!$this->order->isApproved()) {
            session()->flash('error', 'Only approved orders can be completed.');
            return;
        }

        DB::transaction(function () {
            // Update order status
            $this->order->update([
                'status' => OrderStatus::COMPLETED->value,
                'completed_at' => now(),
                'admin_notes' => $this->adminNotes,
                'completion_data' => [
                    'completed_by' => Auth::user()->name,
                    'completed_at' => now()->toDateTimeString(),
                    'final_price' => $this->order->vehicle->price,
                ],
            ]);

            // Update vehicle status to SOLD
            $this->order->vehicle->update([
                'status' => VehicleStatus::SOLD,
                'sold_at' => now(),
            ]);
        });

        session()->flash('success', 'Order completed successfully! Vehicle marked as sold.');
        $this->closeCompleteModal();
        $this->order->refresh();
    }

    public function saveNotes()
    {
        $this->validate([
            'adminNotes' => 'nullable|string|max:1000',
        ]);

        $this->order->update([
            'admin_notes' => $this->adminNotes,
        ]);

        session()->flash('success', 'Admin notes updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.cash-order-detail');
    }
}

