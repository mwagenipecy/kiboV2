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
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by name, company, or location..."
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

                        {{-- Contact Button --}}
                        <div class="flex gap-2">
                            @if($garage->phone_number)
                                <a href="tel:{{ $garage->phone_number }}" class="flex-1 px-4 py-2 bg-white border-2 text-center font-semibold rounded-lg transition-colors" style="border-color: #009866; color: #009866;">
                                    Call
                                </a>
                            @endif
                            <a href="mailto:{{ $garage->email }}" class="flex-1 px-4 py-2 text-white text-center font-semibold rounded-lg transition-colors" style="background-color: #009866;">
                                Email
                            </a>
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
</div>

