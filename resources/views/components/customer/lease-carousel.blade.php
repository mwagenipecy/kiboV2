<!-- Lease Carousel Section -->
<section class="bg-white py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Section Title -->
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12">
            Lease a brand new car
        </h2>

        @if($leases->count() > 0)
        <!-- Carousel Container -->
        <div class="relative">
            <!-- Scroll Container -->
            <div
                id="leaseScrollContainer"
                class="flex gap-6 overflow-x-auto scrollbar-hide scroll-smooth pb-4 snap-x snap-mandatory"
                style="scrollbar-width: none; -ms-overflow-style: none;"
            >
                @foreach($leases as $lease)
                <!-- Deal Card -->
                <a href="{{ route('cars.lease.detail', $lease->id) }}" class="flex-none w-[320px] bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 snap-start block">
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
                                <span class="text-4xl font-bold text-green-600">${{ number_format($lease->monthly_payment, 0) }}</span>
                                <div class="text-xs text-gray-600 ml-auto text-right">
                                    <div class="font-semibold">${{ number_format($lease->total_upfront_cost, 0) }} initial payment</div>
                                    <div>{{ $lease->lease_term_months }} month contract</div>
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
                        <div class="text-sm font-medium text-green-600 mb-4 pb-4 border-b border-gray-200">
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
                id="scrollRightBtn"
                class="hidden lg:flex absolute -right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white rounded-full shadow-lg items-center justify-center hover:bg-gray-50 transition-colors z-10"
                aria-label="Scroll right"
            >
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

            <!-- Navigation Arrow - Right -->
            <button
                id="scrollRightBtn"
                class="hidden lg:flex absolute -right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white rounded-full shadow-lg items-center justify-center hover:bg-gray-50 transition-colors z-10"
                aria-label="Scroll right"
            >
                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-600">No featured lease vehicles available at the moment.</p>
            <a href="{{ route('cars.lease.index') }}" class="inline-block mt-4 text-green-600 hover:text-green-700 font-semibold">
                Browse all lease vehicles →
            </a>
        </div>
        @endif

        <!-- View More Link -->
        @if($leases->count() > 0)
        <div class="text-center mt-8">
            <a
                href="{{ route('cars.lease.index') }}"
                class="inline-flex items-center gap-2 text-gray-900 font-semibold hover:gap-3 transition-all duration-200 group"
            >
                <span class="border-b-2 border-gray-900">View more deals</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
        @endif
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        #leaseScrollContainer::-webkit-scrollbar {
            display: none;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scrollContainer = document.getElementById('leaseScrollContainer');
            const scrollRightBtn = document.getElementById('scrollRightBtn');

            if (scrollRightBtn && scrollContainer) {
                scrollRightBtn.addEventListener('click', function() {
                    scrollContainer.scrollBy({ left: 400, behavior: 'smooth' });
                });
            }
        });
    </script>
</section>

