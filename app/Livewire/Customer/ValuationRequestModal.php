<?php

namespace App\Livewire\Customer;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use App\Models\ValuationPrice;
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
    
    // Pricing
    public $standardPrice = null;
    public $urgentPrice = null;
    public $currency = 'TZS';
    public $currencySymbol = 'TSh';
    
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
        
        // Load dynamic pricing based on vehicle type
        $this->loadPricing();
        
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
    
    /**
     * Load pricing from database based on vehicle type
     */
    private function loadPricing()
    {
        // Determine type (default to 'car' if not a truck)
        $type = 'car';
        if ($this->vehicle && $this->vehicle->body_type) {
            $bodyType = strtolower($this->vehicle->body_type);
            if (str_contains($bodyType, 'truck') || str_contains($bodyType, 'lorry')) {
                $type = 'truck';
            }
        }
        
        // Get make ID if available
        $makeId = $this->vehicle?->vehicle_make_id;
        
        // Fetch standard and urgent pricing
        $standardPriceObj = ValuationPrice::getPrice($type, 'standard', $makeId);
        $urgentPriceObj = ValuationPrice::getPrice($type, 'urgent', $makeId);
        
        // Set prices and currency (fallback to defaults if not found)
        if ($standardPriceObj) {
            $this->standardPrice = $standardPriceObj->price;
            $this->currency = $standardPriceObj->currency;
            $this->currencySymbol = ValuationPrice::CURRENCY_SYMBOLS[$standardPriceObj->currency] ?? $standardPriceObj->currency;
        } else {
            // Fallback defaults
            $this->standardPrice = 50000;
            $this->currency = 'TZS';
            $this->currencySymbol = 'TSh';
        }
        
        if ($urgentPriceObj) {
            $this->urgentPrice = $urgentPriceObj->price;
        } else {
            // Fallback: urgent is standard + 50%
            $this->urgentPrice = $this->standardPrice * 1.5;
        }
    }
    
    /**
     * Get current selected price
     */
    public function getCurrentPrice(): float
    {
        return $this->urgency === 'urgent' ? $this->urgentPrice : $this->standardPrice;
    }
    
    /**
     * Get formatted current price
     */
    public function getFormattedCurrentPrice(): string
    {
        return $this->currencySymbol . ' ' . number_format($this->getCurrentPrice(), 0);
    }

    public function close()
    {
        $this->show = false;
        $this->reset(['vehicleId', 'vehicle', 'purpose', 'urgency', 'customerNotes', 'existingReport', 'standardPrice', 'urgentPrice', 'currency', 'currencySymbol']);
    }

    public function submit()
    {
        $this->validate();

        try {
            $orderType = OrderType::VALUATION_REPORT;
            
            // Use dynamic pricing
            $fee = $this->getCurrentPrice();
            
            $order = Order::create([
                'user_id' => Auth::id(),
                'vehicle_id' => $this->vehicleId,
                'order_type' => $orderType->value,
                'status' => OrderStatus::PENDING->value,
                'fee' => $fee,
                'payment_required' => $fee > 0,
                'payment_completed' => false,
                'order_data' => [
                    'purpose' => $this->purpose,
                    'urgency' => $this->urgency,
                    'currency' => $this->currency,
                    'standard_price' => $this->standardPrice,
                    'urgent_price' => $this->urgentPrice,
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
