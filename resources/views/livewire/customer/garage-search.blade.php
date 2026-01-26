<div>
    <style>
        .kibo-text { color: #009866 !important; }
        .kibo-bg { background-color: #009866 !important; }
        .kibo-bg:hover { background-color: #007a52 !important; }
        .kibo-border { border-color: #009866 !important; }
        .kibo-bg-light { background-color: rgba(0, 152, 102, 0.1) !important; }
    </style>
    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Search --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input
                    type="text"
                    wire:model.live.debounce.500ms="search"
                    placeholder="Search garages or enter order number (e.g., GS-123456)..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                    style="--tw-ring-color: #009866;"
                >
            </div>

            {{-- Vehicle Make Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Make</label>
                <select
                    wire:model.live="selectedMake"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                    style="--tw-ring-color: #009866;"
                >
                    <option value="">All Makes</option>
                    @foreach($vehicleMakes as $make)
                        <option value="{{ $make->id }}">{{ $make->name }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        {{-- Location Controls removed per request --}}
    </div>

    {{-- Results --}}
    @if($garages->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($garages as $garage)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                    {{-- Garage Image/Icon --}}
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>

                    {{-- Garage Info --}}
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $garage->company_name ?? $garage->name }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ $garage->name }}</p>

                        {{-- Address --}}
                        @if($garage->address)
                            <div class="flex items-start gap-2 mb-3">
                                <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <p class="text-sm text-gray-700">{{ $garage->address }}</p>
                            </div>
                        @endif

                        {{-- Distance --}}
                        @if($garage->distance !== null)
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-5 h-5" style="color: #009866;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                <span class="text-sm font-medium" style="color: #009866;">{{ $garage->distance }} km away</span>
                            </div>
                        @endif

                        {{-- Vehicle Makes --}}
                        @if($garage->vehicle_makes && count($garage->vehicle_makes) > 0)
                            <div class="mb-3">
                                <p class="text-xs text-gray-500 mb-1">Specializes in:</p>
                                <div class="flex flex-wrap gap-1">
                                    @php
                                        $makes = \App\Models\VehicleMake::whereIn('id', $garage->vehicle_makes)->get();
                                    @endphp
                                    @foreach($makes->take(3) as $make)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">{{ $make->name }}</span>
                                    @endforeach
                                    @if($makes->count() > 3)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">+{{ $makes->count() - 3 }} more</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Services --}}
                        @if($garage->services && count($garage->services) > 0)
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 mb-1">Services:</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($garage->services, 0, 3) as $service)
                                        <span class="px-2 py-1 text-xs rounded-full capitalize" style="background-color: rgba(0, 152, 102, 0.1); color: #007a52;">{{ str_replace('_', ' ', $service) }}</span>
                                    @endforeach
                                    @if(count($garage->services) > 3)
                                        <span class="px-2 py-1 text-xs rounded-full" style="background-color: rgba(0, 152, 102, 0.1); color: #007a52;">+{{ count($garage->services) - 3 }} more</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Schedule Service (selection happens in modal) --}}
                        <div class="mt-4">
                            <button
                                type="button"
                                class="w-full px-4 py-2 text-white font-semibold rounded-lg transition-colors"
                                style="background-color: #009866;"
                                onmouseover="this.style.backgroundColor='#007a52'"
                                onmouseout="this.style.backgroundColor='#009866'"
                                wire:click="openBookingModalForGarage({{ $garage->id }}, '{{ addslashes($garage->company_name ?? $garage->name) }}', @js($garage->services ?? []))"
                            >
                                Schedule Service
                            </button>
                            @if(!$garage->services || count($garage->services) === 0)
                                <p class="text-xs text-gray-500 mt-2">No services listed — you can still describe what you need in the form.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $garages->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No garages found</h3>
            <p class="text-gray-600">Try adjusting your search criteria or filters</p>
        </div>
    @endif

    {{-- Location Modal --}}
    @if($showLocationModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click="showLocationModal = false">
            <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4" wire:click.stop>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Get Your Location</h3>
                <p class="text-gray-600 mb-4">We need your location to show garages near you. Please allow location access in your browser.</p>
                <div class="flex gap-3">
                    <button
                        wire:click="showLocationModal = false"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- JavaScript for Geolocation --}}
    <script>
        document.addEventListener('livewire:init', () => {
            const isLocalhost = ['localhost', '127.0.0.1'].includes(window.location.hostname);
            const isSecure = window.isSecureContext || isLocalhost;

            const getErrorMessage = (error) => {
                if (!isSecure) {
                    return 'Location is blocked on non-HTTPS pages. Use HTTPS or run on localhost (127.0.0.1).';
                }
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        return 'Location permission denied. Please allow location access in your browser.';
                    case error.POSITION_UNAVAILABLE:
                        return 'Location information is unavailable. Try again in an open area or check network.';
                    case error.TIMEOUT:
                        return 'Location request timed out. Please try again.';
                    default:
                        return 'Unable to get your location. Please enable location access in your browser settings.';
                }
            };

            Livewire.on('request-location', () => {
                if (!navigator.geolocation) {
                    alert('Geolocation is not supported by your browser.');
                    @this.showLocationModal = false;
                    return;
                }

                if (!isSecure) {
                    alert('Location is blocked on non-HTTPS pages. Use HTTPS or run on localhost (127.0.0.1).');
                    @this.showLocationModal = false;
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        @this.setLocation(
                            position.coords.latitude,
                            position.coords.longitude
                        );
                    },
                    (error) => {
                        alert(getErrorMessage(error));
                        @this.showLocationModal = false;
                    },
                    { enableHighAccuracy: true, timeout: 12000, maximumAge: 5000 }
                );
            });
        });
    </script>

    {{-- Order Details Modal --}}
    @if($showOrderModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="closeOrderModal"></div>
            
            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full relative z-[9999]">
                @if($orderNotFound)
                    <!-- Order Not Found -->
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Order Not Found
                            </h3>
                            <button type="button" wire:click="closeOrderModal" class="text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-600 mb-2">No order found with number: <strong>{{ $search }}</strong></p>
                            <p class="text-sm text-gray-500">Please check the order number and try again.</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="closeOrderModal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                @elseif($selectedOrder)
                    <!-- Order Details -->
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Order Details
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">Order #{{ $selectedOrder->order_number }}</p>
                            </div>
                            <button type="button" wire:click="closeOrderModal" class="text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-6">
                            <!-- Status Badge -->
                            <div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($selectedOrder->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($selectedOrder->status === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($selectedOrder->status === 'quoted') bg-purple-100 text-purple-800
                                    @elseif($selectedOrder->status === 'completed') bg-green-100 text-green-800
                                    @elseif($selectedOrder->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($selectedOrder->status) }}
                                </span>
                            </div>

                            <!-- Customer & Garage Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-1">Customer</p>
                                    <p class="text-sm text-gray-900">{{ $selectedOrder->customer_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $selectedOrder->customer_email }}</p>
                                    <p class="text-sm text-gray-600">{{ $selectedOrder->customer_phone }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-1">Garage</p>
                                    <p class="text-sm text-gray-900">{{ $selectedOrder->agent->company_name ?? $selectedOrder->agent->name ?? 'N/A' }}</p>
                                    @if($selectedOrder->agent->phone_number)
                                    <p class="text-sm text-gray-600">{{ $selectedOrder->agent->phone_number }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Service Info -->
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-1">Service Type</p>
                                <p class="text-sm text-gray-900">{{ ucwords(str_replace('_', ' ', $selectedOrder->service_type)) }}</p>
                            </div>

                            <!-- Booking Type -->
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-1">Booking Type</p>
                                <p class="text-sm text-gray-900">{{ ucfirst($selectedOrder->booking_type) }}</p>
                                @if($selectedOrder->booking_type === 'scheduled' && $selectedOrder->scheduled_date)
                                <p class="text-sm text-gray-600 mt-1">
                                    Scheduled for {{ \Carbon\Carbon::parse($selectedOrder->scheduled_date)->format('M d, Y') }}
                                    @if($selectedOrder->scheduled_time)
                                    at {{ \Carbon\Carbon::parse($selectedOrder->scheduled_time)->format('h:i A') }}
                                    @endif
                                </p>
                                @endif
                            </div>

                            <!-- Vehicle Information -->
                            @if($selectedOrder->vehicle_make || $selectedOrder->vehicle_model)
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-1">Vehicle Information</p>
                                <p class="text-sm text-gray-900">
                                    @if($selectedOrder->vehicle_year) {{ $selectedOrder->vehicle_year }} @endif
                                    {{ $selectedOrder->vehicle_make }} {{ $selectedOrder->vehicle_model }}
                                    @if($selectedOrder->vehicle_registration)
                                    ({{ $selectedOrder->vehicle_registration }})
                                    @endif
                                </p>
                            </div>
                            @endif

                            <!-- Service Description -->
                            @if($selectedOrder->service_description)
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-1">Service Description</p>
                                <p class="text-sm text-gray-900">{{ $selectedOrder->service_description }}</p>
                            </div>
                            @endif

                            <!-- Customer Notes -->
                            @if($selectedOrder->customer_notes)
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-1">Your Notes</p>
                                <p class="text-sm text-gray-900">{{ $selectedOrder->customer_notes }}</p>
                            </div>
                            @endif

                            <!-- Quoted Price -->
                            @if($selectedOrder->quoted_price)
                            <div class="p-4 bg-purple-50 border border-purple-200 rounded-lg">
                                <p class="text-sm font-medium text-purple-900 mb-1">Quoted Price</p>
                                <p class="text-lg font-bold text-purple-900">
                                    {{ $selectedOrder->currency ?? 'TZS' }} {{ number_format($selectedOrder->quoted_price, 2) }}
                                </p>
                                @if($selectedOrder->quotation_notes)
                                <p class="text-sm text-purple-700 mt-2">{{ $selectedOrder->quotation_notes }}</p>
                                @endif
                                @if($selectedOrder->quoted_at)
                                <p class="text-xs text-purple-600 mt-2">Quoted on {{ \Carbon\Carbon::parse($selectedOrder->quoted_at)->format('M d, Y h:i A') }}</p>
                                @endif
                            </div>
                            @endif

                            <!-- Rejection Reason -->
                            @if($selectedOrder->rejection_reason)
                            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm font-medium text-red-900 mb-1">Rejection Reason</p>
                                <p class="text-sm text-red-800">{{ $selectedOrder->rejection_reason }}</p>
                            </div>
                            @endif

                            <!-- Completion Info -->
                            @if($selectedOrder->status === 'completed' && $selectedOrder->completed_at)
                            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                <p class="text-sm font-medium text-green-900 mb-1">Completed</p>
                                <p class="text-sm text-green-800">
                                    Completed on {{ \Carbon\Carbon::parse($selectedOrder->completed_at)->format('M d, Y h:i A') }}
                                </p>
                                @if($selectedOrder->completion_notes)
                                <p class="text-sm text-green-700 mt-2">{{ $selectedOrder->completion_notes }}</p>
                                @endif
                            </div>
                            @endif

                            <!-- Order Dates -->
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500">
                                    Order placed on {{ $selectedOrder->created_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="closeOrderModal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Booking Modal (shared) --}}
    @livewire('customer.garage-service-booking')
</div>

