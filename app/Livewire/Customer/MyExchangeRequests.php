<?php

namespace App\Livewire\Customer;

use App\Models\CarExchangeRequest;
use App\Models\DealerExchangeQuotation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyExchangeRequests extends Component
{
    public function acceptQuotation($quotationId)
    {
        $user = Auth::user();
        abort_unless($user, 403);

        $quotation = DealerExchangeQuotation::with('exchangeRequest')
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

        session()->flash('success', 'Quotation accepted successfully! The exchange request is now completed and no further quotations can be submitted.');
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
