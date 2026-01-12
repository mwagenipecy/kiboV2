<?php

namespace App\Livewire\Customer;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\LendingCriteria;
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
    public $matchingCriteria = [];
    public $selectedCriteriaId = null;
    public $selectedCriteria = null;
    
    // Form fields
    public $loanAmount = '';
    public $loanTermMonths = 36;
    public $downPaymentAmount = '';
    public $monthlyIncome = '';
    public $employmentMonths = '';
    public $creditScore = '';
    public $notes = '';
    public $agreeToTerms = false;
    
    public $step = 1; // 1 = select lender, 2 = application form

    #[On('open-financing-modal')]
    public function open($vehicleId)
    {
        $this->vehicleId = $vehicleId;
        $this->vehicle = Vehicle::with(['make', 'model'])->find($vehicleId);
        
        // Get all active lending criteria that match this vehicle
        $allCriteria = LendingCriteria::with('entity')
            ->active()
            ->orderBy('priority', 'desc')
            ->get();
        
        $this->matchingCriteria = $allCriteria->filter(function ($criterion) {
            return $criterion->vehicleMeetsCriteria($this->vehicle);
        })->values();
        
        $this->show = true;
        $this->step = 1;
        $this->resetForm();
    }
    
    public function selectLender($criteriaId)
    {
        $this->selectedCriteriaId = $criteriaId;
        $this->selectedCriteria = LendingCriteria::with('entity')->findOrFail($criteriaId);
        
        // Pre-fill some values based on the criteria
        $this->loanAmount = $this->vehicle->price;
        $this->calculateDownPayment();
        $this->loanTermMonths = $this->selectedCriteria->min_loan_term_months;
        
        $this->step = 2;
    }
    
    public function backToLenderSelection()
    {
        $this->step = 1;
        $this->selectedCriteriaId = null;
        $this->selectedCriteria = null;
    }
    
    public function calculateDownPayment()
    {
        if ($this->selectedCriteria && $this->vehicle) {
            $this->downPaymentAmount = ($this->vehicle->price * $this->selectedCriteria->down_payment_percentage) / 100;
        }
    }

    public function close()
    {
        $this->show = false;
        $this->step = 1;
        $this->resetForm();
    }
    
    public function resetForm()
    {
        $this->loanAmount = '';
        $this->loanTermMonths = 36;
        $this->downPaymentAmount = '';
        $this->monthlyIncome = '';
        $this->employmentMonths = '';
        $this->creditScore = '';
        $this->notes = '';
        $this->agreeToTerms = false;
        $this->selectedCriteriaId = null;
        $this->selectedCriteria = null;
    }

    public function submit()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to apply for financing.');
            return redirect()->route('login');
        }

        $this->validate([
            'loanAmount' => 'required|numeric|min:0',
            'loanTermMonths' => 'required|integer|min:' . $this->selectedCriteria->min_loan_term_months . '|max:' . $this->selectedCriteria->max_loan_term_months,
            'downPaymentAmount' => 'required|numeric|min:0',
            'monthlyIncome' => 'required|numeric|min:' . ($this->selectedCriteria->min_monthly_income ?? 0),
            'employmentMonths' => 'required|integer|min:' . ($this->selectedCriteria->min_employment_months ?? 0),
            'creditScore' => 'nullable|integer|min:300|max:850',
            'agreeToTerms' => 'accepted',
        ], [
            'loanTermMonths.min' => 'Minimum loan term is ' . $this->selectedCriteria->min_loan_term_months . ' months',
            'loanTermMonths.max' => 'Maximum loan term is ' . $this->selectedCriteria->max_loan_term_months . ' months',
            'monthlyIncome.min' => 'Minimum monthly income required is $' . number_format($this->selectedCriteria->min_monthly_income ?? 0),
            'employmentMonths.min' => 'Minimum employment duration is ' . ($this->selectedCriteria->min_employment_months ?? 0) . ' months',
        ]);

        try {
            // Calculate monthly payment
            $monthlyPayment = $this->selectedCriteria->calculateMonthlyPayment(
                $this->loanAmount - $this->downPaymentAmount,
                $this->loanTermMonths
            );

            // Create the financing application order
            Order::create([
                'user_id' => Auth::id(),
                'vehicle_id' => $this->vehicleId,
                'order_type' => OrderType::FINANCING_APPLICATION,
                'status' => OrderStatus::PENDING,
                'customer_notes' => $this->notes,
                'order_data' => [
                    'lending_criteria_id' => $this->selectedCriteria->id,
                    'lender_entity_id' => $this->selectedCriteria->entity_id,
                    'lender_name' => $this->selectedCriteria->entity->name,
                    'criteria_name' => $this->selectedCriteria->name,
                    'loan_amount' => $this->loanAmount,
                    'down_payment' => $this->downPaymentAmount,
                    'loan_term_months' => $this->loanTermMonths,
                    'interest_rate' => $this->selectedCriteria->interest_rate,
                    'monthly_payment' => $monthlyPayment,
                    'processing_fee' => $this->selectedCriteria->processing_fee,
                    'monthly_income' => $this->monthlyIncome,
                    'employment_months' => $this->employmentMonths,
                    'credit_score' => $this->creditScore,
                    'dealer_approval' => 'pending',
                    'lender_approval' => 'pending',
                ],
            ]);

            session()->flash('success', 'Your financing application has been submitted successfully!');
            
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
