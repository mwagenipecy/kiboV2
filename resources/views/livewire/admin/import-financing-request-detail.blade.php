<div class="p-6">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.import-financing') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900">{{ $request->reference_number }}</h1>
            <p class="text-gray-500">{{ $request->request_type_label }} • {{ $request->customer_name }}</p>
        </div>
        <span class="px-3 py-1 text-sm font-medium rounded-full bg-{{ $request->status_color }}-100 text-{{ $request->status_color }}-700">
            {{ $request->status_label }}
        </span>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-semibold text-gray-900">Customer Information</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Name</dt>
                            <dd class="mt-1 font-medium text-gray-900">{{ $request->customer_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Email</dt>
                            <dd class="mt-1 font-medium text-gray-900">{{ $request->customer_email }}</dd>
                        </div>
                        @if($request->customer_phone)
                        <div>
                            <dt class="text-sm text-gray-500">Phone</dt>
                            <dd class="mt-1 font-medium text-gray-900">{{ $request->customer_phone }}</dd>
                        </div>
                        @endif
                        @if($request->user)
                        <div>
                            <dt class="text-sm text-gray-500">Registered User</dt>
                            <dd class="mt-1 font-medium text-green-600">Yes (ID: {{ $request->user->id }})</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Request Details -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-semibold text-gray-900">Request Details</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Request Type</dt>
                            <dd class="mt-1">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $request->request_type === 'buy_car' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $request->request_type_label }}
                                </span>
                            </dd>
                        </div>

                        @if($request->vehicle_make || $request->vehicle_model)
                        <div>
                            <dt class="text-sm text-gray-500">Vehicle</dt>
                            <dd class="mt-1 font-medium text-gray-900">
                                {{ $request->vehicle_make }} {{ $request->vehicle_model }}
                                @if($request->vehicle_year) ({{ $request->vehicle_year }}) @endif
                            </dd>
                        </div>
                        @endif

                        @if($request->vehicle_condition)
                        <div>
                            <dt class="text-sm text-gray-500">Condition</dt>
                            <dd class="mt-1 font-medium text-gray-900 capitalize">{{ $request->vehicle_condition }}</dd>
                        </div>
                        @endif

                        @if($request->request_type === 'buy_car')
                            @if($request->vehicle_price)
                            <div>
                                <dt class="text-sm text-gray-500">Vehicle Price</dt>
                                <dd class="mt-1 font-medium text-gray-900">{{ $request->vehicle_currency }} {{ number_format($request->vehicle_price, 2) }}</dd>
                            </div>
                            @endif

                            @if($request->vehicle_location)
                            <div>
                                <dt class="text-sm text-gray-500">Vehicle Location</dt>
                                <dd class="mt-1 font-medium text-gray-900">{{ $request->vehicle_location }}</dd>
                            </div>
                            @endif

                            @if($request->car_link)
                            <div class="md:col-span-2">
                                <dt class="text-sm text-gray-500">Car Listing Link</dt>
                                <dd class="mt-1">
                                    <a href="{{ $request->car_link }}" target="_blank" class="text-green-600 hover:text-green-700 underline break-all">
                                        {{ $request->car_link }}
                                    </a>
                                </dd>
                            </div>
                            @endif
                        @else
                            <div>
                                <dt class="text-sm text-gray-500">Tax/Duty Amount</dt>
                                <dd class="mt-1 font-medium text-gray-900">TZS {{ number_format($request->tax_amount ?? 0, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Transport Cost</dt>
                                <dd class="mt-1 font-medium text-gray-900">TZS {{ number_format($request->transport_cost ?? 0, 2) }}</dd>
                            </div>
                            @if($request->total_clearing_cost)
                            <div>
                                <dt class="text-sm text-gray-500">Other Clearing Costs</dt>
                                <dd class="mt-1 font-medium text-gray-900">TZS {{ number_format($request->total_clearing_cost, 2) }}</dd>
                            </div>
                            @endif
                        @endif

                        <div class="md:col-span-2 pt-4 border-t border-gray-200">
                            <dt class="text-sm text-gray-500">Financing Amount Requested</dt>
                            <dd class="mt-1 text-xl font-bold text-green-600">
                                {{ $request->request_type === 'buy_car' ? $request->vehicle_currency : 'TZS' }}
                                {{ number_format($request->financing_amount_requested ?? 0, 2) }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm text-gray-500">Preferred Loan Term</dt>
                            <dd class="mt-1 font-medium text-gray-900">{{ $request->loan_term_months }} months</dd>
                        </div>

                        @if($request->down_payment)
                        <div>
                            <dt class="text-sm text-gray-500">Down Payment</dt>
                            <dd class="mt-1 font-medium text-gray-900">
                                {{ $request->request_type === 'buy_car' ? $request->vehicle_currency : 'TZS' }}
                                {{ number_format($request->down_payment, 2) }}
                            </dd>
                        </div>
                        @endif

                        @if($request->customer_notes)
                        <div class="md:col-span-2">
                            <dt class="text-sm text-gray-500">Customer Notes</dt>
                            <dd class="mt-1 text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $request->customer_notes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Lender Offers -->
            @if($request->offers->count() > 0)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-semibold text-gray-900">Lender Offers ({{ $request->offers->count() }})</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($request->offers as $offer)
                    <div class="p-6 {{ $offer->status === 'accepted' ? 'bg-green-50' : '' }}">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="font-semibold text-gray-900">{{ $offer->entity->name ?? 'Lender' }}</span>
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-{{ $offer->status_color }}-100 text-{{ $offer->status_color }}-700">
                                        {{ $offer->status_label }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Amount</span>
                                        <p class="font-medium text-gray-900">{{ number_format($offer->offered_amount, 0) }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Interest Rate</span>
                                        <p class="font-medium text-gray-900">{{ $offer->interest_rate }}% p.a.</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Monthly Payment</span>
                                        <p class="font-medium text-green-600">{{ number_format($offer->monthly_payment, 0) }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Term</span>
                                        <p class="font-medium text-gray-900">{{ $offer->loan_term_months }} months</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Total Repayment</span>
                                        <p class="font-medium text-gray-900">{{ number_format($offer->total_repayment, 0) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Admin Actions -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-semibold text-gray-900">Admin Actions</h2>
                </div>
                <div class="p-6 space-y-4">
                    @if($request->status === 'pending')
                        <button 
                            wire:click="approve"
                            class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors"
                        >
                            Approve Request
                        </button>
                        <button 
                            wire:click="reject"
                            wire:confirm="Are you sure you want to reject this request?"
                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors"
                        >
                            Reject Request
                        </button>
                    @elseif($request->status === 'approved')
                        <button 
                            wire:click="openSendToLendersModal"
                            class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors"
                        >
                            Send to Lenders
                        </button>
                    @elseif(in_array($request->status, ['with_lenders', 'offer_received']))
                        <div class="text-center text-sm text-gray-500 py-4">
                            <svg class="w-8 h-8 mx-auto mb-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Awaiting lender responses
                        </div>
                    @elseif($request->status === 'accepted')
                        <div class="text-center text-sm text-green-600 py-4">
                            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Customer has accepted an offer
                        </div>
                    @endif
                </div>
            </div>

            <!-- Admin Notes -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-semibold text-gray-900">Admin Notes</h2>
                </div>
                <div class="p-6">
                    <textarea 
                        wire:model="adminNotes"
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none text-sm"
                        placeholder="Add internal notes..."
                    ></textarea>
                    <button 
                        wire:click="saveNotes"
                        class="mt-3 w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors text-sm"
                    >
                        Save Notes
                    </button>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-semibold text-gray-900">Timeline</h2>
                </div>
                <div class="p-6">
                    <ol class="relative border-l border-gray-200 ml-3 space-y-6">
                        <li class="ml-6">
                            <span class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-green-100 rounded-full ring-8 ring-white">
                                <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <h3 class="text-sm font-medium text-gray-900">Submitted</h3>
                            <time class="text-xs text-gray-500">{{ $request->created_at->format('M d, Y H:i') }}</time>
                        </li>
                        @if($request->reviewed_at)
                        <li class="ml-6">
                            <span class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-8 ring-white">
                                <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <h3 class="text-sm font-medium text-gray-900">Reviewed</h3>
                            <time class="text-xs text-gray-500">{{ $request->reviewed_at->format('M d, Y H:i') }}</time>
                            @if($request->reviewer)
                            <p class="text-xs text-gray-500">by {{ $request->reviewer->name }}</p>
                            @endif
                        </li>
                        @endif
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Send to Lenders Modal -->
    @if($showSendToLendersModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeSendToLendersModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 pt-6 pb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Send to Lenders</h3>
                    <p class="text-sm text-gray-500 mt-1">This will notify all active lenders about this financing request.</p>
                </div>
                
                <div class="px-6 py-4 bg-gray-50">
                    <p class="text-sm text-gray-600">
                        <strong>{{ $lenders->count() }}</strong> active lender(s) will be notified.
                    </p>
                    @if($lenders->count() > 0)
                    <ul class="mt-2 space-y-1">
                        @foreach($lenders->take(5) as $lender)
                        <li class="text-sm text-gray-600">• {{ $lender->name }}</li>
                        @endforeach
                        @if($lenders->count() > 5)
                        <li class="text-sm text-gray-500">... and {{ $lenders->count() - 5 }} more</li>
                        @endif
                    </ul>
                    @endif
                </div>

                <div class="bg-white px-6 py-4 flex gap-3 justify-end">
                    <button 
                        wire:click="closeSendToLendersModal"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="sendToLenders"
                        class="px-4 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors"
                    >
                        Send to All Lenders
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

