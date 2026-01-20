<?php

namespace App\Livewire\Customer;

use App\Models\ImportFinancingOffer;
use App\Models\ImportFinancingRequest;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.customer', ['vehicleType' => 'import-financing'])]
class ImportFinancingRequestDetail extends Component
{
    public ImportFinancingRequest $request;
    public bool $showAcceptModal = false;
    public ?int $selectedOfferId = null;

    public function mount($id)
    {
        $this->request = ImportFinancingRequest::where('user_id', auth()->id())
            ->with(['offers.entity', 'offers.user'])
            ->findOrFail($id);
    }

    public function openAcceptModal(int $offerId)
    {
        $this->selectedOfferId = $offerId;
        $this->showAcceptModal = true;
    }

    public function closeAcceptModal()
    {
        $this->showAcceptModal = false;
        $this->selectedOfferId = null;
    }

    public function acceptOffer()
    {
        if (!$this->selectedOfferId) {
            return;
        }

        $offer = ImportFinancingOffer::where('import_financing_request_id', $this->request->id)
            ->findOrFail($this->selectedOfferId);

        // Update the offer status
        $offer->update(['status' => 'accepted']);

        // Reject all other pending offers
        ImportFinancingOffer::where('import_financing_request_id', $this->request->id)
            ->where('id', '!=', $this->selectedOfferId)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        // Update the request
        $this->request->update([
            'status' => 'accepted',
            'accepted_offer_id' => $this->selectedOfferId,
        ]);

        $this->closeAcceptModal();
        $this->request->refresh();

        session()->flash('success', 'You have successfully accepted the financing offer!');
    }

    public function cancelRequest()
    {
        if (!in_array($this->request->status, ['pending', 'under_review'])) {
            session()->flash('error', 'This request cannot be cancelled at this stage.');
            return;
        }

        $this->request->update(['status' => 'cancelled']);
        $this->request->refresh();

        session()->flash('success', 'Your financing request has been cancelled.');
    }

    public function render()
    {
        return view('livewire.customer.import-financing-request-detail');
    }
}

