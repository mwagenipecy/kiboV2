@extends('layouts.customer')

@section('title', 'Electric Cars for Sale | Kibo Auto')

@section('content')
    <!-- Hero Section with Search -->
    <section class="relative bg-white mb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="relative">
                <!-- Hero Text Section (Instead of Image) -->
                <div class="relative h-80 rounded-2xl overflow-hidden bg-gradient-to-r from-green-600 to-green-800 flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        <h1 class="text-5xl md:text-6xl font-bold mb-4">Electric cars</h1>
                        <p class="text-xl md:text-2xl text-white">Zero emissions, maximum performance</p>
                    </div>
                </div>

                <!-- Search Form Overlay -->
                <div class="absolute bottom-0 left-0 right-0 transform translate-y-1/2 px-4">
                    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-6">
                        @livewire('customer.vehicle-search-form', ['vehicleType' => 'cars'])
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Electric Cars Search Results -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        @livewire('customer.vehicle-search', key('electric-search'))
    </section>

    <!-- Reserve Online Component -->
    <x-customer.reserve-online />

    <!-- Browse by Brand Component -->
    <x-customer.browse-by-brand />

    <!-- Feature Sections Component -->
    <x-customer.feature-sections />

    <!-- FAQ Component -->
    <x-customer.faq-accordion />

@endsection
