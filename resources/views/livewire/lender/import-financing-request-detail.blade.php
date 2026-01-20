<div class="p-6">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('lender.import-financing.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900">{{ $request->reference_number }}</h1>
            <p class="text-gray-500">{{ $request->request_type_label }}</p>
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

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Request Details -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-semibold text-gray-900">Financing Request Details</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                        @else
                            <div>
                                <dt class="text-sm text-gray-500">Tax/Duty Amount</dt>
                                <dd class="mt-1 font-medium text-gray-900">TZS {{ number_format($request->tax_amount ?? 0, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Transport Cost</dt>
                                <dd class="mt-1 font-medium text-gray-900">TZS {{ number_format($request->transport_cost ?? 0, 2) }}</dd>
                            </div>
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
                    </dl>
                </div>
            </div>

            <!-- My Offer or Offer Form -->
            @if($myOffer)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                        <h2 class="font-semibold text-green-800">Your Submitted Offer</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <dt class="text-sm text-gray-500">Offered Amount</dt>
                                <dd class="mt-1 font-bold text-gray-900">{{ number_format($myOffer->offered_amount, 0) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Interest Rate</dt>
                                <dd class="mt-1 font-medium text-gray-900">{{ $myOffer->interest_rate }}% p.a.</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Monthly Payment</dt>
                                <dd class="mt-1 font-medium text-green-600">{{ number_format($myOffer->monthly_payment, 0) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Loan Term</dt>
                                <dd class="mt-1 font-medium text-gray-900">{{ $myOffer->loan_term_months }} months</dd>
                            </div>
                        </dl>
                        <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-{{ $myOffer->status_color }}-100 text-{{ $myOffer->status_color }}-700">
                                {{ $myOffer->status_label }}
                            </span>
                            @if($myOffer->valid_until)
                            <span class="text-sm text-gray-500">Valid until {{ $myOffer->valid_until->format('M d, Y') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif(in_array($request->status, ['with_lenders', 'offer_received']))
                @if($showOfferForm)
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="font-semibold text-gray-900">Submit Your Financing Offer</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Loan Amount *</label>
                                    <input 
                                        type="number" 
                                        wire:model.live="offeredAmount"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        step="1000"
                                    >
                                    @error('offeredAmount')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Annual Interest Rate (%) *</label>
                                    <input 
                                        type="number" 
                                        wire:model.live="interestRate"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        step="0.1"
                                        min="0"
                                        max="100"
                                    >
                                    @error('interestRate')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Loan Term (Months) *</label>
                                    <select 
                                        wire:model.live="loanTermMonths"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    >
                                        <option value="6">6 months</option>
                                        <option value="12">12 months</option>
                                        <option value="24">24 months</option>
                                        <option value="36">36 months</option>
                                        <option value="48">48 months</option>
                                        <option value="60">60 months</option>
                                        <option value="72">72 months</option>
                                        <option value="84">84 months</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Processing Fee</label>
                                    <input 
                                        type="number" 
                                        wire:model="processingFee"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        step="1000"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Offer Valid Until</label>
                                    <input 
                                        type="date" 
                                        wire:model="validUntil"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        min="{{ now()->addDay()->format('Y-m-d') }}"
                                    >
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                                    <textarea 
                                        wire:model="termsConditions"
                                        rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"
                                        placeholder="Enter any specific terms and conditions..."
                                    ></textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                    <textarea 
                                        wire:model="notes"
                                        rows="2"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"
                                        placeholder="Any additional notes..."
                                    ></textarea>
                                </div>
                            </div>

                            <!-- Calculated Summary -->
                            <div class="mt-6 p-4 bg-green-50 rounded-xl border border-green-200">
                                <h3 class="font-semibold text-green-900 mb-3">Offer Summary</h3>
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="text-green-700">Monthly Payment</span>
                                        <p class="text-lg font-bold text-green-900">{{ number_format($monthlyPayment, 0) }}</p>
                                    </div>
                                    <div>
                                        <span class="text-green-700">Total Repayment</span>
                                        <p class="text-lg font-bold text-green-900">{{ number_format($totalRepayment, 0) }}</p>
                                    </div>
                                    <div>
                                        <span class="text-green-700">Total Interest</span>
                                        <p class="text-lg font-bold text-green-900">{{ number_format($totalRepayment - ($offeredAmount ?? 0), 0) }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex gap-4">
                                <button 
                                    wire:click="submitOffer"
                                    class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors"
                                >
                                    Submit Offer
                                </button>
                                <button 
                                    wire:click="toggleOfferForm"
                                    class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Ready to Make an Offer?</h3>
                        <p class="text-gray-500 mb-6">Submit a competitive financing offer for this import request.</p>
                        <button 
                            wire:click="toggleOfferForm"
                            class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors"
                        >
                            Create Financing Offer
                        </button>
                    </div>
                @endif
            @else
                <div class="bg-yellow-50 rounded-xl border border-yellow-200 p-6 text-center">
                    <p class="text-yellow-700">This request is no longer accepting new offers.</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Info -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-semibold text-gray-900">Quick Info</h2>
                </div>
                <div class="p-6 space-y-4 text-sm">
                    <div>
                        <span class="text-gray-500">Request Type</span>
                        <p class="font-medium text-gray-900">{{ $request->request_type_label }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Submitted</span>
                        <p class="font-medium text-gray-900">{{ $request->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Total Offers</span>
                        <p class="font-medium text-gray-900">{{ $request->offers->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Other Offers (anonymized) -->
            @if($request->offers->count() > 0)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-semibold text-gray-900">Competing Offers</h2>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($request->offers as $index => $offer)
                    <div class="p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Offer #{{ $index + 1 }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $offer->interest_rate }}% p.a.</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

