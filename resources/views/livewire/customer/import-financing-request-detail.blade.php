<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-emerald-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('import-financing.requests') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $request->reference_number }}</h1>
                    <p class="text-gray-500">{{ $request->request_type_label }}</p>
                </div>
                <span class="px-3 py-1 text-sm font-medium rounded-full bg-{{ $request->status_color }}-100 text-{{ $request->status_color }}-700">
                    {{ $request->status_label }}
                </span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Request Details -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-900">Request Details</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                        <a href="{{ $request->car_link }}" target="_blank" class="text-emerald-600 hover:text-emerald-700 underline break-all">
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

                            <div>
                                <dt class="text-sm text-gray-500">Financing Requested</dt>
                                <dd class="mt-1 font-bold text-emerald-600 text-lg">
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
                                <dt class="text-sm text-gray-500">Your Notes</dt>
                                <dd class="mt-1 text-gray-900">{{ $request->customer_notes }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Lender Offers -->
                @if($request->offers->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-900">Financing Offers</h2>
                        <p class="text-sm text-gray-500 mt-1">Compare offers from our partner lenders</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($request->offers as $offer)
                        <div class="p-6 {{ $offer->status === 'accepted' ? 'bg-emerald-50' : '' }}">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="font-semibold text-gray-900">{{ $offer->entity->name ?? 'Lender' }}</span>
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-{{ $offer->status_color }}-100 text-{{ $offer->status_color }}-700">
                                            {{ $offer->status_label }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
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
                                            <p class="font-medium text-emerald-600">{{ number_format($offer->monthly_payment, 0) }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Term</span>
                                            <p class="font-medium text-gray-900">{{ $offer->loan_term_months }} months</p>
                                        </div>
                                    </div>
                                    @if($offer->valid_until)
                                    <p class="text-xs text-gray-400 mt-2">Valid until {{ $offer->valid_until->format('M d, Y') }}</p>
                                    @endif
                                </div>
                                @if($offer->status === 'pending' && !$request->accepted_offer_id)
                                <button 
                                    wire:click="openAcceptModal({{ $offer->id }})"
                                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors"
                                >
                                    Accept Offer
                                </button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @elseif(in_array($request->status, ['with_lenders', 'approved']))
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Awaiting Lender Offers</h3>
                    <p class="text-gray-500">Your request has been sent to our partner lenders. You'll be notified when offers are available.</p>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Timeline -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-900">Status Timeline</h2>
                    </div>
                    <div class="p-6">
                        <ol class="relative border-l border-gray-200 ml-3">
                            <li class="mb-6 ml-6">
                                <span class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-emerald-100 rounded-full ring-8 ring-white">
                                    <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <h3 class="font-medium text-gray-900">Request Submitted</h3>
                                <time class="text-xs text-gray-500">{{ $request->created_at->format('M d, Y H:i') }}</time>
                            </li>
                            @if($request->reviewed_at)
                            <li class="mb-6 ml-6">
                                <span class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-emerald-100 rounded-full ring-8 ring-white">
                                    <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <h3 class="font-medium text-gray-900">Reviewed by Admin</h3>
                                <time class="text-xs text-gray-500">{{ $request->reviewed_at->format('M d, Y H:i') }}</time>
                            </li>
                            @endif
                            @if(in_array($request->status, ['with_lenders', 'offer_received', 'accepted', 'completed']))
                            <li class="mb-6 ml-6">
                                <span class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-emerald-100 rounded-full ring-8 ring-white">
                                    <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <h3 class="font-medium text-gray-900">Sent to Lenders</h3>
                            </li>
                            @endif
                            @if($request->offers->count() > 0)
                            <li class="mb-6 ml-6">
                                <span class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-emerald-100 rounded-full ring-8 ring-white">
                                    <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <h3 class="font-medium text-gray-900">{{ $request->offers->count() }} Offer(s) Received</h3>
                            </li>
                            @endif
                            @if($request->accepted_offer_id)
                            <li class="ml-6">
                                <span class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-emerald-500 rounded-full ring-8 ring-white">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <h3 class="font-medium text-gray-900">Offer Accepted</h3>
                            </li>
                            @endif
                        </ol>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-900">Contact Information</h2>
                    </div>
                    <div class="p-6 space-y-4 text-sm">
                        <div>
                            <span class="text-gray-500">Name</span>
                            <p class="font-medium text-gray-900">{{ $request->customer_name }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Email</span>
                            <p class="font-medium text-gray-900">{{ $request->customer_email }}</p>
                        </div>
                        @if($request->customer_phone)
                        <div>
                            <span class="text-gray-500">Phone</span>
                            <p class="font-medium text-gray-900">{{ $request->customer_phone }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                @if(in_array($request->status, ['pending', 'under_review']))
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        <button 
                            wire:click="cancelRequest"
                            wire:confirm="Are you sure you want to cancel this request?"
                            class="w-full px-4 py-2.5 border border-red-300 text-red-600 hover:bg-red-50 font-medium rounded-xl transition-colors"
                        >
                            Cancel Request
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Accept Offer Modal -->
    @if($showAcceptModal && $selectedOfferId)
    @php
        $selectedOffer = $request->offers->find($selectedOfferId);
    @endphp
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeAcceptModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Accept Financing Offer</h3>
                            <p class="text-sm text-gray-500">from {{ $selectedOffer->entity->name ?? 'Lender' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50">
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-gray-500">Loan Amount</dt>
                            <dd class="font-semibold text-gray-900">{{ number_format($selectedOffer->offered_amount, 0) }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Interest Rate</dt>
                            <dd class="font-semibold text-gray-900">{{ $selectedOffer->interest_rate }}% p.a.</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Monthly Payment</dt>
                            <dd class="font-semibold text-emerald-600">{{ number_format($selectedOffer->monthly_payment, 0) }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Total Repayment</dt>
                            <dd class="font-semibold text-gray-900">{{ number_format($selectedOffer->total_repayment, 0) }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white px-6 py-4">
                    <p class="text-sm text-gray-500">
                        By accepting this offer, you agree to proceed with the financing terms. The lender will contact you to complete the process.
                    </p>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end">
                    <button 
                        wire:click="closeAcceptModal"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="acceptOffer"
                        class="px-4 py-2 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-colors"
                    >
                        Accept Offer
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

