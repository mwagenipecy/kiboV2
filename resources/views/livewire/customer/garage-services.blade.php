<div>
    <style>
        .kibo-bg { background-color: #009866 !important; }
        .kibo-bg:hover { background-color: #007a52 !important; }
    </style>
    <!-- Hero -->
    <section class="relative bg-white mb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div
                class="relative h-72 rounded-2xl overflow-hidden bg-center bg-cover flex items-center justify-center"
                style="background-image: url('{{ asset('image/garage.png') }}');"
            >
                <div class="absolute inset-0 bg-black/45"></div>
                <div class="relative text-center text-white px-4">
                    <p class="text-sm uppercase tracking-[0.3em] text-gray-200 mb-3">Garage Services</p>
                    <h1 class="text-4xl md:text-5xl font-bold mb-3">Everything Your Car Needs</h1>
                    <p class="text-lg text-gray-100 max-w-3xl mx-auto">
                        Browse services offered by our trusted garages across Tanzania.
                    </p>
                    <div class="mt-6 flex flex-col sm:flex-row justify-center gap-3">
                        <a href="{{ route('garage.index') }}" class="px-6 py-3 text-white font-semibold rounded-lg transition-colors" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                            Find a Garage
                        </a>
                        <a href="#services" class="px-6 py-3 bg-white/90 text-gray-900 font-semibold rounded-lg hover:bg-white transition-colors">
                            Explore Services
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section id="services" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Available Services</h2>
                <p class="text-gray-600 mt-1">Aggregated from all active garages.</p>
            </div>
            <a href="{{ route('garage.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg transition-colors" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                Find a Garage
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>

        @if(empty($serviceGroups))
            <div class="bg-white rounded-xl shadow-sm p-10 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0h-.01M15 17a2 2 0 104 0m-4 0h-.01"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Services coming soon</h3>
                <p class="text-gray-600">We’re gathering services from our partner garages. Please check back shortly.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($serviceGroups as $service => $meta)
                    @php
                        $primaryGarage = $meta['garages'][0] ?? null;
                        $primaryPhoneLink = $primaryGarage && !empty($primaryGarage['phone'])
                            ? preg_replace('/\s+/', '', $primaryGarage['phone'])
                            : null;
                        $primaryEmailLink = $primaryGarage['email'] ?? null;
                        $emailSubject = rawurlencode('Garage Service Inquiry - ' . $this->formatService($service));
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col gap-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">{{ $this->formatService($service) }}</h3>
                                <p class="text-sm text-gray-500">Offered by {{ $meta['count'] }} garage{{ $meta['count'] === 1 ? '' : 's' }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">Service</span>
                        </div>

                        @if(!empty($meta['garages']))
                            <div>
                                <p class="text-xs text-gray-500 mb-2">Available at:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(array_slice($meta['garages'], 0, 3) as $garage)
                                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                                            {{ $garage['name'] ?? 'Garage' }}
                                        </span>
                                    @endforeach
                                    @if($meta['count'] > 3)
                                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                                            +{{ $meta['count'] - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="flex gap-3 mt-auto">
                            <button
                                type="button"
                                class="flex-1 px-4 py-2 text-center border border-gray-300 text-gray-800 rounded-lg hover:bg-gray-50 transition-colors"
                                wire:click="openModal('{{ $service }}')"
                            >
                                View garages
                            </button>

                            @if($primaryGarage)
                                <button
                                    type="button"
                                    class="flex-1 px-4 py-2 text-center text-white rounded-lg transition-colors"
                                    style="background-color: #009866;"
                                    onmouseover="this.style.backgroundColor='#007a52'"
                                    onmouseout="this.style.backgroundColor='#009866'"
                                    wire:click="$dispatch('openBookingModal', { services: ['{{ $service }}'], serviceType: '{{ $service }}', agentId: {{ $primaryGarage['id'] ?? 'null' }}, agentName: '{{ $primaryGarage['name'] ?? 'Garage' }}' })"
                                >
                                    Schedule Service
                                </button>
                            @else
                                <span class="flex-1 px-4 py-2 text-center bg-gray-200 text-gray-500 rounded-lg cursor-not-allowed">
                                    No garage
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- Modal -->
    @if($showModal)
        <div
            class="fixed inset-0 z-50 flex items-start justify-end bg-black/40"
            wire:key="garage-service-modal"
        >
            <div class="bg-white w-full max-w-md h-full overflow-y-auto shadow-xl animate-slide-in">
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Service</p>
                        <h3 class="text-xl font-bold text-gray-900">{{ $modalService ?? '' }}</h3>
                    </div>
                    <button class="text-gray-500 hover:text-gray-800" wire:click="closeModal">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                @if(!empty($modalGarages))
                    <div class="p-6 space-y-4">
                        @foreach($modalGarages as $garage)
                            <div class="border rounded-lg p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $garage['name'] ?? 'Garage' }}</h4>
                                        <p class="text-sm text-gray-600">{{ $garage['address'] ?? 'Address not provided' }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">Garage</span>
                                </div>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <button
                                        type="button"
                                        class="px-3 py-2 text-sm text-white rounded-md transition-colors"
                                        style="background-color: #009866;"
                                        onmouseover="this.style.backgroundColor='#007a52'"
                                        onmouseout="this.style.backgroundColor='#009866'"
                                        wire:click="$dispatch('openBookingModal', { services: ['{{ $modalServiceKey }}'], serviceType: '{{ $modalServiceKey }}', agentId: {{ $garage['id'] ?? 'null' }}, agentName: '{{ $garage['name'] ?? 'Garage' }}' })"
                                    >
                                        Schedule Service
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6">
                        <p class="text-gray-600">No garages found for this service.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Order Tracking Section -->
    @auth
    <section id="tracking" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 mt-12">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Track Your Orders</h2>
                    <p class="text-gray-600 mt-1">View and track your garage service bookings</p>
                </div>
                <button
                    type="button"
                    wire:click="toggleTracking"
                    class="px-4 py-2 text-white rounded-lg transition-colors"
                    style="background-color: #009866;"
                    onmouseover="this.style.backgroundColor='#007a52'"
                    onmouseout="this.style.backgroundColor='#009866'"
                >
                    {{ $showTracking ? 'Hide' : 'Show' }} Orders
                </button>
            </div>

            @if($showTracking)
                @if(!empty($userOrders))
                    <div class="space-y-4">
                        @foreach($userOrders as $order)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ ucwords(str_replace('_', ' ', $order['service_type'])) }}</h3>
                                    <p class="text-sm text-gray-600">Order #{{ $order['order_number'] }}</p>
                                    @if(isset($order['agent']['company_name']))
                                    <p class="text-sm text-gray-600">Garage: {{ $order['agent']['company_name'] ?? $order['agent']['name'] ?? 'N/A' }}</p>
                                    @endif
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    @if($order['status'] === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order['status'] === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($order['status'] === 'rejected') bg-red-100 text-red-800
                                    @elseif($order['status'] === 'in_progress') bg-purple-100 text-purple-800
                                    @elseif($order['status'] === 'completed') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order['status'])) }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-600">Booking Type</p>
                                    <p class="font-semibold text-gray-900">{{ ucfirst($order['booking_type']) }}</p>
                                </div>
                                @if($order['booking_type'] === 'scheduled' && $order['scheduled_date'])
                                <div>
                                    <p class="text-gray-600">Scheduled Date</p>
                                    <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($order['scheduled_date'])->format('M d, Y') }}</p>
                                </div>
                                @if($order['scheduled_time'])
                                <div>
                                    <p class="text-gray-600">Scheduled Time</p>
                                    <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($order['scheduled_time'])->format('h:i A') }}</p>
                                </div>
                                @endif
                                @endif
                                @if($order['quoted_price'])
                                <div>
                                    <p class="text-gray-600">Quoted Price</p>
                                    <p class="font-semibold text-gray-900">{{ $order['currency'] }} {{ number_format($order['quoted_price'], 2) }}</p>
                                </div>
                                @endif
                            </div>
                            @if($order['rejection_reason'])
                            <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-800"><span class="font-semibold">Rejection Reason:</span> {{ $order['rejection_reason'] }}</p>
                            </div>
                            @endif
                            @if($order['quotation_notes'])
                            <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm text-blue-800"><span class="font-semibold">Quotation Notes:</span> {{ $order['quotation_notes'] }}</p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Orders Yet</h3>
                        <p class="text-gray-600">You haven't booked any services yet. Schedule a service above to get started.</p>
                    </div>
                @endif
            @endif
        </div>
    </section>
    @endauth

    <!-- Booking Modal Component -->
    @livewire('customer.garage-service-booking')

</div>

