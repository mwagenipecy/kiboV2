<?php

namespace App\Livewire\Admin;

use App\Jobs\SendGarageOrderConfirmationEmail;
use App\Jobs\SendGarageOrderQuotationEmail;
use App\Models\GarageServiceOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class GarageOrderView extends Component
{
    public GarageServiceOrder $order;
    public $showQuoteModal = false;
    public $showApproveModal = false;
    public $quotedPrice = '';
    public $quotationNotes = '';

    public function mount(GarageServiceOrder $order): void
    {
        $user = Auth::user();
        $userRole = $user->role ?? null;

        $query = GarageServiceOrder::with(['agent', 'user', 'processedBy'])
            ->where('id', $order->id);

        if ($userRole !== 'admin') {
            $hasScope = false;

            $agent = \App\Models\Agent::where('user_id', $user->id)->first();
            if ($agent) {
                $query->where('agent_id', $agent->id);
                $hasScope = true;
            }

            if (!empty($user->entity_id)) {
                $query->whereHas('user', function ($q) use ($user) {
                    $q->where('entity_id', $user->entity_id);
                });
                $hasScope = true;
            }

            if (!$hasScope) {
                abort(403);
            }
        }

        $this->order = $query->firstOrFail();
        
        // Pre-fill quotation fields if already quoted
        if ($this->order->quoted_price) {
            $this->quotedPrice = $this->order->quoted_price;
            $this->quotationNotes = $this->order->quotation_notes ?? '';
        }
    }

    public function openQuoteModal()
    {
        $this->validateAccess();
        $this->showApproveModal = false; // Close approve modal first
        $this->quotedPrice = $this->order->quoted_price ?? '';
        $this->quotationNotes = $this->order->quotation_notes ?? '';
        $this->showQuoteModal = true;
    }
    
    public function handleSendQuotation()
    {
        $this->openQuoteModal();
    }

    public function closeQuoteModal()
    {
        $this->showQuoteModal = false;
        $this->quotedPrice = '';
        $this->quotationNotes = '';
    }

    public function submitQuote()
    {
        $this->validate([
            'quotedPrice' => 'required|numeric|min:0',
            'quotationNotes' => 'nullable|string|max:1000',
        ]);

        $this->validateAccess();

        $this->order->update([
            'quoted_price' => $this->quotedPrice,
            'quotation_notes' => $this->quotationNotes,
            'quoted_at' => now(),
            'status' => 'quoted',
        ]);

        // Refresh the order
        $this->order->refresh();

        // Dispatch email job
        SendGarageOrderQuotationEmail::dispatch($this->order);

        session()->flash('success', 'Quotation sent successfully! An email notification has been sent to the customer.');
        $this->closeQuoteModal();
    }

    public function confirmOrder()
    {
        $this->validateAccess();
        $this->showApproveModal = false; // Close approve modal first

        if ($this->order->status === 'pending') {
            $this->order->update([
                'status' => 'confirmed',
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);

            // Refresh the order
            $this->order->refresh();

            // Dispatch email job
            SendGarageOrderConfirmationEmail::dispatch($this->order);

            session()->flash('success', 'Order confirmed successfully! An email notification has been sent to the customer.');
        } else {
            session()->flash('error', 'Only pending orders can be confirmed.');
        }
    }
    
    public function handleConfirmOrder()
    {
        $this->confirmOrder();
    }

    public function openApproveModal()
    {
        if (!$this->canApprove()) {
            abort(403, 'You do not have permission to approve this order.');
        }
        $this->showApproveModal = true;
    }

    public function closeApproveModal()
    {
        $this->showApproveModal = false;
    }

    public function canApprove()
    {
        $user = Auth::user();
        $userRole = $user->role ?? null;

        // Admin can always approve
        if ($userRole === 'admin') {
            return true;
        }

        // Check if user has an Agent record
        $agent = \App\Models\Agent::where('user_id', $user->id)->first();
        
        // If user has an agent record, check if agent.id matches order.agent_id
        if ($agent) {
            if ($this->order->agent_id !== $agent->id) {
                return false;
            }
        }
        
        // Check entity_id matching: user's entity_id must match either:
        // 1. The order's agent_id (if user doesn't have an Agent record but entity_id matches agent_id)
        // 2. The order's customer's entity_id
        if (!empty($user->entity_id)) {
            $entityMatches = false;
            
            // Check if user's entity_id matches order's agent_id (for cases where user doesn't have Agent record)
            if ($this->order->agent_id == $user->entity_id) {
                $entityMatches = true;
            }
            
            // Check if user's entity_id matches order's customer's entity_id
            if ($this->order->user && $this->order->user->entity_id == $user->entity_id) {
                $entityMatches = true;
            }
            
            if (!$entityMatches) {
                return false;
            }
        } else {
            // If user has no entity_id, they must have an Agent record that matches
            if (!$agent || $this->order->agent_id !== $agent->id) {
                return false;
            }
        }

        return true;
    }

    private function validateAccess()
    {
        if (!$this->canApprove()) {
            abort(403, 'You do not have permission to perform this action.');
        }
    }

    public function render()
    {
        $canApprove = $this->canApprove();
        
        return view('livewire.admin.garage-order-view', [
            'canApprove' => $canApprove,
        ]);
    }
}


