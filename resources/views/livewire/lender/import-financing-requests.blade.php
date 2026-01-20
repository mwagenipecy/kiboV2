<div class="p-6">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Import Financing Requests</h1>
        <p class="text-gray-500 mt-1">Browse and submit offers for import financing requests</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by reference, vehicle make or model..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
            </div>
            <div class="w-full md:w-48">
                <select 
                    wire:model.live="typeFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                    <option value="">All Types</option>
                    <option value="buy_car">Buy Car</option>
                    <option value="tax_transport">Tax & Transport</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Requests Grid -->
    @if($requests->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No financing requests available</h3>
            <p class="text-gray-500">Check back later for new import financing opportunities.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($requests as $request)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">{{ $request->reference_number }}</span>
                            <h3 class="text-lg font-semibold text-gray-900 mt-1">
                                @if($request->vehicle_make || $request->vehicle_model)
                                    {{ $request->vehicle_make }} {{ $request->vehicle_model }}
                                @else
                                    Import Financing
                                @endif
                            </h3>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $request->request_type === 'buy_car' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $request->request_type === 'buy_car' ? 'Buy' : 'Tax/Transport' }}
                        </span>
                    </div>

                    <dl class="space-y-3 text-sm">
                        @if($request->vehicle_year)
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Year</dt>
                            <dd class="font-medium text-gray-900">{{ $request->vehicle_year }}</dd>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Amount Requested</dt>
                            <dd class="font-bold text-green-600">
                                {{ $request->request_type === 'buy_car' ? $request->vehicle_currency : 'TZS' }}
                                {{ number_format($request->financing_amount_requested ?? 0, 0) }}
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Loan Term</dt>
                            <dd class="font-medium text-gray-900">{{ $request->loan_term_months }} months</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Offers</dt>
                            <dd class="font-medium text-gray-900">{{ $request->offers_count }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <a 
                            href="{{ route('lender.import-financing.detail', $request->id) }}"
                            class="block w-full text-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors"
                        >
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($requests->hasPages())
        <div class="mt-6">
            {{ $requests->links() }}
        </div>
        @endif
    @endif
</div>

