@extends('layouts.customer')

@section('title', 'Find a Garage | Kibo Auto')

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
                    style="background-image: url('{{ asset('image/garage.png') }}');"
                >
                    <div class="absolute inset-0 bg-black/40"></div>
                    <div class="relative text-center text-white px-4">
                        <h1 class="text-5xl md:text-6xl font-bold mb-4">Find a Garage</h1>
                        <p class="text-xl md:text-2xl text-white">Trusted garages near you</p>
                    </div>
                </div>

                <!-- Search Form Overlay -->
                <div class="absolute bottom-0 left-0 right-0 transform translate-y-1/2 px-4">
                    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                                <input
                                    type="text"
                                    placeholder="Search garages..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                    style="--tw-ring-color: #009866;"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Make</label>
                                <select
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                    style="--tw-ring-color: #009866;"
                                >
                                    <option value="">All Makes</option>
                                    @php
                                        $makes = \App\Models\VehicleMake::where('status', 'active')->orderBy('name')->get();
                                    @endphp
                                    @foreach($makes as $make)
                                        <option value="{{ $make->id }}">{{ $make->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end">
                                <a
                                    href="{{ route('garage.index') }}"
                                    class="w-full px-6 py-2 text-white text-center font-semibold rounded-lg transition-colors"
                                    style="background-color: #009866;"
                                    onmouseover="this.style.backgroundColor='#007a52'"
                                    onmouseout="this.style.backgroundColor='#009866'"
                                >
                                    Find Garages
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Garage Search Results -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        @livewire('customer.garage-search')
    </section>

    <!-- Why Choose Our Garages -->
    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Why Choose Our Garages</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: rgba(0, 152, 102, 0.1);">
                        <svg class="w-8 h-8" style="color: #009866;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Verified & Trusted</h3>
                    <p class="text-gray-600">All garages are verified and approved by our team</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: rgba(0, 152, 102, 0.1);">
                        <svg class="w-8 h-8" style="color: #009866;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Nearby Locations</h3>
                    <p class="text-gray-600">Find garages close to your location</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm text-center">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: rgba(0, 152, 102, 0.1);">
                        <svg class="w-8 h-8" style="color: #009866;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0h-.01M15 17a2 2 0 104 0m-4 0h-.01"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Expert Services</h3>
                    <p class="text-gray-600">Professional mechanics with years of experience</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Browse by Brand Component -->
    <x-customer.browse-by-brand />

    <!-- Feature Sections Component -->
    <x-customer.feature-sections />
@endsection

