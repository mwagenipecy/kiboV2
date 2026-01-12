<?php

namespace App\Livewire\Lender;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FinancingApplicationDetail extends Component
{
    public Order $order;
    public $showApproveModal = false;
    public $showRejectModal = false;
    public $rejectionReason = '';
    public $lenderNotes = '';
    public $lenderApprovalStatus = null;

    public function mount($id)
    {
        $user = Auth::user();
        
        $this->order = Order::with(['vehicle.make', 'vehicle.model', 'vehicle.entity', 'user.customer'])
            ->where('order_type', OrderType::FINANCING_APPLICATION->value)
            ->where('status', OrderStatus::APPROVED->value) // Dealer approved
            ->whereJsonContains('order_data->lender_entity_id', $user->entity_id)
            ->findOrFail($id);
        
        $this->lenderNotes = $this->order->order_data['lender_notes'] ?? '';
        $this->lenderApprovalStatus = $this->order->order_data['lender_approval'] ?? 'pending';
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

    public function approveApplication()
    {
        if ($this->lenderApprovalStatus !== 'pending') {
            session()->flash('error', 'This application has already been processed.');
            return;
        }

        DB::transaction(function () {
            // Update order with lender approval
            $orderData = $this->order->order_data;
            $orderData['lender_approval'] = 'approved';
            $orderData['lender_approved_at'] = now()->toDateTimeString();
            $orderData['lender_approved_by'] = Auth::id();
            $orderData['lender_approved_by_name'] = Auth::user()->name;
            $orderData['lender_notes'] = $this->lenderNotes;

            $this->order->update([
                'order_data' => $orderData,
                'status' => OrderStatus::COMPLETED->value, // Move to completed when lender approves
                'completed_at' => now(),
            ]);

            // Mark vehicle as sold when lender approves financing
            $this->order->vehicle->update([
                'status' => 'sold',
                'sold_at' => now(),
            ]);
        });

        session()->flash('success', 'Financing application approved! Vehicle marked as sold.');
        $this->closeApproveModal();
        $this->mount($this->order->id);
    }

    public function rejectApplication()
    {
        if ($this->lenderApprovalStatus !== 'pending') {
            session()->flash('error', 'This application has already been processed.');
            return;
        }

        $this->validate([
            'rejectionReason' => 'required|string|min:10',
        ], [
            'rejectionReason.required' => 'Please provide a reason for rejection.',
            'rejectionReason.min' => 'Rejection reason must be at least 10 characters.',
        ]);

        DB::transaction(function () {
            // Update order with lender rejection
            $orderData = $this->order->order_data;
            $orderData['lender_approval'] = 'rejected';
            $orderData['lender_rejected_at'] = now()->toDateTimeString();
            $orderData['lender_rejected_by'] = Auth::id();
            $orderData['lender_rejected_by_name'] = Auth::user()->name;
            $orderData['lender_rejection_reason'] = $this->rejectionReason;
            $orderData['lender_notes'] = $this->lenderNotes;

            $this->order->update([
                'order_data' => $orderData,
                'status' => OrderStatus::REJECTED->value,
            ]);

            // Return vehicle to approved status (remove hold)
            $this->order->vehicle->update([
                'status' => 'approved',
            ]);
        });

        session()->flash('success', 'Financing application rejected. Vehicle returned to approved status.');
        $this->closeRejectModal();
        $this->mount($this->order->id);
    }

    public function saveNotes()
    {
        $orderData = $this->order->order_data;
        $orderData['lender_notes'] = $this->lenderNotes;
        
        $this->order->update([
            'order_data' => $orderData,
        ]);

        session()->flash('success', 'Notes saved successfully.');
    }

    public function render()
    {
        return view('livewire.lender.financing-application-detail');
    }
}
