<?php

namespace App\Livewire\Customer;

use App\Models\SparePartOrder;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SparePartOrderDetail extends Component
{
    use WithFileUploads;

    public $order;
    public $orderId;
    public $messages = [];
    public $newMessage = '';
    
    // Quote Response
    public $showQuoteResponseModal = false;
    public $selectedQuotationId = null;
    
    // Payment Modal
    public $showPaymentModal = false;
    public $paymentProof;
    public $paymentNotes = '';
    
    // Success/Error Modals
    public $showSuccessModal = false;
    public $showErrorModal = false;
    public $successMessage = '';
    public $errorMessage = '';

    public function mount($id)
    {
        $this->orderId = $id;
        $this->loadOrder();
        $this->loadMessages();
    }

    public function loadOrder()
    {
        $this->order = SparePartOrder::with(['vehicleMake', 'vehicleModel', 'user', 'assignedTo', 'quotations.agent', 'acceptedQuotation.agent'])
            ->where('id', $this->orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    public function loadMessages()
    {
        // Load messages - using array cast from model
        $this->messages = $this->order->chat_messages ?? [];
    }

    public function sendMessage()
    {
        $this->validate([
            'newMessage' => 'required|string|max:1000',
        ]);

        $message = [
            'id' => uniqid(),
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'message' => $this->newMessage,
            'created_at' => now()->toDateTimeString(),
        ];

        $currentMessages = $this->order->chat_messages ?? [];
        $currentMessages[] = $message;

        $this->order->update([
            'chat_messages' => $currentMessages
        ]);

        $this->newMessage = '';
        $this->loadMessages();
        $this->dispatch('message-sent');
    }

    public function getStatusColor($status)
    {
        return match($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'quoted' => 'bg-purple-100 text-purple-800',
            'accepted' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'awaiting_payment' => 'bg-orange-100 text-orange-800',
            'payment_submitted' => 'bg-indigo-100 text-indigo-800',
            'payment_verified' => 'bg-teal-100 text-teal-800',
            'shipped' => 'bg-blue-100 text-blue-800',
            'delivered' => 'bg-green-100 text-green-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // Quote Response Methods
    public function openQuoteResponseModal($quotationId = null)
    {
        $this->selectedQuotationId = $quotationId;
        $this->showQuoteResponseModal = true;
    }

    public function closeQuoteResponseModal()
    {
        $this->showQuoteResponseModal = false;
        $this->selectedQuotationId = null;
    }

    public function acceptQuotation($quotationId)
    {
        $quotation = \App\Models\SparePartQuotation::where('id', $quotationId)
            ->where('spare_part_order_id', $this->order->id)
            ->firstOrFail();

        if (!$this->order->isOpenForQuotations() && $this->order->status !== 'quoted') {
            $this->errorMessage = 'This order is no longer accepting quotations.';
            $this->showErrorModal = true;
            return;
        }

        // Accept this quotation
        $quotation->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        // Reject all other quotations for this order
        \App\Models\SparePartQuotation::where('spare_part_order_id', $this->order->id)
            ->where('id', '!=', $quotationId)
            ->update([
                'status' => 'rejected',
                'rejected_at' => now(),
            ]);

        // Update order with accepted quotation
        $this->order->update([
            'status' => 'accepted',
            'accepted_quotation_id' => $quotationId,
            'agent_id' => $quotation->agent_id,
            'quoted_price' => $quotation->quoted_price,
            'currency' => $quotation->currency,
            'quotation_notes' => $quotation->quotation_notes,
            'user_confirmed_at' => now(),
            'user_accepted_quote' => true,
        ]);

        $this->closeQuoteResponseModal();
        $this->successMessage = 'You have accepted this quotation! The supplier will now prepare your order and send payment details.';
        $this->showSuccessModal = true;
        $this->loadOrder();
    }

    public function rejectQuote()
    {
        if ($this->order->status !== 'quoted') {
            $this->errorMessage = 'This order cannot be rejected at this time.';
            $this->showErrorModal = true;
            return;
        }

        // Reject all quotations
        \App\Models\SparePartQuotation::where('spare_part_order_id', $this->order->id)
            ->update([
                'status' => 'rejected',
                'rejected_at' => now(),
            ]);

        $this->order->update([
            'status' => 'rejected',
            'user_confirmed_at' => now(),
            'user_accepted_quote' => false,
        ]);

        $this->closeQuoteResponseModal();
        $this->successMessage = 'You have rejected all quotations. You can submit a new order anytime.';
        $this->showSuccessModal = true;
        $this->loadOrder();
    }

    // Payment Methods
    public function openPaymentModal()
    {
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->paymentProof = null;
        $this->paymentNotes = '';
    }

    public function submitPayment()
    {
        $this->validate([
            'paymentProof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'paymentNotes' => 'nullable|string|max:500',
        ]);

        if ($this->order->status !== 'awaiting_payment') {
            $this->errorMessage = 'Payment cannot be submitted at this time.';
            $this->showErrorModal = true;
            return;
        }

        // Store the payment proof
        $path = $this->paymentProof->store('payment-proofs', 'public');

        $this->order->update([
            'payment_proof' => $path,
            'payment_notes' => $this->paymentNotes,
            'payment_submitted_at' => now(),
            'status' => 'payment_submitted',
        ]);

        $this->closePaymentModal();
        $this->successMessage = 'Your payment proof has been submitted successfully! We will verify it shortly.';
        $this->showSuccessModal = true;
        $this->loadOrder();
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
        return view('livewire.customer.spare-part-order-detail');
    }
}

