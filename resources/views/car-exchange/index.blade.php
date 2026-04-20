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
    <!-- Compact page header -->
    <section class="relative border-b border-gray-200 bg-gradient-to-r from-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Car exchange</h1>
            <p class="mt-1.5 text-sm sm:text-base text-gray-600 leading-relaxed">Tell us about your car and what you’re looking for. The request form is right below.</p>
        </div>
    </section>

    <!-- Exchange form (primary focus) -->
    <section class="bg-[#f6f8f7] border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            @livewire('customer.car-exchange-form')
        </div>
    </section>

    <!-- Why exchange -->
    <section class="bg-white py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 text-center mb-8">Why exchange with Kibo?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-6">
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 text-center">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background-color: rgba(0, 152, 102, 0.12);">
                        <svg class="w-6 h-6" style="color: #009866;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-1.5">Fair valuation</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Dealers see your details and photos to offer realistic trade-in value.</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 text-center">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background-color: rgba(0, 152, 102, 0.12);">
                        <svg class="w-6 h-6" style="color: #009866;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-1.5">Simple process</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Submit once — we route your request to the right partners.</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 text-center">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background-color: rgba(0, 152, 102, 0.12);">
                        <svg class="w-6 h-6" style="color: #009866;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-1.5">More choice</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Outline the car you want — budget, fuel type, and body style.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Browse by Brand Component -->
    <x-customer.browse-by-brand />

    <!-- Feature Sections Component -->
    <x-customer.feature-sections />
@endsection

