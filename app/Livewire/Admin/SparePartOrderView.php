<?php

namespace App\Livewire\Admin;

use App\Jobs\SendSparePartQuotationEmail;
use App\Jobs\SendSparePartPaymentConfirmationEmail;
use App\Jobs\SendSparePartShippedEmail;
use App\Models\Agent;
use App\Models\SparePartOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class SparePartOrderView extends Component
{
    use WithFileUploads;

    public SparePartOrder $order;
    public $canManage = false;
    
    // Quote Modal
    public $showQuoteModal = false;
    public $quotedPrice = '';
    public $quotationNotes = '';
    public $currency = 'TZS';
    
    // Delivery Modal
    public $showDeliveryModal = false;
    public $estimatedDeliveryDate = '';
    public $deliveryNotes = '';
    public $paymentMethod = '';
    public $paymentAccountDetails = [];
    
    // Payment Account Details
    public $bankName = '';
    public $accountName = '';
    public $accountNumber = '';
    public $mobileProvider = '';
    public $mobileNumber = '';
    
    // Payment Verification Modal
    public $showPaymentVerifyModal = false;
    public $paymentVerificationNotes = '';
    
    // Shipping Modal
    public $showShippingModal = false;
    public $trackingNumber = '';
    public $shippingNotes = '';
    
    // Success/Error Modals
    public $showSuccessModal = false;
    public $showErrorModal = false;
    public $successMessage = '';
    public $errorMessage = '';

    public function mount(SparePartOrder $order)
    {
        $this->order = $order->load(['user', 'vehicleMake', 'vehicleModel', 'agent', 'assignedTo']);
        
        $user = Auth::user();
        
        // Check if user can manage this order
        if ($user->role === 'admin') {
            $this->canManage = true;
        } elseif ($user->role === 'agent') {
            $agent = Agent::where('user_id', $user->id)->first();
            if ($agent && ($this->order->agent_id === $agent->id || !$this->order->agent_id)) {
                $this->canManage = true;
            }
        } elseif ($user->entity_id && $this->order->agent_id) {
            $orderAgent = Agent::find($this->order->agent_id);
            if ($orderAgent && $orderAgent->entity_id === $user->entity_id) {
                $this->canManage = true;
            }
        }

        // Pre-fill quote form if already quoted
        if ($this->order->quoted_price) {
            $this->quotedPrice = $this->order->quoted_price;
            $this->quotationNotes = $this->order->quotation_notes ?? '';
            $this->currency = $this->order->currency ?? 'TZS';
        }

        // Pre-fill delivery form if already set
        if ($this->order->estimated_delivery_date) {
            $this->estimatedDeliveryDate = $this->order->estimated_delivery_date->format('Y-m-d');
            $this->deliveryNotes = $this->order->delivery_notes ?? '';
            $this->paymentMethod = $this->order->payment_method ?? '';
            
            if ($this->order->payment_account_details) {
                $details = $this->order->payment_account_details;
                $this->bankName = $details['bank_name'] ?? '';
                $this->accountName = $details['account_name'] ?? '';
                $this->accountNumber = $details['account_number'] ?? '';
                $this->mobileProvider = $details['mobile_provider'] ?? '';
                $this->mobileNumber = $details['mobile_number'] ?? '';
            }
        }
    }

    // Quote Methods
    public function openQuoteModal()
    {
        $this->showQuoteModal = true;
    }

    public function closeQuoteModal()
    {
        $this->showQuoteModal = false;
    }

    public function submitQuote()
    {
        $this->validate([
            'quotedPrice' => 'required|numeric|min:0',
            'quotationNotes' => 'nullable|string|max:2000',
            'currency' => 'required|in:TZS,USD,EUR,KES',
        ]);

        if (!$this->canManage) {
            $this->errorMessage = 'You do not have permission to quote this order.';
            $this->showErrorModal = true;
            return;
        }

        // Assign agent if not already assigned
        $agentId = $this->order->agent_id;
        if (!$agentId && Auth::user()->role === 'agent') {
            $agent = Agent::where('user_id', Auth::id())->first();
            $agentId = $agent?->id;
        }

        $this->order->update([
            'quoted_price' => $this->quotedPrice,
            'quotation_notes' => $this->quotationNotes,
            'currency' => $this->currency,
            'quoted_at' => now(),
            'status' => 'quoted',
            'agent_id' => $agentId,
        ]);

        // Send email notification
        SendSparePartQuotationEmail::dispatch($this->order->fresh());

        $this->closeQuoteModal();
        $this->successMessage = 'Quotation has been sent to the customer successfully!';
        $this->showSuccessModal = true;
        $this->order->refresh();
    }

    // Delivery Methods
    public function openDeliveryModal()
    {
        $this->showDeliveryModal = true;
    }

    public function closeDeliveryModal()
    {
        $this->showDeliveryModal = false;
    }

    public function submitDeliveryInfo()
    {
        $this->validate([
            'estimatedDeliveryDate' => 'required|date|after:today',
            'deliveryNotes' => 'nullable|string|max:2000',
            'paymentMethod' => 'required|in:online,offline,bank_transfer,mobile_money',
        ]);

        if (!$this->canManage) {
            $this->errorMessage = 'You do not have permission to update delivery info.';
            $this->showErrorModal = true;
            return;
        }

        $paymentDetails = [];
        if (in_array($this->paymentMethod, ['bank_transfer', 'offline'])) {
            $this->validate([
                'bankName' => 'required|string',
                'accountName' => 'required|string',
                'accountNumber' => 'required|string',
            ]);
            $paymentDetails = [
                'bank_name' => $this->bankName,
                'account_name' => $this->accountName,
                'account_number' => $this->accountNumber,
            ];
        } elseif ($this->paymentMethod === 'mobile_money') {
            $this->validate([
                'mobileProvider' => 'required|string',
                'mobileNumber' => 'required|string',
                'accountName' => 'required|string',
            ]);
            $paymentDetails = [
                'mobile_provider' => $this->mobileProvider,
                'mobile_number' => $this->mobileNumber,
                'account_name' => $this->accountName,
            ];
        }

        $this->order->update([
            'estimated_delivery_date' => $this->estimatedDeliveryDate,
            'delivery_notes' => $this->deliveryNotes,
            'payment_method' => $this->paymentMethod,
            'payment_account_details' => $paymentDetails,
            'delivery_confirmed_at' => now(),
            'status' => 'awaiting_payment',
        ]);

        $this->closeDeliveryModal();
        $this->successMessage = 'Delivery information and payment details have been sent to the customer!';
        $this->showSuccessModal = true;
        $this->order->refresh();
    }

    // Payment Verification Methods
    public function openPaymentVerifyModal()
    {
        $this->showPaymentVerifyModal = true;
    }

    public function closePaymentVerifyModal()
    {
        $this->showPaymentVerifyModal = false;
    }

    public function verifyPayment()
    {
        if (!$this->canManage) {
            $this->errorMessage = 'You do not have permission to verify payment.';
            $this->showErrorModal = true;
            return;
        }

        $this->order->update([
            'payment_verified' => true,
            'payment_verified_at' => now(),
            'status' => 'payment_verified',
        ]);

        // Send confirmation email
        SendSparePartPaymentConfirmationEmail::dispatch($this->order->fresh());

        $this->closePaymentVerifyModal();
        $this->successMessage = 'Payment has been verified! The order is now being prepared.';
        $this->showSuccessModal = true;
        $this->order->refresh();
    }

    public function rejectPayment()
    {
        if (!$this->canManage) {
            $this->errorMessage = 'You do not have permission to reject payment.';
            $this->showErrorModal = true;
            return;
        }

        $this->order->update([
            'payment_verified' => false,
            'status' => 'awaiting_payment',
            'payment_notes' => 'Payment proof was rejected. Please submit a valid payment proof.',
        ]);

        $this->closePaymentVerifyModal();
        $this->successMessage = 'Payment has been rejected. Customer will be notified to resubmit.';
        $this->showSuccessModal = true;
        $this->order->refresh();
    }

    // Shipping Methods
    public function openShippingModal()
    {
        $this->showShippingModal = true;
    }

    public function closeShippingModal()
    {
        $this->showShippingModal = false;
    }

    public function markAsShipped()
    {
        $this->validate([
            'trackingNumber' => 'nullable|string|max:255',
        ]);

        if (!$this->canManage) {
            $this->errorMessage = 'You do not have permission to ship this order.';
            $this->showErrorModal = true;
            return;
        }

        $this->order->update([
            'tracking_number' => $this->trackingNumber,
            'shipped_at' => now(),
            'status' => 'shipped',
        ]);

        // Send shipping notification
        SendSparePartShippedEmail::dispatch($this->order->fresh());

        $this->closeShippingModal();
        $this->successMessage = 'Order has been marked as shipped! Customer will be notified.';
        $this->showSuccessModal = true;
        $this->order->refresh();
    }

    public function markAsDelivered()
    {
        if (!$this->canManage) {
            $this->errorMessage = 'You do not have permission to mark this order as delivered.';
            $this->showErrorModal = true;
            return;
        }

        $this->order->update([
            'delivered_at' => now(),
            'status' => 'delivered',
        ]);

        $this->successMessage = 'Order has been marked as delivered!';
        $this->showSuccessModal = true;
        $this->order->refresh();
    }

    public function markAsCompleted()
    {
        if (!$this->canManage) {
            $this->errorMessage = 'You do not have permission to complete this order.';
            $this->showErrorModal = true;
            return;
        }

        $this->order->update([
            'status' => 'completed',
        ]);

        $this->successMessage = 'Order has been marked as completed!';
        $this->showSuccessModal = true;
        $this->order->refresh();
    }

    public function rejectOrder()
    {
        if (!$this->canManage) {
            $this->errorMessage = 'You do not have permission to reject this order.';
            $this->showErrorModal = true;
            return;
        }

        $this->order->update([
            'status' => 'rejected',
        ]);

        $this->successMessage = 'Order has been rejected.';
        $this->showSuccessModal = true;
        $this->order->refresh();
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
        return view('livewire.admin.spare-part-order-view');
    }
}

