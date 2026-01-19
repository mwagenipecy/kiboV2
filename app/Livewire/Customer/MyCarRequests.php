<?php

namespace App\Livewire\Customer;

use App\Models\CarRequest;
use App\Models\DealerCarOffer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MyCarRequests extends Component
{
    public $showConfirmModal = false;
    public $selectedOffer = null;

    public function showAcceptConfirmation(int $offerId): void
    {
        $user = Auth::user();
        abort_unless($user, 403);

        $offer = DealerCarOffer::with(['request', 'entity'])->findOrFail($offerId);
        $request = $offer->request;

        abort_unless($request && $request->user_id === $user->id, 403);
        abort_unless($request->status === 'open', 400);
        abort_unless($offer->status === 'submitted', 400);

        $this->selectedOffer = $offer;
        $this->showConfirmModal = true;
    }

    public function closeConfirmModal(): void
    {
        $this->showConfirmModal = false;
        $this->selectedOffer = null;
    }

    public function confirmAcceptOffer(): void
    {
        $user = Auth::user();
        abort_unless($user, 403);
        abort_unless($this->selectedOffer, 400);

        $offer = $this->selectedOffer;
        $request = $offer->request;

        abort_unless($request && $request->user_id === $user->id, 403);
        abort_unless($request->status === 'open', 400);

        DB::transaction(function () use ($request, $offer) {
            $request->update([
                'status' => 'closed',
                'accepted_offer_id' => $offer->id,
            ]);

            DealerCarOffer::where('car_request_id', $request->id)
                ->where('id', '!=', $offer->id)
                ->update(['status' => 'rejected']);

            $offer->update(['status' => 'accepted']);
        });

        $this->closeConfirmModal();
        session()->flash('my_car_requests_success', 'Offer accepted. This request is now closed.');
    }

    public function render()
    {
        $user = Auth::user();
        abort_unless($user, 403);

        $requests = CarRequest::with(['make', 'model', 'offers.entity'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('livewire.customer.my-car-requests', [
            'requests' => $requests,
        ])->layout('layouts.customer');
    }
}


