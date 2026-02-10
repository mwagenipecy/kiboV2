<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Exchange Requests</h1>
        <p class="text-gray-600">View and manage your car exchange requests</p>
    </div>

    @if (session()->has('exchange_success'))
        <div class="mb-6 rounded-xl px-4 py-3 text-center" style="background-color: rgba(0, 152, 102, 0.1); color: #007a52;">
            {{ session('exchange_success') }}
        </div>
    @endif

    @if (session()->has('success'))
        <div class="mb-6 rounded-xl px-4 py-3 text-center bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 rounded-xl px-4 py-3 text-center bg-red-50 border border-red-200 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @forelse($requests as $request)
        <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Exchange Request #{{ $request->id }}</h2>
                    <p class="text-sm text-gray-500">Submitted: {{ $request->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    @if($request->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 font-medium text-sm">Pending Review</span>
                    @elseif($request->status === 'admin_approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 font-medium text-sm">Approved</span>
                    @elseif($request->status === 'sent_to_dealers')
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 font-medium text-sm">Sent to Dealers</span>
                    @elseif($request->status === 'completed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-800 font-medium text-sm">Completed</span>
                    @elseif($request->status === 'rejected')
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-800 font-medium text-sm">Rejected</span>
                    @endif
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-medium text-gray-700 mb-3">Current Vehicle</h3>
                    <p class="text-gray-900 font-semibold">{{ $request->current_vehicle_make }} {{ $request->current_vehicle_model }}</p>
                    <p class="text-sm text-gray-600 mt-1">Year: {{ $request->current_vehicle_year }}</p>
                    @if($request->current_vehicle_mileage)
                        <p class="text-sm text-gray-600">Mileage: {{ number_format($request->current_vehicle_mileage) }} km</p>
                    @endif
                    <p class="text-sm text-gray-600">Condition: {{ ucfirst($request->current_vehicle_condition) }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-medium text-gray-700 mb-3">Desired Vehicle</h3>
                    <p class="text-gray-900 font-semibold">{{ $request->desiredMake?->name ?? 'Any' }} {{ $request->desiredModel?->name ?? '' }}</p>
                    @if($request->desired_min_year || $request->desired_max_year)
                        <p class="text-sm text-gray-600 mt-1">Year: {{ $request->desired_min_year ?? 'Any' }} - {{ $request->desired_max_year ?? 'Any' }}</p>
                    @endif
                    @if($request->max_budget)
                        <p class="text-sm text-gray-600">Budget: {{ number_format($request->max_budget) }} TZS</p>
                    @endif
                </div>
            </div>

            @if($request->status === 'completed' && $request->accepted_quotation_id)
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm font-medium text-blue-900">✓ Exchange request completed. A quotation has been accepted and no further quotations can be submitted.</p>
                </div>
            @endif

            @if($request->quotations->count() > 0)
                <div class="mt-6">
                    <h3 class="font-medium text-gray-700 mb-4">
                        Quotations Received ({{ $request->quotations->count() }})
                        @if($request->status === 'completed')
                            <span class="text-sm text-gray-500 font-normal">- Request completed, no new quotations accepted</span>
                        @endif
                    </h3>
                    <div class="space-y-4">
                        @foreach($request->quotations as $quotation)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $quotation->entity->name }}</h4>
                                        <div class="mt-2 grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <p class="text-gray-600">Your Vehicle Valuation:</p>
                                                <p class="font-semibold text-gray-900">{{ number_format($quotation->current_vehicle_valuation, 2) }} {{ $quotation->currency }}</p>
                                            </div>
<div>
                                                <p class="text-gray-600">Desired Vehicle Price:</p>
                                                <p class="font-semibold text-gray-900">{{ number_format($quotation->desired_vehicle_price, 2) }} {{ $quotation->currency }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <p class="text-gray-600">Amount to Pay:</p>
                                            <p class="text-lg font-bold" style="color: #009866;">
                                                {{ number_format($quotation->price_difference, 2) }} {{ $quotation->currency }}
                                            </p>
                                        </div>
                                        @if($quotation->offeredVehicle)
                                            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                                <p class="text-sm font-medium text-gray-700">Vehicle Offered:</p>
                                                <p class="text-sm text-gray-900">
                                                    {{ $quotation->offeredVehicle->make->name }} {{ $quotation->offeredVehicle->model->name }} 
                                                    ({{ $quotation->offeredVehicle->year }})
                                                </p>
                                            </div>
                                        @endif
                                        @if($quotation->message)
                                            <div class="mt-3">
                                                <p class="text-sm text-gray-600 italic">"{{ $quotation->message }}"</p>
                                            </div>
                                        @endif
                                        @if($quotation->quotation_documents && count($quotation->quotation_documents) > 0)
                                            <div class="mt-3">
                                                <p class="text-sm font-medium text-gray-700 mb-1">Documents:</p>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($quotation->quotation_documents as $doc)
                                                        <a href="{{ asset('storage/' . $doc) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 underline">
                                                            View Document {{ $loop->iteration }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-2">Sent: {{ $quotation->sent_at?->format('M d, Y H:i') ?? 'Pending' }}</p>
                                        @if($quotation->accepted_at)
                                            <p class="text-xs text-green-600 mt-1 font-medium">✓ Confirmed on: {{ $quotation->accepted_at->format('M d, Y H:i') }}</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-col items-end gap-2">
                                        @if($quotation->status === 'sent')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs">Sent</span>
                                        @elseif($quotation->status === 'accepted')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold">✓ Confirmed</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs">Pending</span>
                                        @endif
                                        
                                        @if($request->status !== 'completed' && in_array($quotation->status, ['sent', 'pending']) && !$request->accepted_quotation_id)
                                            <button 
                                                wire:click="openConfirmModal({{ $quotation->id }})"
                                                class="px-6 py-3 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-md hover:shadow-lg"
                                            >
                                                ✓ Confirm Quotation
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="mt-6 text-center py-8 bg-gray-50 rounded-lg">
                    <p class="text-gray-600">No quotations received yet.</p>
                    <p class="text-sm text-gray-500 mt-1">
                        @if($request->status === 'sent_to_dealers')
                            Dealers are reviewing your request. You will receive quotations soon.
                        @elseif($request->status === 'pending')
                            Your request is pending admin approval.
                        @elseif($request->status === 'admin_approved')
                            Your request has been approved and will be sent to dealers soon.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white border border-gray-200 rounded-xl p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Exchange Requests</h3>
            <p class="text-gray-600 mb-6">You haven't submitted any car exchange requests yet.</p>
            <a href="{{ route('car-exchange.index') }}" class="inline-block px-6 py-3 text-white font-semibold rounded-lg transition-colors" style="background-color: #009866;">
                Submit Exchange Request
            </a>
        </div>
    @endforelse

    <!-- Confirmation Modal -->
    @if($showConfirmModal && $quotationDetails)
    <div 
        x-data="{ show: true }"
        x-show="show"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[9999] overflow-y-auto"
    >
        <!-- Background overlay -->
        <div 
            @click="$wire.closeConfirmModal()"
            class="fixed inset-0 bg-black/50 bg-opacity-50 transition-opacity"
        ></div>

        <!-- Modal panel -->
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div 
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            >
                <!-- Modal header -->
                <div class="bg-white px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Confirm Quotation</h3>
                        <button 
                            wire:click="closeConfirmModal"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal body -->
                <div class="bg-white px-6 py-4">
                    <p class="text-sm text-gray-600 mb-4">
                        Are you sure you want to confirm this quotation? Once confirmed, this exchange request will be completed and no other quotations can be submitted.
                    </p>

                    @if($quotationDetails)
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <h4 class="font-semibold text-gray-900 mb-3">{{ $quotationDetails->entity->name }}</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Your Vehicle Valuation:</span>
                                <span class="font-semibold text-gray-900">{{ number_format($quotationDetails->current_vehicle_valuation, 2) }} {{ $quotationDetails->currency }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Desired Vehicle Price:</span>
                                <span class="font-semibold text-gray-900">{{ number_format($quotationDetails->desired_vehicle_price, 2) }} {{ $quotationDetails->currency }}</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-gray-200">
                                <span class="text-gray-700 font-medium">Amount to Pay:</span>
                                <span class="font-bold text-lg" style="color: #009866;">{{ number_format($quotationDetails->price_difference, 2) }} {{ $quotationDetails->currency }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Modal footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button 
                        wire:click="closeConfirmModal"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="acceptQuotation"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition-colors shadow-md hover:shadow-lg"
                    >
                        ✓ Confirm Quotation
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
