<?php

namespace App\Livewire\Customer;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ValuationRequestModal extends Component
{
    public $show = false;
    public $vehicleId;
    public $vehicle;
    public $existingReport = null;
    
    // Form fields
    public $purpose = '';
    public $urgency = 'standard';
    public $customerNotes = '';

    protected $rules = [
        'purpose' => 'required|string',
        'urgency' => 'required|in:standard,urgent',
        'customerNotes' => 'nullable|string|max:500',
    ];

    #[On('open-valuation-modal')]
    public function open($vehicleId)
    {
        $this->vehicleId = $vehicleId;
        $this->vehicle = Vehicle::with(['make', 'model'])->find($vehicleId);
        
        // Check if user already has a valuation report for this vehicle
        $this->existingReport = Order::where('user_id', Auth::id())
            ->where('vehicle_id', $vehicleId)
            ->where('order_type', OrderType::VALUATION_REPORT->value)
            ->whereIn('status', [
                OrderStatus::PENDING->value,
                OrderStatus::PROCESSING->value,
                OrderStatus::APPROVED->value,
                OrderStatus::COMPLETED->value
            ])
            ->first();
        
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
        $this->reset(['vehicleId', 'vehicle', 'purpose', 'urgency', 'customerNotes', 'existingReport']);
    }

    public function submit()
    {
        $this->validate();

        try {
            $orderType = OrderType::VALUATION_REPORT;
            
            $order = Order::create([
                'user_id' => Auth::id(),
                'vehicle_id' => $this->vehicleId,
                'order_type' => $orderType->value,
                'status' => OrderStatus::PENDING->value,
                'fee' => $orderType->fee(),
                'payment_required' => $orderType->requiresPayment(),
                'payment_completed' => false,
                'order_data' => [
                    'purpose' => $this->purpose,
                    'urgency' => $this->urgency,
                    'vehicle_details' => [
                        'make' => $this->vehicle->make->name ?? '',
                        'model' => $this->vehicle->model->name ?? '',
                        'year' => $this->vehicle->year,
                        'price' => $this->vehicle->price,
                    ],
                ],
                'customer_notes' => $this->customerNotes,
            ]);

            session()->flash('success', 'Valuation report requested successfully! You can track it in your dashboard.');
            
            $this->dispatch('order-created');
            $this->close();
            
            // Redirect to payment if required
            if ($order->requiresPayment()) {
                return redirect()->route('profile.edit')->with('showPayment', $order->id);
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit request. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.customer.valuation-request-modal');
    }
}
