<?php

namespace App\Livewire\Admin;

use App\Enums\VehicleStatus;
use App\Jobs\SendExchangeQuotationMail;
use App\Models\CarExchangeRequest;
use App\Models\DealerExchangeQuotation;
use App\Models\Entity;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ExchangeRequestDetail extends Component
{
    use WithFileUploads;

    public CarExchangeRequest $request;
    public $sendToAll = false;
    public $selectedDealerIds = [];
    public $dealerSearch = '';
    
    // Quotation form properties
    public $showQuotationForm = false;
    public $current_vehicle_valuation = '';
    public $desired_vehicle_price = '';
    public $offered_vehicle_id = '';
    public $message = '';
    public $quotation_documents = [];

    public function mount(int $id): void
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();
        
        $query = CarExchangeRequest::with(['desiredMake', 'desiredModel', 'quotations.entity', 'quotations.user', 'user']);
        
        // If dealer, only allow access to requests with status 'sent_to_dealers'
        if (!$isAdmin && $user->isDealer()) {
            $query->where('status', 'sent_to_dealers');
        }
        
        $this->request = $query->findOrFail($id);
    }

    public function updatedSendToAll($value): void
    {
        if ($value) {
            // If "send to all" is checked, select all dealer IDs
            $this->selectedDealerIds = Entity::where('type', 'dealer')
                ->where('status', 'active')
                ->pluck('id')
                ->toArray();
        } else {
            // If unchecked, clear selections
            $this->selectedDealerIds = [];
        }
    }

    public function approve(): void
    {
        $this->request->update([
            'status' => 'admin_approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        session()->flash('success', 'Exchange request approved successfully.');
        $this->request->refresh();
    }

    public function reject(): void
    {
        $this->request->update([
            'status' => 'rejected',
        ]);

        session()->flash('success', 'Exchange request rejected.');
        $this->request->refresh();
    }

    public function sendToDealers(): void
    {
        if ($this->sendToAll) {
            // Send to all active dealers
            $dealers = Entity::where('type', 'dealer')
                ->where('status', 'active')
                ->get();
        } else {
            // Validate selected dealers
            $validated = $this->validate([
                'selectedDealerIds' => ['required', 'array', 'min:1'],
                'selectedDealerIds.*' => ['exists:entities,id'],
            ]);

            $dealers = Entity::whereIn('id', $validated['selectedDealerIds'])
                ->where('type', 'dealer')
                ->where('status', 'active')
                ->get();
        }

        if ($dealers->isEmpty()) {
            session()->flash('error', 'No valid dealers selected.');
            return;
        }

        // Update request status
        $this->request->update([
            'status' => 'sent_to_dealers',
            'sent_to_dealer_id' => $dealers->first()->id, // Keep first for backward compatibility
            'sent_to_dealer_at' => now(),
        ]);

        // TODO: Send email notifications to all selected dealers
        // foreach ($dealers as $dealer) {
        //     SendExchangeRequestToDealerEmail::dispatch($dealer, $this->request);
        // }

        $dealerCount = $dealers->count();
        session()->flash('success', "Exchange request sent to {$dealerCount} dealer(s) successfully.");
        
        // Reset form
        $this->sendToAll = false;
        $this->selectedDealerIds = [];
        $this->request->refresh();
    }

    public function submitQuotation()
    {
        $user = Auth::user();
        
        // Check if request is already completed
        if ($this->request->status === 'completed') {
            session()->flash('error', 'This exchange request has been completed. No further quotations can be submitted.');
            return;
        }

        // Check if a quotation has already been accepted
        if ($this->request->accepted_quotation_id) {
            session()->flash('error', 'A quotation has already been accepted for this request. No further quotations can be submitted.');
            return;
        }
        
        // Only dealers can submit quotations
        if (!$user->isDealer() && !$user->isAdmin()) {
            session()->flash('error', 'Only dealers can submit quotations.');
            return;
        }

        // If admin, they need to be acting as a dealer (have entity_id)
        if ($user->isAdmin() && !$user->entity_id) {
            session()->flash('error', 'Admin users need to be associated with a dealer entity to submit quotations.');
            return;
        }

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

        // Convert empty string to null for offered_vehicle_id
        $offeredVehicleId = !empty($validated['offered_vehicle_id']) ? $validated['offered_vehicle_id'] : null;

        $quotation = DealerExchangeQuotation::create([
            'exchange_request_id' => $this->request->id,
            'entity_id' => $user->entity_id,
            'user_id' => $user->id,
            'current_vehicle_valuation' => $validated['current_vehicle_valuation'],
            'desired_vehicle_price' => $validated['desired_vehicle_price'],
            'price_difference' => $priceDifference,
            'currency' => 'TZS',
            'offered_vehicle_id' => $offeredVehicleId,
            'message' => !empty($validated['message']) ? $validated['message'] : null,
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

        // Reset form
        $this->showQuotationForm = false;
        $this->current_vehicle_valuation = '';
        $this->desired_vehicle_price = '';
        $this->offered_vehicle_id = '';
        $this->message = '';
        $this->quotation_documents = [];
        
        // Refresh request to show new quotation
        $this->request->refresh();
    }

    public function render()
    {
        $query = Entity::where('type', 'dealer')
            ->where('status', 'active');

        // Apply search filter
        if (!empty($this->dealerSearch)) {
            $search = $this->dealerSearch;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $dealers = $query->orderBy('name')->get();

        // Get available vehicles for quotation (if user is dealer or admin with entity_id)
        $availableVehicles = collect();
        $user = Auth::user();
        if ($user->entity_id) {
            $availableVehicles = Vehicle::where('entity_id', $user->entity_id)
                ->where('status', VehicleStatus::APPROVED)
                ->where('status', '!=', VehicleStatus::SOLD)
                ->with(['make', 'model'])
                ->get();
        }
        
        return view('livewire.admin.exchange-request-detail', [
            'dealers' => $dealers,
            'availableVehicles' => $availableVehicles,
        ])->layout('layouts.admin');
    }
}
