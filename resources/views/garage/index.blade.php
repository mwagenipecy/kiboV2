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
                <div class="absolute bottom-0 left-0 right-0 transform translate-y-1/2 px-3 sm:px-4">
                    <div class="max-w-4xl mx-auto bg-white rounded-2xl sm:rounded-3xl shadow-lg shadow-black/10 border border-gray-100 p-2.5 sm:p-4">
                        @php
                            $makes = \App\Models\VehicleMake::where('status', 'active')->orderBy('name')->get();
                        @endphp

                        {{-- Mobile --}}
                        <div class="md:hidden rounded-2xl border border-gray-200 bg-gray-50/90 overflow-hidden shadow-inner">
                            <div class="divide-y divide-gray-200/90">
                                <div class="px-4 pt-3 pb-2 bg-white">
                                    <label class="block text-[11px] font-semibold uppercase tracking-wide text-gray-500 mb-1">Search</label>
                                    <input
                                        type="text"
                                        placeholder="Search garages..."
                                        class="w-full min-h-[44px] px-0 pr-2 py-2 text-base text-gray-900 border-0 bg-transparent focus:ring-0 focus:outline-none placeholder:text-gray-400"
                                    >
                                </div>
                                <div class="px-4 pt-3 pb-2 bg-white">
                                    <label class="block text-[11px] font-semibold uppercase tracking-wide text-gray-500 mb-1">Vehicle make</label>
                                    <div class="relative">
                                        <select class="w-full min-h-[44px] pl-0 pr-9 py-2 text-base text-gray-900 border-0 bg-transparent focus:ring-0 focus:outline-none appearance-none">
                                            <option value="">All makes</option>
                                            @foreach($makes as $make)
                                                <option value="{{ $make->id }}">{{ $make->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="pointer-events-none absolute right-0 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2.5 bg-gray-50/80 border-t border-gray-200/90">
                                <a
                                    href="{{ route('garage.index') }}"
                                    class="w-full min-h-[48px] inline-flex items-center justify-center text-white text-[15px] font-semibold rounded-full transition-colors shadow-md"
                                    style="background-color: #009866;"
                                    onmouseover="this.style.backgroundColor='#007a52'"
                                    onmouseout="this.style.backgroundColor='#009866'"
                                >
                                    Find Garages
                                </a>
                            </div>
                        </div>

                        {{-- Desktop --}}
                        <div class="hidden md:flex md:items-stretch md:rounded-full md:border md:border-gray-200 md:bg-gray-50/80 md:overflow-hidden md:shadow-inner">
                            <div class="flex-1 min-w-0 md:border-r md:border-gray-200">
                                <input
                                    type="text"
                                    placeholder="Search garages..."
                                    class="w-full h-full min-h-[2.75rem] px-4 py-2 text-sm text-gray-900 border-0 bg-transparent focus:ring-2 focus:ring-inset focus:ring-[#009866]/25 focus:outline-none placeholder:text-gray-400"
                                >
                            </div>
                            <div class="w-64 shrink-0 md:border-r md:border-gray-200">
                                <select class="w-full h-full min-h-[2.75rem] px-4 py-2 text-sm text-gray-900 border-0 bg-transparent focus:ring-2 focus:ring-inset focus:ring-[#009866]/25 focus:outline-none appearance-none">
                                    <option value="">All makes</option>
                                    @foreach($makes as $make)
                                        <option value="{{ $make->id }}">{{ $make->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex shrink-0 p-1 pl-2">
                                <a
                                    href="{{ route('garage.index') }}"
                                    class="h-full min-h-[2.75rem] px-6 text-white text-sm font-semibold rounded-full inline-flex items-center justify-center whitespace-nowrap transition-colors shadow-sm"
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

