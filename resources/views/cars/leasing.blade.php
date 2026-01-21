@extends('layouts.customer')

@section('title', 'Car Leasing | Flexible Lease Deals')

@push('styles')
<style>
    .kibo-text { color: #009866 !important; }
    .kibo-bg { background-color: #009866 !important; }
    .kibo-bg-light { background-color: rgba(0, 152, 102, 0.1) !important; }
    .kibo-gradient-bg { background: linear-gradient(to bottom right, rgba(0, 152, 102, 0.1), white, rgba(0, 152, 102, 0.1)) !important; }
    .kibo-gradient-line { background: linear-gradient(to right, rgba(0, 152, 102, 0.3), rgba(0, 152, 102, 0.15), transparent) !important; }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="relative kibo-gradient-bg py-20 overflow-hidden">
        <div class="absolute inset-0 bg-grid-gray-900/[0.04] bg-[size:20px_20px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                   
                    
                    <h1 class="text-3xl md:text-3xl font-bold text-gray-900 mb-6 leading-tight">
                        Lease Your Dream Car
                        <span class="block kibo-text mt-2">Today</span>
                    </h1>
                    
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Drive a brand new vehicle with flexible lease terms. Low monthly payments, 
                        no long-term commitment, and the option to upgrade when you're ready.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 mb-8">
                        <a href="{{ route('cars.lease.index') }}" class="inline-flex items-center justify-center px-8 py-4 kibo-bg text-white font-semibold rounded-lg transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <span>Browse Available Leases</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="#how-it-works" class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg transition-colors" style="--hover-color: #009866;" onmouseover="this.style.borderColor='#009866'; this.style.color='#009866';" onmouseout="this.style.borderColor='#d1d5db'; this.style.color='#374151';">
                            How It Works
                        </a>
                    </div>
                    
                    <!-- Key Stats -->
                    <div class="grid grid-cols-3 gap-6 pt-8 border-t border-gray-200">
                        <div>
                            <div class="text-3xl font-bold kibo-text mb-1">
                                @php
                                    $activeLeases = \App\Models\VehicleLease::active()->count();
                                @endphp
                                {{ $activeLeases }}+
                            </div>
                            <div class="text-sm text-gray-600">Available Leases</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold kibo-text mb-1">
                                @php
                                    $avgPayment = \App\Models\VehicleLease::active()->avg('monthly_payment');
                                @endphp
                                ${{ number_format($avgPayment ?? 500, 0) }}
                            </div>
                            <div class="text-sm text-gray-600">Avg. Monthly</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold kibo-text mb-1">24-60</div>
                            <div class="text-sm text-gray-600">Month Terms</div>
                        </div>
                    </div>
                </div>
                
                <div class="hidden lg:block">
                    <div class="relative">
                        <div class="absolute inset-0 kibo-bg-light rounded-3xl transform rotate-6"></div>
                        <div class="relative bg-white rounded-3xl shadow-2xl p-8 border-2" style="border-color: rgba(0, 152, 102, 0.2);">
                            <img 
                                src="https://images.unsplash.com/photo-1617531653332-bd46c24f2068?auto=format&fit=crop&w=800&q=80" 
                                alt="Car Leasing" 
                                class="w-full h-auto rounded-2xl object-cover"
                                loading="lazy"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works / Steps Section -->
    <section id="how-it-works" class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    How Car Leasing Works
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Getting started with car leasing is simple. Follow these easy steps to drive your new vehicle.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="relative">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-20 h-20 kibo-bg-light rounded-full flex items-center justify-center mb-6 relative z-10">
                            <span class="text-3xl font-bold kibo-text">1</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Browse & Select</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Explore our wide selection of lease vehicles. Filter by price, term, make, and model to find your perfect match.
                        </p>
                    </div>
                    <div class="hidden lg:block absolute top-10 left-1/2 w-full h-0.5 kibo-gradient-line transform translate-x-10"></div>
                </div>
                
                <!-- Step 2 -->
                <div class="relative">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-20 h-20 kibo-bg-light rounded-full flex items-center justify-center mb-6 relative z-10">
                            <span class="text-3xl font-bold kibo-text">2</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Apply Online</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Complete our quick online application form. Provide your details, income information, and select your preferred lease term.
                        </p>
                    </div>
                    <div class="hidden lg:block absolute top-10 left-1/2 w-full h-0.5 kibo-gradient-line transform translate-x-10"></div>
                </div>
                
                <!-- Step 3 -->
                <div class="relative">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-20 h-20 kibo-bg-light rounded-full flex items-center justify-center mb-6 relative z-10">
                            <span class="text-3xl font-bold kibo-text">3</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Get Approved</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Our team reviews your application quickly. Once approved, we'll contact you to finalize the lease agreement and schedule delivery.
                        </p>
                    </div>
                    <div class="hidden lg:block absolute top-10 left-1/2 w-full h-0.5 kibo-gradient-line transform translate-x-10"></div>
                </div>
                
                <!-- Step 4 -->
                <div class="relative">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-20 h-20 kibo-bg-light rounded-full flex items-center justify-center mb-6 relative z-10">
                            <span class="text-3xl font-bold kibo-text">4</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Drive Away</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Sign your lease agreement, make your initial payment, and drive away in your new vehicle. It's that simple!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Comparison Section: Lease vs Buy -->
    <section class="bg-gradient-to-br from-gray-50 to-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Leasing vs. Buying
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Compare the advantages of leasing versus traditional car buying
                </p>
            </div>
            
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-200">
                    <!-- Leasing Column -->
                    <div class="p-8 md:p-12 kibo-gradient-bg">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 kibo-bg rounded-full mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">Leasing</h3>
                            <p class="text-gray-600">Ideal for flexibility</p>
                        </div>
                        
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 kibo-text mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-900">Lower Monthly Payments</span>
                                    <p class="text-sm text-gray-600">Pay only for depreciation during lease term</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 kibo-text mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-900">Always Drive New</span>
                                    <p class="text-sm text-gray-600">Upgrade to a new vehicle every few years</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 kibo-text mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-900">No Resale Hassle</span>
                                    <p class="text-sm text-gray-600">Simply return the vehicle at lease end</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 kibo-text mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-900">Warranty Coverage</span>
                                    <p class="text-sm text-gray-600">Most leases covered under manufacturer warranty</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Buying Column -->
                    <div class="p-8 md:p-12">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-600 rounded-full mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">Buying</h3>
                            <p class="text-gray-600">Ideal for ownership</p>
                        </div>
                        
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-gray-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-700">Higher Monthly Payments</span>
                                    <p class="text-sm text-gray-500">Pay full vehicle value plus interest</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-gray-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-700">Vehicle Depreciation</span>
                                    <p class="text-sm text-gray-500">Vehicle loses value over time</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-gray-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-700">Resale Responsibility</span>
                                    <p class="text-sm text-gray-500">You're responsible for selling or trading</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 kibo-text mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-900">Full Ownership</span>
                                    <p class="text-sm text-gray-600">You own the vehicle after loan payoff</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Why Choose Car Leasing?
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Discover the advantages of leasing over buying
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Benefit 1 -->
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="w-14 h-14 kibo-bg-light rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 kibo-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Lower Monthly Payments</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Lease payments are typically lower than loan payments because you're only paying for the vehicle's depreciation during the lease term.
                    </p>
                </div>
                
                <!-- Benefit 2 -->
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="w-14 h-14 kibo-bg-light rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 kibo-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Always Drive New Cars</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Enjoy the latest models with the newest features, technology, and safety systems. Upgrade to a new vehicle every few years.
                    </p>
                </div>
                
                <!-- Benefit 3 -->
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="w-14 h-14 kibo-bg-light rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 kibo-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">No Resale Hassle</h3>
                    <p class="text-gray-600 leading-relaxed">
                        At the end of your lease, simply return the vehicle. No need to worry about selling or trading in your car.
                    </p>
                </div>
                
                <!-- Benefit 4 -->
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="w-14 h-14 kibo-bg-light rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 kibo-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Flexible Terms</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Choose from various lease terms (12, 24, 36, 48, or 60 months) that fit your budget and lifestyle.
                    </p>
                </div>
                
                <!-- Benefit 5 -->
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="w-14 h-14 kibo-bg-light rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 kibo-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Warranty Coverage</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Most leased vehicles are covered under manufacturer warranty, so you have peace of mind during your lease term.
                    </p>
                </div>
                
                <!-- Benefit 6 -->
                <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="w-14 h-14 kibo-bg-light rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 kibo-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Tax Benefits</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Business lease payments may be tax-deductible. Consult with your accountant to understand the benefits for your situation.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Lease Vehicles Carousel -->
    @php
        $featuredLeases = \App\Models\VehicleLease::active()
            ->featured()
            ->orderBy('priority', 'desc')
            ->limit(8)
            ->get();
    @endphp
    
    @if($featuredLeases->count() > 0)
    <section class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Featured Lease Deals
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Discover our handpicked selection of the best lease offers
                </p>
            </div>
            
            <!-- Carousel Container -->
            <div class="relative">
                <!-- Scroll Container -->
                <div
                    id="featuredLeaseScrollContainer"
                    class="flex gap-6 overflow-x-auto scrollbar-hide scroll-smooth pb-4 snap-x snap-mandatory"
                    style="scrollbar-width: none; -ms-overflow-style: none;"
                >
                    @foreach($featuredLeases as $lease)
                    <!-- Deal Card -->
                    <a href="{{ route('cars.lease.detail', $lease->id) }}" class="flex-none w-[320px] bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 snap-start block border border-gray-200">
                        <!-- Image Section -->
                        <div class="relative aspect-[4/3] bg-gray-100">
                            @if($lease->image_front)
                                <img
                                    src="{{ asset('storage/' . $lease->image_front) }}"
                                    alt="{{ $lease->vehicle_title }}"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                />
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            
                            @php
                                $imageCount = 1;
                                if ($lease->image_side) $imageCount++;
                                if ($lease->image_back) $imageCount++;
                                if ($lease->other_images && is_array($lease->other_images)) {
                                    $imageCount += count($lease->other_images);
                                }
                            @endphp
                            
                            <!-- Image Count Badge -->
                            @if($imageCount > 1)
                            <div class="absolute top-3 left-3 bg-gray-800 bg-opacity-80 text-white px-2 py-1 rounded flex items-center gap-1 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $imageCount }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Content Section -->
                        <div class="p-5">
                            <!-- Pricing Header -->
                            <div class="mb-4">
                                <div class="flex items-baseline gap-2 mb-2">
                                    <span class="text-sm text-gray-600">From</span>
                                    <span class="text-4xl font-bold kibo-text">${{ number_format($lease->monthly_payment, 0) }}</span>
                                    <div class="text-xs text-gray-600 ml-auto text-right">
                                        <div class="font-semibold">${{ number_format($lease->total_upfront_cost, 0) }} initial</div>
                                        <div>{{ $lease->lease_term_months }} months</div>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600">Per month</div>
                                <div class="text-sm text-gray-600">{{ number_format($lease->mileage_limit_per_year) }} miles p/a</div>
                            </div>

                            <!-- Delivery Info -->
                            @if($lease->available_from)
                            <div class="text-sm font-medium text-gray-700 mb-4 pb-4 border-b border-gray-200">
                                Available from {{ $lease->available_from->format('F Y') }}
                            </div>
                            @else
                            <div class="text-sm font-medium kibo-text mb-4 pb-4 border-b border-gray-200">
                                Available Now
                            </div>
                            @endif

                            <!-- Car Details -->
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">
                                    {{ $lease->vehicle_title }}
                                </h3>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    {{ $lease->vehicle_year }} {{ $lease->vehicle_make }} {{ $lease->vehicle_model }}
                                    @if($lease->vehicle_variant) - {{ $lease->vehicle_variant }} @endif
                                </p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- Navigation Arrow - Right -->
                <button
                    id="featuredScrollRightBtn"
                    class="hidden lg:flex absolute -right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white rounded-full shadow-lg items-center justify-center hover:bg-gray-50 transition-colors z-10"
                    aria-label="Scroll right"
                >
                    <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            <!-- View More Link -->
            <div class="text-center mt-8">
                <a
                    href="{{ route('cars.lease.index') }}"
                    class="inline-flex items-center gap-2 text-gray-900 font-semibold hover:gap-3 transition-all duration-200 group"
                >
                    <span class="border-b-2 border-gray-900">View more lease deals</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- FAQ Section -->
    <section class="bg-white py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Title -->
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12">
                Frequently Asked Questions
            </h2>

            <!-- FAQ Items -->
            <div class="space-y-0">
                <!-- FAQ 1 -->
                <div class="border-b border-gray-200">
                    <button
                        class="faq-question w-full py-6 px-4 flex items-center justify-between text-left hover:bg-gray-50 transition-colors"
                        data-faq="0"
                    >
                        <span class="text-lg md:text-xl text-gray-900 font-medium pr-8">
                            What is car leasing and how does it work?
                        </span>
                        <div class="flex-shrink-0">
                            <svg class="plus-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <svg class="minus-icon w-6 h-6 text-gray-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </div>
                    </button>

                    <div class="faq-answer px-4 pb-6 hidden">
                        <p class="text-gray-600 leading-relaxed">
                            Car leasing is a financing option where you pay to use a vehicle for a set period (typically 24-60 months) without owning it. 
                            You make monthly payments based on the vehicle's depreciation, plus interest and fees. At the end of the lease term, you can 
                            return the vehicle, purchase it at a predetermined price, or lease a new vehicle. It's similar to renting, but for a longer term 
                            with the option to buy at the end.
                        </p>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="border-b border-gray-200">
                    <button
                        class="faq-question w-full py-6 px-4 flex items-center justify-between text-left hover:bg-gray-50 transition-colors"
                        data-faq="1"
                    >
                        <span class="text-lg md:text-xl text-gray-900 font-medium pr-8">
                            What are the typical lease terms and monthly payments?
                        </span>
                        <div class="flex-shrink-0">
                            <svg class="plus-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <svg class="minus-icon w-6 h-6 text-gray-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </div>
                    </button>

                    <div class="faq-answer px-4 pb-6 hidden">
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Lease terms typically range from 12 to 60 months, with 36 and 48 months being the most common. Monthly payments depend on several factors:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4">
                            <li>Vehicle price and residual value</li>
                            <li>Lease term length (longer terms = lower monthly payments)</li>
                            <li>Down payment amount</li>
                            <li>Interest rate</li>
                            <li>Mileage allowance</li>
                        </ul>
                        <p class="text-gray-600 leading-relaxed mt-4">
                            On average, monthly payments can range from $200 to $800+ depending on the vehicle and terms selected.
                        </p>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="border-b border-gray-200">
                    <button
                        class="faq-question w-full py-6 px-4 flex items-center justify-between text-left hover:bg-gray-50 transition-colors"
                        data-faq="2"
                    >
                        <span class="text-lg md:text-xl text-gray-900 font-medium pr-8">
                            What happens at the end of my lease?
                        </span>
                        <div class="flex-shrink-0">
                            <svg class="plus-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <svg class="minus-icon w-6 h-6 text-gray-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </div>
                    </button>

                    <div class="faq-answer px-4 pb-6 hidden">
                        <p class="text-gray-600 leading-relaxed">
                            At the end of your lease term, you have three options:
                        </p>
                        <ol class="list-decimal list-inside text-gray-600 space-y-2 mt-4 ml-4">
                            <li><strong>Return the vehicle:</strong> Simply return the car in good condition (normal wear and tear is acceptable) and walk away. You may be charged for excess mileage or damage beyond normal wear.</li>
                            <li><strong>Purchase the vehicle:</strong> Buy the vehicle at the predetermined residual value stated in your lease agreement.</li>
                            <li><strong>Lease a new vehicle:</strong> Start a new lease on a different vehicle and return your current one.</li>
                        </ol>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="border-b border-gray-200">
                    <button
                        class="faq-question w-full py-6 px-4 flex items-center justify-between text-left hover:bg-gray-50 transition-colors"
                        data-faq="3"
                    >
                        <span class="text-lg md:text-xl text-gray-900 font-medium pr-8">
                            What is excess mileage and how is it charged?
                        </span>
                        <div class="flex-shrink-0">
                            <svg class="plus-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <svg class="minus-icon w-6 h-6 text-gray-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </div>
                    </button>

                    <div class="faq-answer px-4 pb-6 hidden">
                        <p class="text-gray-600 leading-relaxed">
                            Excess mileage refers to the miles driven beyond the agreed-upon annual mileage limit in your lease contract. Most leases include 
                            10,000 to 15,000 miles per year. If you exceed this limit, you'll be charged a per-mile fee (typically $0.15 to $0.30 per mile) 
                            at the end of your lease. For example, if your lease allows 12,000 miles per year for 36 months (36,000 total miles) and you 
                            drive 40,000 miles, you'd pay for the 4,000 excess miles. It's important to estimate your annual driving accurately when signing 
                            your lease to avoid unexpected charges.
                        </p>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="border-b border-gray-200">
                    <button
                        class="faq-question w-full py-6 px-4 flex items-center justify-between text-left hover:bg-gray-50 transition-colors"
                        data-faq="4"
                    >
                        <span class="text-lg md:text-xl text-gray-900 font-medium pr-8">
                            Can I end my lease early?
                        </span>
                        <div class="flex-shrink-0">
                            <svg class="plus-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <svg class="minus-icon w-6 h-6 text-gray-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </div>
                    </button>

                    <div class="faq-answer px-4 pb-6 hidden">
                        <p class="text-gray-600 leading-relaxed">
                            Yes, you can end your lease early, but there are typically penalties involved. Early termination fees vary but usually include:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mt-4 ml-4">
                            <li>Remaining lease payments (or a portion of them)</li>
                            <li>Early termination fee (usually several months' payments)</li>
                            <li>Disposition fee</li>
                            <li>Excess wear and damage charges</li>
                            <li>Any outstanding fees or charges</li>
                        </ul>
                        <p class="text-gray-600 leading-relaxed mt-4">
                            Some leases may allow you to transfer the lease to another qualified individual. Contact us to discuss your options if you need to end your lease early.
                        </p>
                    </div>
                </div>

                <!-- FAQ 6 -->
                <div class="border-b border-gray-200">
                    <button
                        class="faq-question w-full py-6 px-4 flex items-center justify-between text-left hover:bg-gray-50 transition-colors"
                        data-faq="5"
                    >
                        <span class="text-lg md:text-xl text-gray-900 font-medium pr-8">
                            What's included in my lease payment?
                        </span>
                        <div class="flex-shrink-0">
                            <svg class="plus-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <svg class="minus-icon w-6 h-6 text-gray-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </div>
                    </button>

                    <div class="faq-answer px-4 pb-6 hidden">
                        <p class="text-gray-600 leading-relaxed">
                            Your monthly lease payment typically includes:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mt-4 ml-4">
                            <li><strong>Depreciation:</strong> The vehicle's decrease in value over the lease term</li>
                            <li><strong>Finance charge:</strong> Interest on the amount financed (money factor)</li>
                            <li><strong>Taxes:</strong> Sales tax on the monthly payment (varies by location)</li>
                            <li><strong>Fees:</strong> May include acquisition fee, documentation fees, and other administrative costs</li>
                        </ul>
                        <p class="text-gray-600 leading-relaxed mt-4">
                            Some leases include maintenance and service packages. Insurance and registration fees are typically separate and paid by you directly.
                        </p>
                    </div>
                </div>

                <!-- FAQ 7 -->
                <div class="border-b border-gray-200">
                    <button
                        class="faq-question w-full py-6 px-4 flex items-center justify-between text-left hover:bg-gray-50 transition-colors"
                        data-faq="6"
                    >
                        <span class="text-lg md:text-xl text-gray-900 font-medium pr-8">
                            What credit score do I need to lease a car?
                        </span>
                        <div class="flex-shrink-0">
                            <svg class="plus-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <svg class="minus-icon w-6 h-6 text-gray-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </div>
                    </button>

                    <div class="faq-answer px-4 pb-6 hidden">
                        <p class="text-gray-600 leading-relaxed">
                            Credit score requirements vary by lender and lease program. Generally:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mt-4 ml-4">
                            <li><strong>Excellent credit (720+):</strong> Best rates and terms available</li>
                            <li><strong>Good credit (650-719):</strong> Good rates with standard terms</li>
                            <li><strong>Fair credit (600-649):</strong> May qualify with higher down payment or interest rates</li>
                            <li><strong>Poor credit (below 600):</strong> More challenging, may require larger down payment or co-signer</li>
                        </ul>
                        <p class="text-gray-600 leading-relaxed mt-4">
                            Each lease listing on our platform shows the minimum credit score requirement. We work with multiple lenders to find options that fit various credit profiles.
                        </p>
                    </div>
                </div>

                <!-- FAQ 8 -->
                <div class="border-b border-gray-200">
                    <button
                        class="faq-question w-full py-6 px-4 flex items-center justify-between text-left hover:bg-gray-50 transition-colors"
                        data-faq="7"
                    >
                        <span class="text-lg md:text-xl text-gray-900 font-medium pr-8">
                            Are there any upfront costs when leasing?
                        </span>
                        <div class="flex-shrink-0">
                            <svg class="plus-icon w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <svg class="minus-icon w-6 h-6 text-gray-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </div>
                    </button>

                    <div class="faq-answer px-4 pb-6 hidden">
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Yes, there are typically upfront costs when starting a lease, which may include:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 ml-4">
                            <li><strong>Down Payment:</strong> Optional but recommended to reduce monthly payments (typically 10-20% of vehicle value)</li>
                            <li><strong>First Month's Payment:</strong> Due at signing</li>
                            <li><strong>Security Deposit:</strong> Refundable deposit (usually 1-2 months' payments) held as security</li>
                            <li><strong>Acquisition Fee:</strong> One-time fee charged by the leasing company (typically $500-$1,000)</li>
                            <li><strong>Title and Registration:</strong> Government fees for vehicle registration</li>
                            <li><strong>Documentation Fee:</strong> Processing fee for paperwork</li>
                            <li><strong>Taxes:</strong> Sales tax on down payment and fees (varies by state)</li>
                        </ul>
                        <p class="text-gray-600 leading-relaxed mt-4">
                            Total upfront costs can range from $1,000 to $5,000+ depending on the vehicle, down payment amount, and local taxes. 
                            Some leases offer "zero down" options, but this usually results in higher monthly payments.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

  

    <!-- FAQ JavaScript & Smooth Scrolling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // FAQ Accordion
            const faqQuestions = document.querySelectorAll('.faq-question');

            faqQuestions.forEach(function(button) {
                button.addEventListener('click', function() {
                    const answer = this.nextElementSibling;
                    const plusIcon = this.querySelector('.plus-icon');
                    const minusIcon = this.querySelector('.minus-icon');
                    
                    // Close all other answers
                    document.querySelectorAll('.faq-answer').forEach(function(otherAnswer) {
                        if (otherAnswer !== answer) {
                            otherAnswer.classList.add('hidden');
                            const otherButton = otherAnswer.previousElementSibling;
                            if (otherButton) {
                                otherButton.querySelector('.plus-icon')?.classList.remove('hidden');
                                otherButton.querySelector('.minus-icon')?.classList.add('hidden');
                            }
                        }
                    });

                    // Toggle current answer
                    if (answer.classList.contains('hidden')) {
                        answer.classList.remove('hidden');
                        plusIcon?.classList.add('hidden');
                        minusIcon?.classList.remove('hidden');
                    } else {
                        answer.classList.add('hidden');
                        plusIcon?.classList.remove('hidden');
                        minusIcon?.classList.add('hidden');
                    }
                });
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    if (href !== '#' && href.length > 1) {
                        e.preventDefault();
                        const target = document.querySelector(href);
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }
                });
            });

            // Featured Lease Carousel Scroll
            const featuredScrollContainer = document.getElementById('featuredLeaseScrollContainer');
            const featuredScrollRightBtn = document.getElementById('featuredScrollRightBtn');

            if (featuredScrollRightBtn && featuredScrollContainer) {
                featuredScrollRightBtn.addEventListener('click', function() {
                    featuredScrollContainer.scrollBy({ left: 400, behavior: 'smooth' });
                });
            }
        });
    </script>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        #featuredLeaseScrollContainer::-webkit-scrollbar {
            display: none;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-slideDown {
            animation: slideDown 0.3s ease-out;
        }
    </style>
@endsection
