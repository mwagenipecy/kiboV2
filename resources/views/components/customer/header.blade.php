@props(['vehicleType' => 'cars'])

{{-- Sticky wrapper: category nav + header + global track modal (same on every section) --}}
<div
    class="sticky top-0 z-[100] bg-white shadow-sm"
    x-data="{
        mobileMenuOpen: false,
        trackModalOpen: false,
        trackInput: '',
        trackError: '',
        openTrack() {
            this.trackModalOpen = true;
            this.trackError = '';
            this.trackInput = '';
            this.$nextTick(() => this.$refs.trackInputField?.focus());
        },
        closeTrack() {
            this.trackModalOpen = false;
        },
        submitTrack() {
            this.trackError = '';
            const v = (this.trackInput || '').trim();
            const orderNorm = v.replace(/\s+/g, '').toUpperCase();
            if (/^SPO-\d{8}-[A-Z0-9]+$/.test(orderNorm)) {
                window.location.href = '{{ rtrim(url('/spare-parts/track/order'), '/') }}/' + encodeURIComponent(orderNorm);
                return;
            }
            let token = null;
            const urlMatch = v.match(/\/spare-parts\/track\/([a-f0-9]{40})/i);
            if (urlMatch) {
                token = urlMatch[1].toLowerCase();
            } else {
                const hex = v.replace(/[^a-f0-9]/gi, '');
                if (hex.length === 40) {
                    token = hex.toLowerCase();
                }
            }
            if (!token) {
                this.trackError = 'Enter your order number (e.g. SPO-20260404-9E2D2A), or paste the link / 40-character code from your SMS.';
                return;
            }
            window.location.href = '{{ rtrim(url('/spare-parts/track'), '/') }}/' + token;
        }
    }"
    @keydown.escape.window="closeTrack()"
>
    <!-- Top Category Navigation -->
    <nav class="bg-white border-b border-gray-200 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-4 h-12 w-full">
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 min-w-0">
                <a href="{{ route('cars.index') }}" class="text-sm font-medium {{ $vehicleType === 'cars' || request()->routeIs('cars.*') ? 'text-gray-900 font-semibold border-b-2 border-green-600 pb-1' : 'text-gray-600' }} hover:text-green-700 transition-colors">{{ __('vehicles.cars') }}</a>
                <a href="{{ route('trucks.index') }}" class="text-sm font-medium {{ $vehicleType === 'trucks' || request()->routeIs('trucks.*') ? 'text-gray-900 font-semibold border-b-2 border-green-600 pb-1' : 'text-gray-600' }} hover:text-green-700 transition-colors">{{ __('vehicles.trucks') }}</a>
                <a href="{{ route('spare-parts.index') }}" class="text-sm font-medium {{ $vehicleType === 'spare-parts' || request()->routeIs('spare-parts.*') ? 'text-gray-900 font-semibold border-b-2 border-green-600 pb-1' : 'text-gray-600' }} hover:text-green-700 transition-colors">Spare Parts</a>
                <a href="{{ route('garage.index') }}" class="text-sm font-medium {{ $vehicleType === 'garage' || request()->routeIs('garage.*') ? 'text-gray-900 font-semibold border-b-2 border-green-600 pb-1' : 'text-gray-600' }} hover:text-green-700 transition-colors">Garage</a>
                <a href="{{ route('import-financing.index') }}" class="text-sm font-medium {{ $vehicleType === 'financing' || request()->routeIs('loan-calculator.*') || request()->routeIs('import-financing.*') ? 'text-gray-900 font-semibold border-b-2 border-green-600 pb-1' : 'text-gray-600' }} hover:text-green-700 transition-colors">Financing</a>
                <a href="{{ route('agiza-import.index') }}" class="text-sm font-medium {{ $vehicleType === 'agiza-import' || request()->routeIs('agiza-import.*') ? 'text-gray-900 font-semibold border-b-2 border-green-600 pb-1' : 'text-gray-600' }} hover:text-green-700 transition-colors">Agiza/Import</a>
                <a href="{{ route('car-exchange.index') }}" class="text-sm font-medium {{ $vehicleType === 'car-exchange' || request()->routeIs('car-exchange.*') ? 'text-gray-900 font-semibold border-b-2 border-green-600 pb-1' : 'text-gray-600' }} hover:text-green-700 transition-colors">Car Exchange</a>
                </div>
                <button
                    type="button"
                    @click="openTrack()"
                    class="shrink-0 text-sm font-semibold px-3 py-1.5 rounded-lg border border-emerald-600 text-emerald-700 hover:bg-emerald-50 transition-colors"
                >
                    Track order
                </button>
                <!-- <a href="{{ route('cars.complaints') }}" class="ml-auto px-4 py-1.5 rounded-lg text-sm font-semibold text-white hover:opacity-90 transition-opacity" style="background-color: #009866;">Complaints</a> -->
                {{-- Hidden menus --}}
                {{-- <a href="{{ route('vans.index') }}" class="text-sm font-medium {{ $vehicleType === 'vans' ? 'text-gray-900' : 'text-gray-600' }} hover:text-green-700">{{ __('vehicles.vans') }}</a> --}}
                {{-- <a href="{{ route('bikes.index') }}" class="text-sm font-medium {{ $vehicleType === 'bikes' ? 'text-gray-900' : 'text-gray-600' }} hover:text-green-700">{{ __('vehicles.bikes') }}</a> --}}
                {{-- <a href="{{ route('motorhomes.index') }}" class="text-sm font-medium {{ $vehicleType === 'motorhomes' ? 'text-gray-900' : 'text-gray-600' }} hover:text-green-700">{{ __('vehicles.motorhomes') }}</a> --}}
                {{-- <a href="{{ route('caravans.index') }}" class="text-sm font-medium {{ $vehicleType === 'caravans' ? 'text-gray-900' : 'text-gray-600' }} hover:text-green-700">{{ __('vehicles.caravans') }}</a> --}}
                {{-- <a href="{{ route('farm.index') }}" class="text-sm font-medium {{ $vehicleType === 'farm' ? 'text-gray-900' : 'text-gray-600' }} hover:text-green-700">{{ __('vehicles.farm') }}</a> --}}
                {{-- <a href="{{ route('plant.index') }}" class="text-sm font-medium {{ $vehicleType === 'plant' ? 'text-gray-900' : 'text-gray-600' }} hover:text-green-700">{{ __('vehicles.plant') }}</a> --}}
                {{-- <a href="{{ route('electric-bikes.index') }}" class="text-sm font-medium {{ $vehicleType === 'electric-bikes' ? 'text-gray-900' : 'text-gray-600' }} hover:text-green-700">{{ __('vehicles.electric_bikes') }}</a> --}}
            </div>
        </div>
    </nav>

    <!-- Main Navigation -->
    <header class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo and Mobile Menu Button -->
            <div class="flex items-center">
                <!-- Mobile Menu Toggle -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-600 hover:text-gray-900 mr-3" aria-label="Toggle menu">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                <a href="{{ route('cars.index') }}" class="flex items-center">
                    <img src="{{ asset('logo/green.png') }}" alt="Logo" class="h-10 w-auto">
                </a>
            </div>

            <!-- Dynamic Navigation Links based on vehicle type (Desktop) -->
            <nav class="hidden md:flex items-center space-x-6">
                @include('components.customer.navigation.' . $vehicleType)
            </nav>

            <!-- Right Side Icons -->
            <div class="flex items-center space-x-3 sm:space-x-4">
                <button
                    type="button"
                    @click="openTrack()"
                    class="md:hidden shrink-0 text-sm font-semibold text-emerald-700 hover:text-emerald-800 px-2 py-1 rounded-lg hover:bg-emerald-50"
                >
                    Track
                </button>
                <!-- Language Switcher -->
                <!-- <x-language-switcher-simple /> -->
                
                @auth
                    <!-- Saved Vehicles (only show when authenticated) -->
                    <!-- <a href="#" class="hidden md:flex flex-col items-center text-gray-700 hover:text-green-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="text-xs mt-1">{{ __('common.saved') }}</span>
                    </a> -->
                @endauth
                
                <!-- User Menu (Livewire Component) -->
                @livewire('customer.user-menu')
            </div>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileMenuOpen = false"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 md:hidden"
         style="display: none;">
    </div>

    <!-- Mobile Menu Sidebar -->
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl overflow-y-auto md:hidden"
         style="display: none;">
        <div class="flex flex-col h-full">
            <!-- Mobile Menu Header -->
            <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200">
                <a href="{{ route('cars.index') }}" @click="mobileMenuOpen = false" class="flex items-center">
                    <img src="{{ asset('logo/green.png') }}" alt="Logo" class="h-8 w-auto">
                </a>
                <button @click="mobileMenuOpen = false" class="p-2 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Top Category Navigation (Mobile) -->
            <div class="px-4 py-4 border-b border-gray-200">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Categories</h3>
                <div class="space-y-2">
                    <a href="{{ route('cars.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ $vehicleType === 'cars' || request()->routeIs('cars.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        {{ __('vehicles.cars') }}
                    </a>
                    <a href="{{ route('trucks.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ $vehicleType === 'trucks' || request()->routeIs('trucks.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        {{ __('vehicles.trucks') }}
                    </a>
                    <a href="{{ route('spare-parts.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ $vehicleType === 'spare-parts' || request()->routeIs('spare-parts.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        Spare Parts
                    </a>
                    <a href="{{ route('garage.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ $vehicleType === 'garage' || request()->routeIs('garage.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        Garage
                    </a>
                    <a href="{{ route('import-financing.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ $vehicleType === 'financing' || request()->routeIs('loan-calculator.*') || request()->routeIs('import-financing.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        Financing
                    </a>
                    <a href="{{ route('agiza-import.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ $vehicleType === 'agiza-import' || request()->routeIs('agiza-import.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        Agiza/Import
                    </a>
                    <a href="{{ route('car-exchange.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ $vehicleType === 'car-exchange' || request()->routeIs('car-exchange.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        Car Exchange
                    </a>
                    <button
                        type="button"
                        @click="mobileMenuOpen = false; openTrack()"
                        class="w-full text-left block px-3 py-2 rounded-lg text-sm font-semibold text-emerald-700 border border-emerald-200 hover:bg-emerald-50 transition-colors mt-1"
                    >
                        Track order
                    </button>
                    <a href="{{ route('cars.complaints') }}" @click="mobileMenuOpen = false" class="block px-4 py-3 rounded-lg text-sm font-semibold text-white text-center mt-3 transition-opacity hover:opacity-90" style="background-color: #009866;">
                        Complaints
                    </a>
                </div>
            </div>

            <!-- Main Navigation (Mobile) -->
            <div class="flex-1 px-4 py-4 overflow-y-auto">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Menu</h3>
                <div class="space-y-1">
                    @if($vehicleType === 'cars')
                        <a href="{{ route('cars.used') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('cars.used') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">{{ __('vehicles.used_cars') }}</a>
                        <a href="{{ route('cars.new') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('cars.new') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">{{ __('vehicles.new_cars') }}</a>
                        <a href="{{ route('cars.sell') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('cars.sell') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">{{ __('vehicles.sell_your_car') }}</a>
                        <a href="{{ route('cars.find') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('cars.find') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">{{ __('vehicles.find_me_a_car') }}</a>
                        <a href="{{ route('cars.leasing') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('cars.leasing') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">{{ __('vehicles.car_leasing') }}</a>
                        <a href="{{ route('cars.electric') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('cars.electric') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">{{ __('vehicles.electric_cars') }}</a>
                        <a href="{{ route('cars.insurance') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('cars.insurance') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">{{ __('vehicles.car_insurance') }}</a>
                    @elseif($vehicleType === 'trucks')
                        <a href="{{ route('trucks.search') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('trucks.search') || request()->routeIs('trucks.index') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">Search trucks</a>
                        <a href="{{ route('trucks.used') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('trucks.used') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">Used trucks</a>
                        <a href="{{ route('trucks.new') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('trucks.new') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">New trucks</a>
                        <a href="{{ route('trucks.reviews') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('trucks.reviews') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">Truck reviews</a>
                        <a href="{{ route('trucks.finance') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('trucks.finance') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">Finance options</a>
                        <a href="{{ route('trucks.parts') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('trucks.parts') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">Parts & accessories</a>
                    @elseif($vehicleType === 'spare-parts')
                        <a href="{{ route('spare-parts.sourcing') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('spare-parts.sourcing') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">Order Spare Parts</a>
                        <a href="{{ route('spare-parts.orders') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('spare-parts.orders') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">See Previous Orders</a>
                    @elseif($vehicleType === 'garage')
                        <a href="{{ route('garage.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('garage.index') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">Find a Garage</a>
                        <a href="{{ route('garage.services') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('garage.services') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">Services</a>
                    @elseif($vehicleType === 'financing')
                        <a href="{{ route('import-financing.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('import-financing.index') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">Apply for Financing</a>
                        @auth
                        <a href="{{ route('import-financing.requests') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('import-financing.requests') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">My Requests</a>
                        @endauth
                        <a href="{{ route('loan-calculator.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('loan-calculator.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">Loan Calculator</a>
                    @elseif($vehicleType === 'agiza-import')
                        <a href="{{ route('agiza-import.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('agiza-import.index') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">Submit Import Request</a>
                        @auth
                        <a href="{{ route('agiza-import.requests') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('agiza-import.requests') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">My Import Requests</a>
                        @endauth
                    @endif
                </div>
            </div>

            <!-- Mobile Menu Footer -->
            <div class="px-4 py-4 border-t border-gray-200">
                @auth
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('my-adverts') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 mb-2">
                            My Adverts
                        </a>
                        <a href="{{ route('my-orders') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 mb-2">
                            My Orders
                        </a>
                        <a href="{{ route('profile.edit') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Profile Settings
                        </a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Dashboard
                        </a>
                    @endif
                @else
                    <button onclick="document.getElementById('openAuthModal').click()" @click="mobileMenuOpen = false" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition-colors">
                        Sign In
                    </button>
                @endauth
            </div>
        </div>
    </div>
</header>

    {{-- Global track modal (spare parts order — same from any category) --}}
    <div
        x-show="trackModalOpen"
        x-cloak
        class="fixed inset-0 z-[200] flex items-center justify-center bg-black/50 p-4"
        @click.self="closeTrack()"
    >
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl" @click.stop role="dialog" aria-modal="true" aria-labelledby="track-modal-title">
            <h2 id="track-modal-title" class="text-lg font-bold text-gray-900 mb-1">Track spare part order</h2>
            <p class="text-sm text-gray-600 mb-4">Use your <strong>order number</strong> (e.g. SPO-20260404-9E2D2A), or paste the link / code from your SMS.</p>
            <input
                x-ref="trackInputField"
                type="text"
                x-model="trackInput"
                @keydown.enter.prevent="submitTrack()"
                placeholder="SPO-20260404-9E2D2A or link…"
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/25 outline-none mb-2"
                autocomplete="off"
            />
            <p x-show="trackError" x-text="trackError" class="text-sm text-red-600 mb-3"></p>
            <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
                <button type="button" @click="closeTrack()" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 order-2 sm:order-1">Cancel</button>
                <button type="button" @click="submitTrack()" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 order-1 sm:order-2">View order</button>
            </div>
            <p class="mt-4 text-xs text-gray-500">
                <a href="{{ route('spare-parts.track-landing') }}" class="text-emerald-700 font-medium hover:underline">More about tracking</a>
            </p>
        </div>
    </div>
</div>

<!-- Auth Modal Component -->
<x-customer.auth-modal />
