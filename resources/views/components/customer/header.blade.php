@props(['vehicleType' => 'cars'])

<!-- Top Category Navigation -->
<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-start space-x-4 h-12">
            <a href="{{ route('cars.index') }}" class="text-sm font-medium {{ $vehicleType === 'cars' ? 'text-gray-900' : 'text-gray-600' }} hover:text-green-700">Cars</a>
            <a href="{{ route('vans.index') }}" class="text-sm font-medium {{ $vehicleType === 'vans' ? 'text-gray-900' : 'text-gray-600' }} hover:text-green-700">Vans</a>
            <a href="{{ route('bikes.index') }}" class="text-sm font-medium {{ $vehicleType === 'bikes' ? 'text-gray-900' : 'text-gray-600' }} hover:text-green-700">Bikes</a>
            <a href="{{ route('motorhomes.index') }}" class="text-sm font-medium {{ $vehicleType === 'motorhomes' ? 'text-gray-900' : 'text-gray-600' }} hover:text-green-700">Motorhomes</a>
            <a href="{{ route('caravans.index') }}" class="text-sm font-medium {{ $vehicleType === 'caravans' ? 'text-gray-900' : 'text-gray-600' }} hover:text-green-700">Caravans</a>
            <a href="{{ route('trucks.index') }}" class="text-sm font-medium {{ $vehicleType === 'trucks' ? 'text-gray-900' : 'text-gray-600' }} hover:text-green-700">Trucks</a>
            <a href="{{ route('farm.index') }}" class="text-sm font-medium {{ $vehicleType === 'farm' ? 'text-gray-900' : 'text-gray-600' }} hover:text-green-700">Farm</a>
            <a href="{{ route('plant.index') }}" class="text-sm font-medium {{ $vehicleType === 'plant' ? 'text-gray-900' : 'text-gray-600' }} hover:text-green-700">Plant</a>
            <a href="{{ route('electric-bikes.index') }}" class="text-sm font-medium {{ $vehicleType === 'electric-bikes' ? 'text-gray-900' : 'text-gray-600' }} hover:text-green-700">Electric bikes</a>
        </div>
    </div>
</nav>

<!-- Main Navigation -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('cars.index') }}" class="flex items-center">
                    <img src="{{ asset('logo/green.png') }}" alt="Logo" class="h-10 w-auto">
                </a>
            </div>

            <!-- Dynamic Navigation Links based on vehicle type -->
            <nav class="hidden md:flex items-center space-x-6">
                @include('components.customer.navigation.' . $vehicleType)
            </nav>

            <!-- Right Side Icons -->
            <div class="flex items-center space-x-4">
                <!-- Language Switcher -->
                <x-language-switcher-simple />
                
                <a href="#" class="flex flex-col items-center text-gray-700 hover:text-green-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span class="text-xs mt-1">Saved</span>
                </a>
                <button id="openAuthModal" class="flex flex-col items-center text-gray-700 hover:text-green-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-xs mt-1">{{ __('auth.sign_in') }}</span>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Auth Modal Component -->
<x-customer.auth-modal />
