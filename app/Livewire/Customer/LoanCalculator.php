<?php

namespace App\Livewire\Customer;

use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.customer', ['vehicleType' => 'loan-calculator'])]
class LoanCalculator extends Component
{
    // Loan Details
    public $vehiclePrice = 5000000;
    public $downPayment = 1000000;
    public $loanTerm = 60; // months
    public $interestRate = 15; // annual percentage
    public $currency = 'TZS';

    // Additional Costs
    public $includeInsurance = true;
    public $insuranceRate = 3; // percentage of vehicle price per year
    public $includeRegistration = true;
    public $registrationFee = 150000;
    public $includeProcessingFee = true;
    public $processingFeeRate = 1; // percentage of loan amount

    // Results
    public $loanAmount = 0;
    public $monthlyPayment = 0;
    public $totalInterest = 0;
    public $totalPayment = 0;
    public $monthlyInsurance = 0;
    public $processingFee = 0;
    public $effectiveRate = 0;
    public $amortizationSchedule = [];

    // UI States
    public $showAmortization = false;
    public $showComparison = false;

    // Comparison data
    public $comparisonTerms = [36, 48, 60, 72, 84];
    public $comparisonResults = [];

    protected $rules = [
        'vehiclePrice' => 'required|numeric|min:100000',
        'downPayment' => 'required|numeric|min:0',
        'loanTerm' => 'required|integer|min:6|max:120',
        'interestRate' => 'required|numeric|min:0|max:100',
        'insuranceRate' => 'nullable|numeric|min:0|max:50',
        'registrationFee' => 'nullable|numeric|min:0',
        'processingFeeRate' => 'nullable|numeric|min:0|max:10',
    ];

    public function mount()
    {
        $this->calculate();
    }

    public function updated($property)
    {
        if (in_array($property, [
            'vehiclePrice', 'downPayment', 'loanTerm', 'interestRate',
            'includeInsurance', 'insuranceRate', 'includeRegistration',
            'registrationFee', 'includeProcessingFee', 'processingFeeRate'
        ])) {
            $this->calculate();
        }
    }

    public function calculate()
    {
        // Validate down payment
        if ($this->downPayment > $this->vehiclePrice) {
            $this->downPayment = $this->vehiclePrice * 0.2;
        }

        // Calculate loan amount
        $this->loanAmount = max(0, $this->vehiclePrice - $this->downPayment);

        if ($this->loanAmount <= 0) {
            $this->resetResults();
            return;
        }

        // Calculate processing fee
        $this->processingFee = $this->includeProcessingFee 
            ? ($this->loanAmount * $this->processingFeeRate / 100) 
            : 0;

        // Monthly interest rate
        $monthlyRate = ($this->interestRate / 100) / 12;

        // Calculate monthly payment using PMT formula
        if ($monthlyRate > 0) {
            $this->monthlyPayment = $this->loanAmount * 
                ($monthlyRate * pow(1 + $monthlyRate, $this->loanTerm)) / 
                (pow(1 + $monthlyRate, $this->loanTerm) - 1);
        } else {
            $this->monthlyPayment = $this->loanAmount / $this->loanTerm;
        }

        // Calculate total payment and interest
        $this->totalPayment = $this->monthlyPayment * $this->loanTerm;
        $this->totalInterest = $this->totalPayment - $this->loanAmount;

        // Calculate monthly insurance
        $this->monthlyInsurance = $this->includeInsurance 
            ? ($this->vehiclePrice * ($this->insuranceRate / 100) / 12) 
            : 0;

        // Calculate effective annual rate (including all fees)
        $totalCost = $this->totalPayment + $this->processingFee + 
            ($this->includeRegistration ? $this->registrationFee : 0) +
            ($this->monthlyInsurance * $this->loanTerm);
        
        $this->effectiveRate = (($totalCost / $this->loanAmount) - 1) / ($this->loanTerm / 12) * 100;

        // Generate amortization schedule
        $this->generateAmortizationSchedule();

        // Generate comparison data
        $this->generateComparison();
    }

    protected function resetResults()
    {
        $this->monthlyPayment = 0;
        $this->totalInterest = 0;
        $this->totalPayment = 0;
        $this->monthlyInsurance = 0;
        $this->processingFee = 0;
        $this->effectiveRate = 0;
        $this->amortizationSchedule = [];
        $this->comparisonResults = [];
    }

    protected function generateAmortizationSchedule()
    {
        $this->amortizationSchedule = [];
        $balance = $this->loanAmount;
        $monthlyRate = ($this->interestRate / 100) / 12;

        for ($month = 1; $month <= $this->loanTerm; $month++) {
            $interestPayment = $balance * $monthlyRate;
            $principalPayment = $this->monthlyPayment - $interestPayment;
            $balance = max(0, $balance - $principalPayment);

            $this->amortizationSchedule[] = [
                'month' => $month,
                'payment' => $this->monthlyPayment,
                'principal' => $principalPayment,
                'interest' => $interestPayment,
                'balance' => $balance,
            ];
        }
    }

    protected function generateComparison()
    {
        $this->comparisonResults = [];
        $monthlyRate = ($this->interestRate / 100) / 12;

        foreach ($this->comparisonTerms as $term) {
            if ($monthlyRate > 0) {
                $payment = $this->loanAmount * 
                    ($monthlyRate * pow(1 + $monthlyRate, $term)) / 
                    (pow(1 + $monthlyRate, $term) - 1);
            } else {
                $payment = $this->loanAmount / $term;
            }

            $totalPaid = $payment * $term;
            $totalInterest = $totalPaid - $this->loanAmount;

            $this->comparisonResults[] = [
                'term' => $term,
                'termYears' => $term / 12,
                'monthlyPayment' => $payment,
                'totalPayment' => $totalPaid,
                'totalInterest' => $totalInterest,
                'selected' => $term == $this->loanTerm,
            ];
        }
    }

    public function toggleAmortization()
    {
        $this->showAmortization = !$this->showAmortization;
    }

    public function toggleComparison()
    {
        $this->showComparison = !$this->showComparison;
    }

    public function selectTerm($term)
    {
        $this->loanTerm = $term;
        $this->calculate();
    }

    public function downloadPdf()
    {
        $data = [
            'vehiclePrice' => $this->vehiclePrice,
            'downPayment' => $this->downPayment,
            'loanAmount' => $this->loanAmount,
            'loanTerm' => $this->loanTerm,
            'interestRate' => $this->interestRate,
            'monthlyPayment' => $this->monthlyPayment,
            'totalInterest' => $this->totalInterest,
            'totalPayment' => $this->totalPayment,
            'monthlyInsurance' => $this->monthlyInsurance,
            'includeInsurance' => $this->includeInsurance,
            'insuranceRate' => $this->insuranceRate,
            'processingFee' => $this->processingFee,
            'registrationFee' => $this->includeRegistration ? $this->registrationFee : 0,
            'effectiveRate' => $this->effectiveRate,
            'currency' => $this->currency,
            'amortizationSchedule' => array_slice($this->amortizationSchedule, 0, 12), // First year
            'fullSchedule' => $this->amortizationSchedule,
            'comparisonResults' => $this->comparisonResults,
            'generatedAt' => now()->format('F d, Y H:i'),
        ];

        $pdf = Pdf::loadView('pdf.loan-calculator', $data);
        $pdf->setPaper('A4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'loan-calculation-' . now()->format('Y-m-d') . '.pdf');
    }

    public function render()
    {
        return view('livewire.customer.loan-calculator');
    }
}

