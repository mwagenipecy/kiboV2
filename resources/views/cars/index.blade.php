@extends('layouts.customer')

@section('title', 'Cars for Sale - Find Your Perfect Car')

@push('styles')
<style>
    .kibo-text { color: #009866 !important; }
</style>
@endpush

@section('content')
    <x-customer.page-hero slug="cars" variant="floating_overlay">
        <x-slot:overlay>
            @livewire('customer.vehicle-search-form', ['vehicleType' => 'cars'])
        </x-slot:overlay>
    </x-customer.page-hero>

    <!-- Cars List Section (2 rows + View more) - right after search -->
    <x-customer.cars-list-preview />

    <!-- Discovery Component -->
    <x-customer.discovery />

    <!-- Lease Carousel Component -->
    <x-customer.lease-carousel />

    <!-- Reserve Online Component -->
    <x-customer.reserve-online />

    <!-- Browse by Brand Component -->
    <x-customer.browse-by-brand />

    <!-- FAQ Accordion Component -->
    <x-customer.faq-accordion />

    <!-- Complaints & Feedback CTA -->
    <section class="bg-white py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-gray-200 bg-white p-8 sm:p-10 text-center shadow-sm">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-[#009866]/10 text-[#009866] mb-6">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Complaints &amp; Feedback</h2>
                <p class="mt-2 text-gray-600 max-w-xl mx-auto">Submit a complaint or track an existing one using your tracking number.</p>
                <a href="{{ route('cars.complaints') }}" class="mt-6 inline-flex items-center px-6 py-3 rounded-xl bg-[#009866] text-white font-semibold hover:bg-[#007a52] transition-colors shadow-sm">
                    Manage complaints
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Additional Features Section -->
    <section class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <!-- Feature 1 -->
                <div>
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 kibo-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Verified Dealers</h3>
                    <p class="text-gray-600">Buy with confidence from trusted sellers</p>
                </div>

                <!-- Feature 2 -->
                <div>
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 kibo-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Best Prices</h3>
                    <p class="text-gray-600">Compare prices and find great deals</p>
                </div>

                <!-- Feature 3 -->
                <div>
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 kibo-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Quick & Easy</h3>
                    <p class="text-gray-600">Find and buy your car in minutes</p>
                </div>

                <!-- Feature 4 -->
                <div>
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 kibo-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
