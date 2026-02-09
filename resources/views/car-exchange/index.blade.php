@extends('layouts.customer')

@section('title', 'Car Exchange | Kibo Auto')

@push('styles')
<style>
    .kibo-text { color: #009866 !important; }
    .kibo-bg { background-color: #009866 !important; }
    .kibo-bg:hover { background-color: #007a52 !important; }
    .kibo-border { border-color: #009866 !important; }
    .kibo-bg-light { background-color: rgba(0, 152, 102, 0.1) !important; }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-white mb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="relative">
                <!-- Hero Text Section -->
                <div
                    class="relative h-80 rounded-2xl overflow-hidden bg-center bg-cover flex items-center justify-center"
                    style="background-image: linear-gradient(135deg, #009866 0%, #007a52 100%);"
                >
                    <div class="absolute inset-0 bg-black/20"></div>
                    <div class="relative text-center text-white px-4">
                        <h1 class="text-5xl md:text-6xl font-bold mb-4">Car Exchange</h1>
                        <p class="text-xl md:text-2xl text-white">Trade in your car for a better one</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Exchange Form Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        @livewire('customer.car-exchange-form')
    </section>

    <!-- Why Exchange Section -->
    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Why Exchange Your Car?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: rgba(0, 152, 102, 0.1);">
                        <svg class="w-8 h-8" style="color: #009866;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Fair Valuation</h3>
                    <p class="text-gray-600">Get a fair market value for your current vehicle</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: rgba(0, 152, 102, 0.1);">
                        <svg class="w-8 h-8" style="color: #009866;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Easy Process</h3>
                    <p class="text-gray-600">Simple and hassle-free exchange process</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: rgba(0, 152, 102, 0.1);">
                        <svg class="w-8 h-8" style="color: #009866;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Wide Selection</h3>
                    <p class="text-gray-600">Choose from our extensive inventory of vehicles</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Browse by Brand Component -->
    <x-customer.browse-by-brand />

    <!-- Feature Sections Component -->
    <x-customer.feature-sections />
@endsection

