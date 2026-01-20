<div>
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
                        <a href="{{ route('garage.index') }}" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
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
            <a href="{{ route('garage.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
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

                            @if($primaryGarage && ($primaryGarage['phone'] ?? false))
                                <a
                                    href="tel:{{ $primaryPhoneLink }}"
                                    class="flex-1 px-4 py-2 text-center bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                                >
                                    Book service
                                </a>
                            @elseif($primaryGarage && $primaryEmailLink)
                                <a
                                    href="mailto:{{ $primaryEmailLink }}?subject={{ $emailSubject }}"
                                    class="flex-1 px-4 py-2 text-center bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                                >
                                    Book service
                                </a>
                            @else
                                <span class="flex-1 px-4 py-2 text-center bg-gray-200 text-gray-500 rounded-lg cursor-not-allowed">
                                    No contact
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
                            @if(!empty($garage['phone']))
                                <a href="tel:{{ preg_replace('/\\s+/', '', $garage['phone']) }}" class="px-3 py-2 text-sm bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                            Call
                                        </a>
                                    @endif
                                    @if(!empty($garage['email']))
                                <a href="mailto:{{ $garage['email'] }}?subject={{ rawurlencode('Garage Service Inquiry - ' . ($modalService ?? 'Service')) }}" class="px-3 py-2 text-sm border border-gray-300 text-gray-800 rounded-md hover:bg-gray-50 transition-colors">
                                            Email
                                        </a>
                                    @endif
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
</div>

