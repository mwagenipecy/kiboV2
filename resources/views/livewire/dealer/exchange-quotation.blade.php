<div class="p-6 space-y-6">
    <div>
        <a href="{{ route('dealer.exchange-requests.index') }}" class="text-green-600 hover:text-green-800 mb-4 inline-block">← Back to Exchange Requests</a>
        <h1 class="text-2xl font-bold text-gray-900">Send Quotation</h1>
        <p class="text-gray-600">Submit a quotation for this exchange request.</p>
    </div>

    @if (session()->has('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Request Details -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Exchange Request Details</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <h3 class="font-medium text-gray-700 mb-2">Current Vehicle</h3>
                <p class="text-gray-900">{{ $request->current_vehicle_make }} {{ $request->current_vehicle_model }}</p>
                <p class="text-sm text-gray-600">Year: {{ $request->current_vehicle_year }} · Mileage: {{ number_format($request->current_vehicle_mileage ?? 0) }} km</p>
            </div>
            <div>
                <h3 class="font-medium text-gray-700 mb-2">Desired Vehicle</h3>
                <p class="text-gray-900">{{ $request->desiredMake?->name ?? 'Any' }} {{ $request->desiredModel?->name ?? '' }}</p>
                @if($request->max_budget)
                    <p class="text-sm text-gray-600">Budget: {{ number_format($request->max_budget) }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quotation Form -->
    <form wire:submit.prevent="submitQuotation" class="bg-white border border-gray-200 rounded-xl p-6 space-y-6">
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Vehicle Valuation (TZS) *</label>
                <input 
                    type="number" 
                    wire:model.defer="current_vehicle_valuation" 
                    step="0.01"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                    placeholder="e.g., 15000000"
                    required
                >
                @error('current_vehicle_valuation') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Desired Vehicle Price (TZS) *</label>
                <input 
                    type="number" 
                    wire:model.defer="desired_vehicle_price" 
                    step="0.01"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                    placeholder="e.g., 25000000"
                    required
                >
                @error('desired_vehicle_price') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Vehicle to Offer (Optional)</label>
            <select 
                wire:model.defer="offered_vehicle_id" 
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
            >
                <option value="">Select a vehicle from inventory</option>
                @foreach($availableVehicles as $vehicle)
                    <option value="{{ $vehicle->id }}">
                        {{ $vehicle->make->name }} {{ $vehicle->model->name }} {{ $vehicle->year }} - {{ number_format($vehicle->price) }} TZS
                    </option>
                @endforeach
            </select>
            @error('offered_vehicle_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Message to Customer</label>
            <textarea 
                wire:model.defer="message" 
                rows="4" 
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                placeholder="Add a personal message to the customer..."
            ></textarea>
            @error('message') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

<div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Quotation Documents (Optional)</label>
            <input 
                type="file" 
                wire:model="quotation_documents" 
                multiple
                accept=".pdf,.doc,.docx,image/*"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
            >
            <p class="text-xs text-gray-500 mt-1">You can upload multiple files (PDF, DOC, images - max 5MB each)</p>
            @error('quotation_documents.*') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="pt-4">
            <button 
                type="submit" 
                class="w-full text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200" style="background-color: #009866;"
            >
                Send Quotation via Email
            </button>
        </div>
    </form>
</div>
