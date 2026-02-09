<div class="p-6 space-y-6">
    <div>
        <a href="{{ route('admin.exchange-requests.index') }}" class="text-green-600 hover:text-green-800 mb-4 inline-block">← Back to Exchange Requests</a>
        <h1 class="text-2xl font-bold text-gray-900">Exchange Request Details</h1>
    </div>

    @if (session()->has('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <!-- Request Information -->
    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Request Information</h2>
        
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-medium text-gray-700 mb-2">Customer Details</h3>
                <p class="text-gray-900">{{ $request->customer_name }}</p>
                <p class="text-sm text-gray-600">{{ $request->customer_email }}</p>
                @if($request->customer_phone)
                    <p class="text-sm text-gray-600">{{ $request->customer_phone }}</p>
                @endif
                @if($request->location)
                    <p class="text-sm text-gray-600 mt-1">📍 {{ $request->location }}</p>
                @endif
            </div>

            <div>
                <h3 class="font-medium text-gray-700 mb-2">Status</h3>
                @if($request->status === 'pending')
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 font-medium">Pending</span>
                @elseif($request->status === 'admin_approved')
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 font-medium">Approved</span>
                @elseif($request->status === 'sent_to_dealers')
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 font-medium">Sent to Dealers</span>
                @elseif($request->status === 'completed')
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-800 font-medium">Completed</span>
                @elseif($request->status === 'rejected')
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-800 font-medium">Rejected</span>
                @endif
            </div>
        </div>

        <div class="mt-6 grid md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-medium text-gray-700 mb-2">Current Vehicle</h3>
                <p class="text-gray-900">{{ $request->current_vehicle_make }} {{ $request->current_vehicle_model }}</p>
                <p class="text-sm text-gray-600">Year: {{ $request->current_vehicle_year }}</p>
                @if($request->current_vehicle_registration)
                    <p class="text-sm text-gray-600">Registration: {{ $request->current_vehicle_registration }}</p>
                @endif
                @if($request->current_vehicle_mileage)
                    <p class="text-sm text-gray-600">Mileage: {{ number_format($request->current_vehicle_mileage) }} km</p>
                @endif
                <p class="text-sm text-gray-600">Condition: {{ ucfirst($request->current_vehicle_condition) }}</p>
                @if($request->current_vehicle_description)
                    <p class="text-sm text-gray-600 mt-2">{{ $request->current_vehicle_description }}</p>
                @endif
            </div>

            <div>
                <h3 class="font-medium text-gray-700 mb-2">Desired Vehicle</h3>
                <p class="text-gray-900">{{ $request->desiredMake?->name ?? 'Any' }} {{ $request->desiredModel?->name ?? '' }}</p>
                @if($request->desired_min_year || $request->desired_max_year)
                    <p class="text-sm text-gray-600">Year: {{ $request->desired_min_year ?? 'Any' }} - {{ $request->desired_max_year ?? 'Any' }}</p>
                @endif
                @if($request->max_budget)
                    <p class="text-sm text-gray-600">Budget: {{ number_format($request->max_budget) }} TZS</p>
                @endif
            </div>
        </div>

        @if($request->notes)
        <div class="mt-6">
            <h3 class="font-medium text-gray-700 mb-2">Additional Notes</h3>
            <p class="text-gray-900">{{ $request->notes }}</p>
        </div>
        @endif
    </div>

    <!-- Actions (Admin Only) -->
    @auth
        @if(auth()->user()->isAdmin())
            @if($request->status === 'pending')
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                <div class="flex space-x-4">
                    <button wire:click="approve" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Approve Request
                    </button>
                    <button wire:click="reject" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Reject Request
                    </button>
                </div>
            </div>
            @endif

            @if($request->status === 'admin_approved')
    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Send to Dealers</h2>
        <form wire:submit.prevent="sendToDealers" class="space-y-4">
            <div class="flex items-center space-x-2 mb-4">
                <input 
                    type="checkbox" 
                    id="sendToAll" 
                    wire:model.live="sendToAll"
                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                >
                <label for="sendToAll" class="text-sm font-medium text-gray-700">
                    Send to All Active Dealers ({{ $dealers->count() }} dealers)
                </label>
            </div>

            @if(!$sendToAll)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Dealers</label>
                
                <!-- Search Input -->
                <div class="mb-3">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="dealerSearch"
                        placeholder="Search dealers by name, email, or phone..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                </div>

                @if($dealers->count() > 0)
                    <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-lg p-3 space-y-2">
                        @foreach($dealers as $dealer)
                            <label class="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    wire:model="selectedDealerIds" 
                                    value="{{ $dealer->id }}"
                                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                >
                                <div class="flex-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $dealer->name }}</span>
                                    @if($dealer->email)
                                        <span class="text-xs text-gray-500 ml-2">({{ $dealer->email }})</span>
                                    @endif
                                    @if($dealer->phone)
                                        <span class="text-xs text-gray-500 ml-2">{{ $dealer->phone }}</span>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Showing {{ $dealers->count() }} dealer(s)
                        @if(!empty($dealerSearch))
                            matching "{{ $dealerSearch }}"
                        @endif
                    </p>
                @else
                    <div class="border border-gray-300 rounded-lg p-6 text-center">
                        <p class="text-sm text-gray-600">No dealers found</p>
                        @if(!empty($dealerSearch))
                            <p class="text-xs text-gray-500 mt-1">Try a different search term</p>
                        @endif
                    </div>
                @endif

                @error('selectedDealerIds') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                @if(count($selectedDealerIds) > 0)
                    <p class="text-sm text-gray-600 mt-2 font-medium">{{ count($selectedDealerIds) }} dealer(s) selected</p>
                @endif
            </div>
            @else
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <p class="text-sm text-blue-800">
                        <strong>All {{ $dealers->count() }} active dealers</strong> will receive this exchange request.
                    </p>
                </div>
            @endif

            <div class="flex space-x-3 pt-4">
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium"
                    @if(!$sendToAll && count($selectedDealerIds) === 0) disabled @endif
                >
                    @if($sendToAll)
                        Send to All Dealers
                    @else
                        Send to Selected Dealers ({{ count($selectedDealerIds) }})
                    @endif
                </button>
                @if(!$sendToAll && count($selectedDealerIds) === 0)
                    <p class="text-sm text-red-600 self-center">Please select at least one dealer</p>
                @endif
            </div>
        </form>
    </div>
            @endif
        @endif
    @endauth

    <!-- Dealer/Admin Action: Send Quotation -->
    @auth
        @php
            $user = auth()->user();
            $canSubmitQuotation = ($user->isDealer() || ($user->isAdmin() && $user->entity_id)) && $request->status === 'sent_to_dealers';
        @endphp
        
        @if($request->status === 'sent_to_dealers' && $request->status !== 'completed' && !$request->accepted_quotation_id)
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Submit Quotation</h2>
                <p class="text-sm text-gray-600 mt-1">Submit a quotation for this exchange request.</p>
                @if(!$canSubmitQuotation)
                    <p class="text-sm text-yellow-600 mt-2 bg-yellow-50 border border-yellow-200 rounded-lg p-3">⚠️ You need to be associated with a dealer entity to submit quotations.</p>
                @endif
            </div>
            @if($canSubmitQuotation)
            <form wire:submit.prevent="submitQuotation" class="space-y-6">
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

                @if($availableVehicles->count() > 0)
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
                @endif

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
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium"
                    >
                        Send Quotation via Email
                    </button>
                </div>
            </form>
            @endif
        </div>
        @endif
    @endauth

    <!-- Quotations -->
    @if($request->quotations->count() > 0)
    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quotations ({{ $request->quotations->count() }})</h2>
        <div class="space-y-4">
            @foreach($request->quotations as $quotation)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-gray-900">{{ $quotation->entity->name }}</p>
                        <p class="text-sm text-gray-600">Valuation: {{ number_format($quotation->current_vehicle_valuation, 2) }} TZS</p>
                        <p class="text-sm text-gray-600">Price: {{ number_format($quotation->desired_vehicle_price, 2) }} TZS</p>
                        <p class="text-sm font-semibold text-green-600">Difference: {{ number_format($quotation->price_difference, 2) }} TZS</p>
                    </div>
<div>
                        @if($quotation->status === 'sent')
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs">Sent</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs">Pending</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
