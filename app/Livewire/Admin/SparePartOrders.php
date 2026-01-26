<?php

namespace App\Livewire\Admin;

use App\Models\Agent;
use App\Models\SparePartOrder;
use App\Models\SparePartQuotation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class SparePartOrders extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $activeTab = 'open'; // 'open', 'my_quotations', 'all'
    
    // Quote Modal
    public $showQuoteModal = false;
    public $selectedOrder = null;
    public $quotedPrice = '';
    public $currency = 'TZS';
    public $quotationNotes = '';
    public $estimatedDays = '';
    
    // Success/Error
    public $showSuccessModal = false;
    public $showErrorModal = false;
    public $successMessage = '';
    public $errorMessage = '';

    protected $queryString = ['search', 'statusFilter', 'activeTab'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function getCurrentAgent()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return null; // Admin can see all
        }
        return Agent::where('user_id', $user->id)->first();
    }

    public function openQuoteModal($orderId)
    {
        $this->selectedOrder = SparePartOrder::with(['vehicleMake', 'vehicleModel', 'user'])->findOrFail($orderId);
        
        // Check if agent already quoted
        $agent = $this->getCurrentAgent();
        if ($agent) {
            $existingQuote = SparePartQuotation::where('spare_part_order_id', $orderId)
                ->where('agent_id', $agent->id)
                ->first();
            
            if ($existingQuote) {
                $this->errorMessage = 'You have already submitted a quotation for this request.';
                $this->showErrorModal = true;
                return;
            }
        }
        
        $this->quotedPrice = '';
        $this->currency = 'TZS';
        $this->quotationNotes = '';
        $this->estimatedDays = '';
        $this->showQuoteModal = true;
    }

    public function closeQuoteModal()
    {
        $this->showQuoteModal = false;
        $this->selectedOrder = null;
        $this->quotedPrice = '';
        $this->quotationNotes = '';
        $this->estimatedDays = '';
    }

    public function submitQuotation()
    {
        $this->validate([
            'quotedPrice' => 'required|numeric|min:0',
            'currency' => 'required|in:TZS,USD,EUR,KES',
            'quotationNotes' => 'nullable|string|max:2000',
            'estimatedDays' => 'nullable|integer|min:1|max:365',
        ]);

        $agent = $this->getCurrentAgent();
        
        if (!$agent && Auth::user()->role !== 'admin') {
            $this->errorMessage = 'You must be an agent to submit quotations.';
            $this->showErrorModal = true;
            return;
        }

        // For admin, we need to select an agent or create a system agent
        $agentId = $agent ? $agent->id : null;
        
        if (!$agentId) {
            $this->errorMessage = 'Agent profile not found. Please contact administrator.';
            $this->showErrorModal = true;
            return;
        }

        // Check if already quoted
        $existingQuote = SparePartQuotation::where('spare_part_order_id', $this->selectedOrder->id)
            ->where('agent_id', $agentId)
            ->first();

        if ($existingQuote) {
            $this->errorMessage = 'You have already submitted a quotation for this request.';
            $this->showErrorModal = true;
            return;
        }

        // Create quotation
        SparePartQuotation::create([
            'spare_part_order_id' => $this->selectedOrder->id,
            'agent_id' => $agentId,
            'quoted_price' => $this->quotedPrice,
            'currency' => $this->currency,
            'quotation_notes' => $this->quotationNotes,
            'estimated_days' => $this->estimatedDays ?: null,
            'status' => 'pending',
            'expires_at' => now()->addDays(7), // Quotations expire in 7 days
        ]);

        // Update order status if it's the first quotation
        if ($this->selectedOrder->status === 'pending') {
            $this->selectedOrder->update(['status' => 'quoted']);
        }

        $this->closeQuoteModal();
        $this->successMessage = 'Your quotation has been submitted successfully! The customer will be notified.';
        $this->showSuccessModal = true;
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->successMessage = '';
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
    }

    public function render()
    {
        $agent = $this->getCurrentAgent();
        $isAdmin = Auth::user()->role === 'admin';

        // Open requests - available for quotation (pending, no accepted quotation)
        $openRequestsQuery = SparePartOrder::with(['vehicleMake', 'vehicleModel', 'user', 'quotations'])
            ->where('status', 'pending')
            ->whereNull('accepted_quotation_id');
        
        // Exclude orders already quoted by this agent
        if ($agent) {
            $quotedOrderIds = SparePartQuotation::where('agent_id', $agent->id)->pluck('spare_part_order_id');
            $openRequestsQuery->whereNotIn('id', $quotedOrderIds);
        }

        // My quotations - quotations submitted by this agent
        $myQuotationsQuery = SparePartQuotation::with(['order.vehicleMake', 'order.vehicleModel', 'order.user']);
        if ($agent) {
            $myQuotationsQuery->where('agent_id', $agent->id);
        }

        // All orders query
        $allOrdersQuery = SparePartOrder::with(['vehicleMake', 'vehicleModel', 'user', 'quotations', 'acceptedQuotation.agent']);
        
        if (!$isAdmin && $agent) {
            // For agents, show only orders they have quoted on
            $quotedOrderIds = SparePartQuotation::where('agent_id', $agent->id)->pluck('spare_part_order_id');
            $allOrdersQuery->whereIn('id', $quotedOrderIds);
        }

        // Apply search filter
        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            
            $openRequestsQuery->where(function ($q) use ($searchTerm) {
                $q->where('order_number', 'like', $searchTerm)
                    ->orWhere('customer_name', 'like', $searchTerm)
                    ->orWhere('part_name', 'like', $searchTerm);
            });

            $allOrdersQuery->where(function ($q) use ($searchTerm) {
                $q->where('order_number', 'like', $searchTerm)
                    ->orWhere('customer_name', 'like', $searchTerm)
                    ->orWhere('part_name', 'like', $searchTerm);
            });
        }

        // Apply status filter for all orders
        if ($this->statusFilter) {
            $allOrdersQuery->where('status', $this->statusFilter);
        }

        // Get counts
        $openRequestsCount = (clone $openRequestsQuery)->count();
        $myQuotationsCount = (clone $myQuotationsQuery)->count();
        $allOrdersCount = (clone $allOrdersQuery)->count();

        // Paginate based on active tab
        $openRequests = $this->activeTab === 'open' 
            ? $openRequestsQuery->orderBy('created_at', 'desc')->paginate(15)
            : collect();

        $myQuotations = $this->activeTab === 'my_quotations'
            ? $myQuotationsQuery->orderBy('created_at', 'desc')->paginate(15)
            : collect();

        $allOrders = $this->activeTab === 'all'
            ? $allOrdersQuery->orderBy('created_at', 'desc')->paginate(15)
            : collect();

        $statuses = [
            'pending' => 'Pending',
            'quoted' => 'Quoted',
            'accepted' => 'Accepted',
            'awaiting_payment' => 'Awaiting Payment',
            'payment_submitted' => 'Payment Submitted',
            'payment_verified' => 'Payment Verified',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'completed' => 'Completed',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
        ];

        return view('livewire.admin.spare-part-orders', [
            'openRequests' => $openRequests,
            'myQuotations' => $myQuotations,
            'allOrders' => $allOrders,
            'openRequestsCount' => $openRequestsCount,
            'myQuotationsCount' => $myQuotationsCount,
            'allOrdersCount' => $allOrdersCount,
            'statuses' => $statuses,
            'isAdmin' => $isAdmin,
            'currentAgent' => $agent,
        ]);
    }
}
