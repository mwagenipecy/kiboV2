<?php

namespace App\Livewire\Customer;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Entity;
use App\Models\Order;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class FinancingApplicationModal extends Component
{
    public $show = false;
    public $vehicleId;
    public $vehicle;
    public $lenders = [];
    
    // Form fields
    public $lenderId = '';
    public $downPayment = '';
    public $loanTerm = '36';
    public $employmentStatus = '';
    public $monthlyIncome = '';
    public $customerNotes = '';

    protected $rules = [
        'lenderId' => 'required|exists:entities,id',
        'downPayment' => 'required|numeric|min:0',
        'loanTerm' => 'required|in:12,24,36,48,60',
        'employmentStatus' => 'required|string',
        'monthlyIncome' => 'required|numeric|min:0',
        'customerNotes' => 'nullable|string|max:500',
    ];

    #[On('open-financing-modal')]
    public function open($vehicleId)
    {
        $this->vehicleId = $vehicleId;
        $this->vehicle = Vehicle::with(['make', 'model'])->find($vehicleId);
        
        // Get active lenders
        $this->lenders = Entity::where('type', 'lender')
            ->where('status', 'active')
            ->get();
        
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
        $this->reset();
    }

    public function submit()
    {
        $this->validate();

        try {
            $lender = Entity::find($this->lenderId);
            
            Order::create([
                'user_id' => Auth::id(),
                'vehicle_id' => $this->vehicleId,
                'order_type' => OrderType::FINANCING_APPLICATION->value,
                'status' => OrderStatus::PENDING->value,
                'fee' => 0,
                'payment_required' => false,
                'order_data' => [
                    'lender_id' => $this->lenderId,
                    'lender_name' => $lender->name,
                    'vehicle_price' => $this->vehicle->price,
                    'down_payment' => $this->downPayment,
                    'loan_amount' => $this->vehicle->price - $this->downPayment,
                    'loan_term' => $this->loanTerm,
                    'employment_status' => $this->employmentStatus,
                    'monthly_income' => $this->monthlyIncome,
                    'vehicle_details' => [
                        'make' => $this->vehicle->make->name ?? '',
                        'model' => $this->vehicle->model->name ?? '',
                        'year' => $this->vehicle->year,
                    ],
                ],
                'customer_notes' => $this->customerNotes,
            ]);

            session()->flash('success', 'Financing application submitted successfully! The lender will review your application.');
            
            $this->dispatch('order-created');
            $this->close();

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit application. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.customer.financing-application-modal');
    }
}
