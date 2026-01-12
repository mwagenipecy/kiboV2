@extends('layouts.customer')

@section('title', 'Trucks for Sale - Find Your Perfect Truck | Autotrader')

@section('content')
    <!-- Hero Section with Search -->
    <section class="relative bg-white mb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="relative">
                <!-- Hero Image -->
                <div class="relative h-80 rounded-2xl overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?auto=format&fit=crop&w=2000&q=80" 
                         alt="Commercial trucks" 
                         class="w-full h-full object-cover">
                </div>

                <!-- Search Form Overlay -->
                <div class="absolute bottom-0 left-0 right-0 transform translate-y-1/2 px-4">
                    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-6">
                        @livewire('customer.vehicle-search-form', ['vehicleType' => 'trucks'])
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Discovery Component -->
    <x-customer.discovery />

    <!-- Reserve Online Component -->
    <x-customer.reserve-online />

    <!-- Browse by Brand Component -->
    <x-customer.browse-by-brand />

    <!-- FAQ Accordion Component -->
    <x-customer.faq-accordion />

    <!-- Additional Features Section -->
    <section class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <!-- Feature 1 -->
                <div>
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Verified Dealers</h3>
                    <p class="text-gray-600">Buy with confidence from trusted sellers</p>
                </div>

                <!-- Feature 2 -->
                <div>
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Best Prices</h3>
                    <p class="text-gray-600">Compare prices and find great deals</p>
                </div>

                <!-- Feature 3 -->
                <div>
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Quick & Easy</h3>
                    <p class="text-gray-600">Find and buy your truck in minutes</p>
                </div>

                <!-- Feature 4 -->
                <div>
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">24/7 Support</h3>
                    <p class="text-gray-600">We're here to help anytime you need</p>
                </div>
            </div>
        </div>
    </section>
@endsection

