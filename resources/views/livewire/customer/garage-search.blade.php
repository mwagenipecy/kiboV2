<div>
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
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
            </div>

            {{-- Vehicle Make Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Make</label>
                <select
                    wire:model.live="selectedMake"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                    <option value="">All Makes</option>
                    @foreach($vehicleMakes as $make)
                        <option value="{{ $make->id }}">{{ $make->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Sort By --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                <select
                    wire:model.live="sortBy"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                    <option value="distance">Distance</option>
                    <option value="name">Name</option>
                </select>
            </div>
        </div>

        {{-- Location Controls --}}
        <div class="mt-4 flex items-center gap-4">
            @if($userLatitude && $userLongitude)
                <div class="flex items-center gap-2 text-sm text-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Location: {{ number_format($userLatitude, 4) }}, {{ number_format($userLongitude, 4) }}</span>
                </div>
                <button
                    wire:click="clearLocation"
                    class="text-sm text-gray-600 hover:text-gray-900 underline"
                >
                    Clear Location
                </button>
            @else
                <button
                    wire:click="getCurrentLocation"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Use My Location
                </button>
            @endif
        </div>
    </div>

    {{-- Results --}}
    @if($garages->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($garages as $garage)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                    {{-- Garage Image/Icon --}}
                    <div class="h-48 bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center">
                        <svg class="w-24 h-24 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                <span class="text-sm font-medium text-green-600">{{ $garage->distance }} km away</span>
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
                                        <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-full capitalize">{{ str_replace('_', ' ', $service) }}</span>
                                    @endforeach
                                    @if(count($garage->services) > 3)
                                        <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-full">+{{ count($garage->services) - 3 }} more</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Contact Button --}}
                        <div class="flex gap-2">
                            @if($garage->phone_number)
                                <a href="tel:{{ $garage->phone_number }}" class="flex-1 px-4 py-2 bg-white border-2 border-green-600 text-green-600 text-center font-semibold rounded-lg hover:bg-green-50 transition-colors">
                                    Call
                                </a>
                            @endif
                            <a href="mailto:{{ $garage->email }}" class="flex-1 px-4 py-2 bg-green-600 text-white text-center font-semibold rounded-lg hover:bg-green-700 transition-colors">
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
            Livewire.on('request-location', () => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            @this.setLocation(
                                position.coords.latitude,
                                position.coords.longitude
                            );
                        },
                        function(error) {
                            alert('Unable to get your location. Please enable location access in your browser settings.');
                            @this.showLocationModal = false;
                        },
                        { enableHighAccuracy: true, timeout: 10000 }
                    );
                } else {
                    alert('Geolocation is not supported by your browser.');
                    @this.showLocationModal = false;
                }
            });
        });
    </script>
</div>

