<?php

namespace App\Livewire\Customer;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\VehicleStatus;
use App\Models\Order;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CashPurchaseModal extends Component
{
    public $showModal = false;
    public $vehicle = null;
    public $customerNotes = '';
    public $agreedTerms = false;

    protected $rules = [
        'customerNotes' => 'nullable|string|max:500',
        'agreedTerms' => 'required|accepted',
    ];

    protected $messages = [
        'agreedTerms.required' => 'You must agree to the terms and conditions',
        'agreedTerms.accepted' => 'You must agree to the terms and conditions',
    ];

    #[On('open-cash-purchase-modal')]
    public function openModal($vehicleId)
    {
        logger('Cash purchase modal opened for vehicle: ' . $vehicleId);
        
        $this->vehicle = Vehicle::with(['make', 'model', 'entity'])->findOrFail($vehicleId);
        
        // Check if vehicle is available
        if ($this->vehicle->status !== VehicleStatus::APPROVED) {
            session()->flash('error', 'This vehicle is not available for purchase.');
            return;
        }

        $this->showModal = true;
        $this->customerNotes = '';
        $this->agreedTerms = false;
        
        logger('Modal showModal set to: ' . ($this->showModal ? 'true' : 'false'));
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->vehicle = null;
        $this->customerNotes = '';
        $this->agreedTerms = false;
        $this->resetValidation();
    }

    public function submitOrder()
    {
        $this->validate();

        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to place an order.');
            return redirect()->route('login');
        }

        if (!$this->vehicle) {
            session()->flash('error', 'Vehicle not found.');
            return;
        }

        // Check if vehicle is still available
        $this->vehicle->refresh();
        if ($this->vehicle->status !== VehicleStatus::APPROVED) {
            session()->flash('error', 'This vehicle is no longer available for purchase.');
            $this->closeModal();
            return;
        }

        // Check if user already has a pending order for this vehicle
        $existingOrder = Order::where('user_id', Auth::id())
            ->where('vehicle_id', $this->vehicle->id)
            ->where('order_type', OrderType::CASH_PURCHASE->value)
            ->whereIn('status', [OrderStatus::PENDING->value, OrderStatus::PROCESSING->value, OrderStatus::APPROVED->value])
            ->first();

        if ($existingOrder) {
            session()->flash('error', 'You already have a pending order for this vehicle.');
            $this->closeModal();
            return;
        }

        // Create the order
        $order = Order::create([
            'user_id' => Auth::id(),
            'vehicle_id' => $this->vehicle->id,
            'order_type' => OrderType::CASH_PURCHASE->value,
            'status' => OrderStatus::PENDING->value,
            'fee' => 0,
            'payment_required' => false,
            'payment_completed' => false,
            'customer_notes' => $this->customerNotes,
            'order_data' => [
                'vehicle_price' => $this->vehicle->price,
                'vehicle_make' => $this->vehicle->make->name ?? '',
                'vehicle_model' => $this->vehicle->model->name ?? '',
                'vehicle_year' => $this->vehicle->year,
                'submitted_at' => now()->toDateTimeString(),
            ],
        ]);

        session()->flash('success', 'Your purchase order has been submitted successfully! Order #' . $order->order_number);
        
        $this->closeModal();
        $this->dispatch('order-submitted');
    }

    public function render()
    {
        return view('livewire.customer.cash-purchase-modal');
    }
}
