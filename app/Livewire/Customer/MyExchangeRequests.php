<?php

namespace App\Livewire\Customer;

use App\Models\CarExchangeRequest;
use App\Models\DealerExchangeQuotation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyExchangeRequests extends Component
{
    public $showConfirmModal = false;
    public $quotationToConfirm = null;
    public $quotationDetails = null;

    public function openConfirmModal($quotationId)
    {
        $user = Auth::user();
        abort_unless($user, 403);

        $quotation = DealerExchangeQuotation::with(['exchangeRequest', 'entity', 'offeredVehicle'])
            ->whereHas('exchangeRequest', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->findOrFail($quotationId);

        // Check if request is already completed
        if ($quotation->exchangeRequest->status === 'completed') {
            session()->flash('error', 'This exchange request has already been completed.');
            return;
        }

        // Check if another quotation has already been accepted
        if ($quotation->exchangeRequest->accepted_quotation_id) {
            session()->flash('error', 'Another quotation has already been accepted for this request.');
            return;
        }

        $this->quotationToConfirm = $quotationId;
        $this->quotationDetails = $quotation;
        $this->showConfirmModal = true;
    }

    public function closeConfirmModal()
    {
        $this->showConfirmModal = false;
        $this->quotationToConfirm = null;
        $this->quotationDetails = null;
    }

    public function acceptQuotation()
    {
        if (!$this->quotationToConfirm) {
            return;
        }

        $user = Auth::user();
        abort_unless($user, 403);

        $quotation = DealerExchangeQuotation::with('exchangeRequest')
            ->whereHas('exchangeRequest', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->findOrFail($this->quotationToConfirm);

        // Check if request is already completed
        if ($quotation->exchangeRequest->status === 'completed') {
            session()->flash('error', 'This exchange request has already been completed.');
            return;
        }

        // Check if another quotation has already been accepted
        if ($quotation->exchangeRequest->accepted_quotation_id) {
            session()->flash('error', 'Another quotation has already been accepted for this request.');
            return;
        }

        // Allow confirmation for both 'sent' and 'pending' status quotations
        if (!in_array($quotation->status, ['sent', 'pending'])) {
            session()->flash('error', 'This quotation cannot be confirmed at this time.');
            return;
        }

        // Update quotation status
        $quotation->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        // Update exchange request to completed and set accepted quotation
        $quotation->exchangeRequest->update([
            'status' => 'completed',
            'accepted_quotation_id' => $quotation->id,
        ]);

        session()->flash('success', 'Quotation confirmed successfully! The exchange request is now completed and no further quotations can be submitted.');
        
        // Close modal
        $this->closeConfirmModal();
    }

    public function render()
    {
        $user = Auth::user();
        abort_unless($user, 403);

        $requests = CarExchangeRequest::with(['desiredMake', 'desiredModel', 'quotations.entity', 'quotations.offeredVehicle', 'acceptedQuotation'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('livewire.customer.my-exchange-requests', [
            'requests' => $requests,
        ])->layout('layouts.customer');
    }
}
