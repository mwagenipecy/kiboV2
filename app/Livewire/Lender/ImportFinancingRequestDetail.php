<?php

namespace App\Livewire\Lender;

use App\Models\ImportFinancingOffer;
use App\Models\ImportFinancingRequest;
use Livewire\Component;

class ImportFinancingRequestDetail extends Component
{
    public ImportFinancingRequest $request;
    
    // Offer form fields
    public ?float $offeredAmount = null;
    public float $interestRate = 15;
    public int $loanTermMonths = 36;
    public ?float $processingFee = null;
    public ?string $termsConditions = '';
    public ?string $notes = '';
    public ?string $validUntil = null;

    // Calculated values
    public float $monthlyPayment = 0;
    public float $totalRepayment = 0;

    public bool $showOfferForm = false;

    protected $rules = [
        'offeredAmount' => 'required|numeric|min:1000',
        'interestRate' => 'required|numeric|min:0|max:100',
        'loanTermMonths' => 'required|integer|min:6|max:120',
        'processingFee' => 'nullable|numeric|min:0',
        'termsConditions' => 'nullable|string|max:5000',
        'notes' => 'nullable|string|max:2000',
        'validUntil' => 'nullable|date|after:today',
    ];

    public function mount($id)
    {
        $this->request = ImportFinancingRequest::with(['user', 'offers.entity'])
            ->whereIn('status', ['with_lenders', 'offer_received', 'accepted', 'completed'])
            ->findOrFail($id);
        
        // Pre-fill with requested values
        $this->offeredAmount = $this->request->financing_amount_requested;
        $this->loanTermMonths = $this->request->loan_term_months ?? 36;
        $this->validUntil = now()->addDays(14)->format('Y-m-d');
        
        $this->calculatePayments();
    }

    public function updated($property)
    {
        if (in_array($property, ['offeredAmount', 'interestRate', 'loanTermMonths'])) {
            $this->calculatePayments();
        }
    }

    protected function calculatePayments()
    {
        if (!$this->offeredAmount || $this->offeredAmount <= 0 || $this->loanTermMonths <= 0) {
            $this->monthlyPayment = 0;
            $this->totalRepayment = 0;
            return;
        }

        $monthlyRate = ($this->interestRate / 100) / 12;
        
        if ($monthlyRate > 0) {
            $this->monthlyPayment = $this->offeredAmount * 
                ($monthlyRate * pow(1 + $monthlyRate, $this->loanTermMonths)) / 
                (pow(1 + $monthlyRate, $this->loanTermMonths) - 1);
        } else {
            $this->monthlyPayment = $this->offeredAmount / $this->loanTermMonths;
        }

        $this->totalRepayment = $this->monthlyPayment * $this->loanTermMonths;
    }

    public function toggleOfferForm()
    {
        $this->showOfferForm = !$this->showOfferForm;
    }

    public function submitOffer()
    {
        $this->validate();
        $this->calculatePayments();

        $user = auth()->user();
        $entity = $user->entity;

        if (!$entity) {
            session()->flash('error', 'You must be associated with a lender entity to submit offers.');
            return;
        }

        // Check if this lender already made an offer
        $existingOffer = ImportFinancingOffer::where('import_financing_request_id', $this->request->id)
            ->where('entity_id', $entity->id)
            ->first();

        if ($existingOffer) {
            session()->flash('error', 'You have already submitted an offer for this request.');
            return;
        }

        ImportFinancingOffer::create([
            'import_financing_request_id' => $this->request->id,
            'entity_id' => $entity->id,
            'user_id' => $user->id,
            'offered_amount' => $this->offeredAmount,
            'interest_rate' => $this->interestRate,
            'loan_term_months' => $this->loanTermMonths,
            'monthly_payment' => $this->monthlyPayment,
            'processing_fee' => $this->processingFee,
            'total_repayment' => $this->totalRepayment,
            'terms_conditions' => $this->termsConditions,
            'notes' => $this->notes,
            'valid_until' => $this->validUntil,
            'status' => 'pending',
        ]);

        // Update request status if this is the first offer
        if ($this->request->status === 'with_lenders') {
            $this->request->update(['status' => 'offer_received']);
        }

        $this->showOfferForm = false;
        $this->request->refresh();

        session()->flash('success', 'Your financing offer has been submitted successfully!');
    }

    public function render()
    {
        $user = auth()->user();
        $entity = $user->entity ?? null;
        
        $myOffer = null;
        if ($entity) {
            $myOffer = $this->request->offers->where('entity_id', $entity->id)->first();
        }

        return view('livewire.lender.import-financing-request-detail', [
            'myOffer' => $myOffer,
        ])->layout('layouts.lender');
    }
}

