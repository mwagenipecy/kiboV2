<div>
    <!-- Hero Section -->
    <section class="relative bg-white mb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="relative">
                <!-- Hero Text Section -->
                <div class="relative h-64 rounded-2xl overflow-hidden flex items-center justify-center bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('image/electricCar.png') }}');">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative text-center text-white px-4 z-10">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4">Advertising Prices</h1>
                        <p class="text-xl md:text-2xl text-white/90">Choose the perfect plan for your {{ strtolower($categoryName) }} listing</p>
                        <div class="mt-6 flex flex-wrap justify-center gap-4 text-sm md:text-base">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Transparent Pricing</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>No Hidden Fees</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Flexible Plans</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Plans -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        @if (session()->has('error'))
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm">
                {{ session('error') }}
            </div>
        @endif
        @if($plans->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($plans as $plan)
                    <div class="bg-white rounded-2xl shadow-lg border-2 {{ $plan->is_popular ? 'border-green-500 transform scale-105' : 'border-gray-200' }} transition-all hover:shadow-xl relative">
                        @if($plan->is_popular)
                            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                <span class="bg-green-600 text-white px-4 py-1 rounded-full text-sm font-semibold">Most Popular</span>
                            </div>
                        @endif
                        @if($plan->is_featured)
                            <div class="absolute top-4 right-4">
                                <span class="bg-yellow-400 text-yellow-900 px-2 py-1 rounded-full text-xs font-semibold">Featured</span>
                            </div>
                        @endif

                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                            @if($plan->description)
                                <p class="text-gray-600 mb-6">{{ $plan->description }}</p>
                            @endif

                            <div class="mb-6">
                                <div class="flex items-baseline">
                                    <span class="text-4xl font-bold text-gray-900">{{ $plan->currency }} {{ number_format($plan->price, 2) }}</span>
                                    @if($plan->duration_days)
                                        <span class="ml-2 text-gray-600">/ {{ $plan->duration_days }} days</span>
                                    @else
                                        <span class="ml-2 text-gray-600">one-time</span>
                                    @endif
                                </div>
                                @if($plan->max_listings !== null || $plan->max_trucks !== null)
                                    <p class="mt-2 text-sm font-medium text-green-700">
                                        @if($plan->max_listings !== null){{ $plan->max_listings }} {{ $plan->max_listings === 1 ? 'car' : 'cars' }}@endif
                                        @if($plan->max_listings !== null && $plan->max_trucks !== null) · @endif
                                        @if($plan->max_trucks !== null){{ $plan->max_trucks }} {{ $plan->max_trucks === 1 ? 'truck' : 'trucks' }}@endif
                                        can be listed
                                    </p>
                                @endif
                            </div>

                            @if($plan->features && count($plan->features) > 0)
                                <ul class="space-y-3 mb-8">
                                    @foreach($plan->features as $feature)
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="text-gray-700">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            @if($category === 'cars')
                                @php
                                    $isCurrentPlan = $currentPlan && $currentPlan->id === $plan->id;
                                    $isUpgrade = $currentPlan && !$isCurrentPlan && isset($currentPlanIndex) && $plans->search(fn ($p) => $p->id === $plan->id) > $currentPlanIndex;
                                @endphp
                                @if($isCurrentPlan)
                                    <div class="w-full bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg text-center cursor-default">
                                        Current plan
                                    </div>
                                @else
                                    <a href="{{ route('pricing.cars.checkout', ['plan' => $plan->id]) }}" class="block w-full text-center font-semibold py-3 px-6 rounded-lg transition-colors {{ $isUpgrade ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}">
                                        {{ $isUpgrade ? 'Upgrade to this plan' : 'Select Plan' }}
                                    </a>
                                @endif
                            @else
                                <button class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                    Select Plan
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No pricing plans available</h3>
                <p class="mt-2 text-gray-500">Pricing plans for {{ strtolower($categoryName) }} will be available soon.</p>
            </div>
        @endif
    </section>

    <!-- Additional Info Section -->
    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Why advertise with us?</h2>
                <p class="text-lg text-gray-600">Reach millions of potential buyers</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">10+ Million Visitors</h3>
                    <p class="text-gray-600">Reach the largest audience in the market</p>
                </div>

                <div class="text-center">
                    <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Fast Results</h3>
                    <p class="text-gray-600">Get inquiries within hours of listing</p>
                </div>

                <div class="text-center">
                    <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure & Trusted</h3>
                    <p class="text-gray-600">Safe transactions with verified buyers</p>
                </div>
            </div>
        </div>
    </section>
</div>
