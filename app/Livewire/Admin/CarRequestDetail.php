<?php

namespace App\Livewire\Admin;

use App\Models\CarRequest;
use App\Models\DealerCarOffer;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CarRequestDetail extends Component
{
    use WithFileUploads;

    public CarRequest $request;

    public $price = '';
    public $message = '';
    public $image;

    public function mount(int $id): void
    {
        $this->request = CarRequest::with(['make', 'model', 'offers.entity', 'offers.user'])
            ->findOrFail($id);
    }

    public function submitOffer(): void
    {
        $this->request->refresh();

        abort_unless($this->request->status === 'open', 400);

        $validated = $this->validate([
            'price' => ['nullable', 'integer', 'min:0'],
            'message' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        // Convert empty strings to null so MySQL doesn't get '' for integer columns
        $validated = array_map(function ($value) {
            return $value === '' ? null : $value;
        }, $validated);

        $imagePath = null;
        if (!empty($this->image)) {
            $imagePath = $this->image->store('car-request-offers', 'public');
        }

        // Prevent duplicates (double-click / double submit):
        // keep exactly ONE admin offer per request per admin user.
        DealerCarOffer::updateOrCreate(
            [
                'car_request_id' => $this->request->id,
                'user_id' => Auth::id(),
                'entity_id' => null,
            ],
            [
                'price' => $validated['price'] ?? null,
                'message' => $validated['message'] ?? null,
                'image_path' => $imagePath,
                'status' => 'submitted',
            ]
        );

        $this->reset(['price', 'message', 'image']);
        $this->request->refresh();
        session()->flash('admin_offer_success', 'Offer submitted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.car-request-detail', [
            'carRequest' => $this->request,
        ])->layout('layouts.admin');
    }
}


