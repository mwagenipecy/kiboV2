<div class="p-6">
    @if($showSuccessMessage)
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ $successMessage }}</span>
        </div>
        <button wire:click="closeSuccessMessage" class="text-green-600 hover:text-green-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    @endif

    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.agiza-import') }}" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">Import Request #{{ $request->request_number }}</h1>
                </div>
                <p class="mt-1 text-sm text-gray-600">Submitted {{ $request->created_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
            <span class="px-3 py-1.5 text-sm font-semibold rounded-full {{ $request->statusColor }}">
                {{ $request->statusLabel }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $request->customer_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $request->customer_email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $request->customer_phone ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Request Type</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $request->request_type === 'with_link' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $request->request_type === 'with_link' ? 'With Link' : 'Already Contacted Dealer' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Vehicle / listing -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Vehicle / listing</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($request->vehicle_link)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Listing URL</dt>
                        <dd class="mt-1">
                            <a href="{{ $request->vehicle_link }}" target="_blank" rel="noopener noreferrer" class="text-sm text-green-600 hover:text-green-700 underline break-all">
                                {{ $request->vehicle_link }}
                            </a>
                        </dd>
                    </div>
                    @endif
                    @if(filled($request->vehicle_make))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Make</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $request->vehicle_make }}</dd>
                    </div>
                    @endif
                    @if(filled($request->vehicle_model))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Model</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $request->vehicle_model }}</dd>
                    </div>
                    @endif
                    @if($request->vehicle_year)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Year</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $request->vehicle_year }}</dd>
                    </div>
                    @endif
                    @if(filled($request->vehicle_condition))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Condition</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($request->vehicle_condition) }}</dd>
                    </div>
                    @endif
                    @if(filled($request->source_country))
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Source Country</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $request->source_country }}</dd>
                    </div>
                    @endif
                    @if($request->estimated_price)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Estimated Price</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ number_format($request->estimated_price, 2) }} {{ $request->price_currency }}</dd>
                    </div>
                    @endif
                </dl>

                @if($request->dealer_contact_info)
                <div class="mt-4">
                    <dt class="text-sm font-medium text-gray-500">Dealer Contact Information</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $request->dealer_contact_info }}</dd>
                </div>
                @endif
            </div>

            <!-- Additional Information -->
            @if($request->special_requirements || $request->customer_notes)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h2>
                
                @if($request->special_requirements)
                <div class="mb-4">
                    <dt class="text-sm font-medium text-gray-500">Special Requirements</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $request->special_requirements }}</dd>
                </div>
                @endif

                @if($request->customer_notes)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Customer Notes</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $request->customer_notes }}</dd>
                </div>
                @endif
            </div>
            @endif

            <!-- Documents & Images -->
            @if($request->documents || $request->vehicle_images)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Documents & Images</h2>
                
                @if($request->vehicle_images)
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Vehicle Photos</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($request->vehicle_images as $image)
                        <a href="{{ asset('storage/' . $image) }}" target="_blank" class="block">
                            <img src="{{ asset('storage/' . $image) }}" alt="Vehicle" class="w-full h-32 object-cover rounded-lg border border-gray-200 hover:opacity-75 transition">
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($request->documents)
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Documents</h3>
                    <ul class="space-y-2">
                        @foreach($request->documents as $doc)
                        <li>
                            <a href="{{ asset('storage/' . $doc) }}" target="_blank" class="text-sm text-green-600 hover:text-green-700 underline">
                                {{ basename($doc) }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Update -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h2>
                <select wire:model="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent mb-3">
                    <option value="pending">Pending</option>
                    <option value="under_review">Under Review</option>
                    <option value="quote_provided">Quote Provided</option>
                    <option value="accepted">Accepted</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <button wire:click="updateStatus" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm font-medium">Update Status</button>
            </div>

            <!-- Assignment -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Assign Agent</h2>
                <select wire:model="assignedTo" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent mb-3">
                    <option value="">Select Agent</option>
                    @foreach($agents as $agent)
                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                    @endforeach
                </select>
                <button wire:click="assignAgent" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">Assign</button>
            </div>

            <!-- Provide Quote -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Provide Quote</h2>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Import Cost</label>
                        <input type="number" wire:model="quotedImportCost" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Cost</label>
                        <input type="number" wire:model="quotedTotalCost" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                        <select wire:model="quoteCurrency" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <option value="GBP">GBP</option>
                            <option value="TZS">TZS</option>
                        </select>
                    </div>
                    <button wire:click="provideQuote" class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition text-sm font-medium">Provide Quote</button>
                </div>
            </div>

            <!-- Admin Notes -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Admin Notes</h2>
                <textarea wire:model="adminNotes" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" rows="4" placeholder="Add internal notes..."></textarea>
                <button wire:click="saveNotes" class="mt-3 w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm font-medium">Save Notes</button>
            </div>

            <!-- Quote Information (if exists) -->
            @if($request->quoted_import_cost || $request->quoted_total_cost)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Current Quote</h2>
                <dl class="space-y-3">
                    @if($request->quoted_import_cost)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Import Cost</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ number_format($request->quoted_import_cost, 2) }} {{ $request->quote_currency }}</dd>
                    </div>
                    @endif
                    @if($request->quoted_total_cost)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Cost</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ number_format($request->quoted_total_cost, 2) }} {{ $request->quote_currency }}</dd>
                    </div>
                    @endif
                    @if($request->quoted_at)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Quoted On</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $request->quoted_at->format('M d, Y') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            @endif

        </div>
    </div>
</div>
