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
    public $channelFilter = ''; // '', 'portal', 'whatsapp'
    public $activeTab = 'open'; // 'open', 'my_quotations', 'all'
    
    // Quote Modal
    public $showQuoteModal = false;
    public $selectedOrder = null;
    public $quotedPrice = '';
    public $currency = 'TZS';
    public $quotationNotes = '';
    public $estimatedDays = '';
    /** When admin submits a quote, they must select which agent the quote is from */
    public $selectedAgentId = null;
    
    // Success/Error
    public $showSuccessModal = false;
    public $showErrorModal = false;
    public $successMessage = '';
    public $errorMessage = '';

    protected $queryString = ['search', 'statusFilter', 'channelFilter', 'activeTab'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingChannelFilter()
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
        return Agent::where('id', $user->entity_id)->first();
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
        $this->selectedAgentId = null;
        // Default to first spare part agent for admin
        if (Auth::user()->role === 'admin') {
            $first = Agent::where('agent_type', 'spare_part')->where('status', 'active')->orderBy('name')->first();
            $this->selectedAgentId = $first?->id;
        }
        $this->showQuoteModal = true;
    }

    public function closeQuoteModal()
    {
        $this->showQuoteModal = false;
        $this->selectedOrder = null;
        $this->quotedPrice = '';
        $this->quotationNotes = '';
        $this->estimatedDays = '';
        $this->selectedAgentId = null;
    }

    public function submitQuotation()
    {
        $rules = [
            'quotedPrice' => 'required|numeric|min:0',
            'currency' => 'required|in:TZS,USD,EUR,KES',
            'quotationNotes' => 'nullable|string|max:2000',
            'estimatedDays' => 'nullable|integer|min:1|max:365',
        ];
        if (Auth::user()->role === 'admin') {
            $rules['selectedAgentId'] = 'required|exists:agents,id';
        }
        $this->validate($rules);

        $user = Auth::user();
        $agent = $this->getCurrentAgent();

        // Agent users: use their own agent profile. Admin: use selected agent.
        $agentId = null;
        if ($user->role === 'admin') {
            $agentId = $this->selectedAgentId ? (int) $this->selectedAgentId : null;
            if (!$agentId) {
                $this->errorMessage = 'Please select an agent to submit the quotation on behalf of.';
                $this->showErrorModal = true;
                return;
            }
        } elseif ($agent) {
            $agentId = $agent->id;
        } else {
            $this->errorMessage = 'You must be an agent to submit quotations.';
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

        // Update order status and quoted_at if it's the first quotation
        if ($this->selectedOrder->status === 'pending') {
            $this->selectedOrder->update([
                'status' => 'quoted',
                'quoted_at' => now(),
            ]);
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

        // Apply channel filter (portal / whatsapp)
        if ($this->channelFilter) {
            $openRequestsQuery->where('order_channel', $this->channelFilter);
            $allOrdersQuery->where('order_channel', $this->channelFilter);
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

        $sparePartAgents = $isAdmin ? Agent::where('agent_type', 'spare_part')->where('status', 'active')->orderBy('name')->get() : collect();

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
            'sparePartAgents' => $sparePartAgents,
        ]);
    }
}
