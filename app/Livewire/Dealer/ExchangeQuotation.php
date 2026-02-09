<?php

namespace App\Livewire\Dealer;

use App\Jobs\SendExchangeQuotationMail;
use App\Models\CarExchangeRequest;
use App\Models\DealerExchangeQuotation;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ExchangeQuotation extends Component
{
    use WithFileUploads;

    public CarExchangeRequest $request;
    public $current_vehicle_valuation = '';
    public $desired_vehicle_price = '';
    public $offered_vehicle_id = '';
    public $message = '';
    public $quotation_documents = [];

    public function mount(int $id): void
    {
        $user = Auth::user();
        abort_unless($user && $user->isDealer(), 403);

        $this->request = CarExchangeRequest::with(['desiredMake', 'desiredModel', 'quotations'])
            ->findOrFail($id);
    }

    public function submitQuotation()
    {
        $user = Auth::user();
        abort_unless($user && $user->isDealer(), 403);

        $validated = $this->validate([
            'current_vehicle_valuation' => ['required', 'numeric', 'min:0'],
            'desired_vehicle_price' => ['required', 'numeric', 'min:0'],
            'offered_vehicle_id' => ['nullable', 'exists:vehicles,id'],
            'message' => ['nullable', 'string', 'max:2000'],
            'quotation_documents.*' => ['nullable', 'file', 'max:5120'], // 5MB max
        ]);

        // Calculate price difference
        $priceDifference = $validated['desired_vehicle_price'] - $validated['current_vehicle_valuation'];

        // Handle document uploads
        $documentPaths = [];
        if (!empty($this->quotation_documents)) {
            foreach ($this->quotation_documents as $doc) {
                $path = $doc->store('exchange-quotations', 'public');
                $documentPaths[] = $path;
            }
        }

        $quotation = DealerExchangeQuotation::create([
            'exchange_request_id' => $this->request->id,
            'entity_id' => $user->entity_id,
            'user_id' => $user->id,
            'current_vehicle_valuation' => $validated['current_vehicle_valuation'],
            'desired_vehicle_price' => $validated['desired_vehicle_price'],
            'price_difference' => $priceDifference,
            'currency' => 'TZS',
            'offered_vehicle_id' => $validated['offered_vehicle_id'] ?? null,
            'message' => $validated['message'] ?? null,
            'quotation_documents' => $documentPaths,
            'status' => 'pending',
        ]);

        // Queue email to customer (non-blocking)
        try {
            SendExchangeQuotationMail::dispatch($quotation);
            session()->flash('success', 'Quotation created successfully! The email will be sent to the customer shortly.');
        } catch (\Exception $e) {
            // Even if queuing fails, quotation is saved
            \Log::error('Failed to queue exchange quotation email: ' . $e->getMessage(), [
                'quotation_id' => $quotation->id,
            ]);
            session()->flash('success', 'Quotation created successfully! However, there was an issue queuing the email. The quotation has been saved and can be viewed by the customer.');
        }
        
        return $this->redirect(route('dealer.exchange-requests.index'), navigate: true);
    }

    public function render()
    {
        $availableVehicles = Vehicle::where('entity_id', Auth::user()->entity_id)
            ->where('status', 'approved')
            ->where('status', '!=', 'sold')
            ->with(['make', 'model'])
            ->get();

        return view('livewire.dealer.exchange-quotation', [
            'availableVehicles' => $availableVehicles,
        ])->layout('layouts.dealer');
    }
}
