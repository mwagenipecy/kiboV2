<?php

namespace App\Livewire\Dealer;

use App\Models\CarRequest;
use App\Models\DealerCarOffer;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CarRequests extends Component
{
    use WithFileUploads;

    public $selectedRequestId = null;
    public $price = '';
    public $message = '';
    public $image;

    public function submitOffer(int $requestId)
    {
        $user = Auth::user();
        abort_unless($user && $user->isDealer(), 403);

        $request = CarRequest::where('status', 'open')->findOrFail($requestId);

        $validated = $this->validate([
            'price' => ['nullable', 'integer', 'min:0'],
            'message' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $imagePath = null;
        if (!empty($this->image)) {
            $imagePath = $this->image->store('car-request-offers', 'public');
        }

        DealerCarOffer::updateOrCreate(
            [
                'car_request_id' => $request->id,
                'entity_id' => $user->entity_id,
            ],
            [
                'user_id' => $user->id,
                'price' => $validated['price'] ?? null,
                'message' => $validated['message'] ?? null,
                'image_path' => $imagePath,
                'status' => 'submitted',
            ]
        );

        $this->reset(['selectedRequestId', 'price', 'message', 'image']);
        session()->flash('dealer_offer_success', 'Offer submitted.');
    }

    public function render()
    {
        $user = Auth::user();
        abort_unless($user && $user->isDealer(), 403);

        $requests = CarRequest::with(['make', 'model'])
            ->where('status', 'open')
            ->latest()
            ->get();

        $myOffers = DealerCarOffer::where('entity_id', $user->entity_id)->get()->keyBy('car_request_id');

        return view('livewire.dealer.car-requests', [
            'requests' => $requests,
            'myOffers' => $myOffers,
        ])->layout('layouts.dealer');
    }
}


