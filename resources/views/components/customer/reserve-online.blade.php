<!-- Reserve Online Section -->
<section class="bg-white py-16 px-4 sm:px-6 lg:px-8">
    @php
        $approvedCarsCount = cache()->remember(
            'kibo.approved_cars_count',
            now()->addMinutes(10),
            fn () => \App\Models\Vehicle::query()
                ->where('status', \App\Enums\VehicleStatus::APPROVED->value)
                ->count()
        );
    @endphp
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Section - Info & Search -->
            <div class="lg:col-span-4">
                <div class="lg:sticky lg:top-8">
                    <p class="text-sm font-medium text-gray-600 mb-2">
                        Reserve with Kibo Auto
                    </p>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Reserve online with Kibo Auto
                    </h2>
                    <p class="text-gray-600 mb-8">
                        Once you've found your car, build your deal to reserve online.
                    </p>
                    
                    <!-- Search Button -->
                    <a href="{{ route('cars.search') }}" class="w-full max-w-md flex items-center justify-center gap-2 px-6 py-4 border-2 border-green-600 text-green-600 rounded-full font-medium hover:bg-green-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Search {{ number_format((int) $approvedCarsCount) }} cars</span>
                    </a>
                </div>
            </div>

            <!-- Right Section - Scrollable Cards -->
            <div class="lg:col-span-8">
                <div class="relative">
                    <!-- Scroll Container -->
                    <div
                        id="reserveScrollContainer"
                        class="flex gap-6 overflow-x-auto scrollbar-hide scroll-smooth pb-4 snap-x snap-mandatory"
                        style="scrollbar-width: none; -ms-overflow-style: none;"
                    >
                        <!-- Card 1 - Build your deal -->
                        <div class="flex-none w-[340px] bg-slate-900 rounded-2xl overflow-hidden snap-start flex flex-col">
                            <!-- Image/Visual Section - Fixed Height -->
                            <div class="h-[180px] flex items-center justify-center p-6 bg-slate-900">
                                <div class="relative">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 h-16 rounded-full border-4 border-white flex items-center justify-center bg-transparent">
                                            <span class="text-3xl font-bold text-white">1</span>
                                        </div>
                                        <div class="w-8 border-t-2 border-dashed border-white"></div>
                                        <div class="w-16 h-16 rounded-full border-4 border-white flex items-center justify-center bg-transparent">
                                            <span class="text-3xl font-bold text-white">2</span>
                                        </div>
                                        <div class="w-8 border-t-2 border-dashed border-white"></div>
                                        <div class="w-16 h-16 rounded-full border-4 border-white flex items-center justify-center bg-transparent">
                                            <span class="text-3xl font-bold text-white">3</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Content Section -->
                            <div class="p-6 text-white flex-1 bg-slate-900">
                                <h3 class="text-xl font-bold mb-3 leading-tight">
                                    Build your deal, step-by-step
                                </h3>
                                <p class="text-sm leading-relaxed text-gray-300">
                                    Add part exchange, finance and choose delivery or collection. We'll guide you through it all! Then complete the sale with the seller.
                                </p>
                            </div>
                        </div>

                        <!-- Card 2 - Vehicle history check -->
                        <div class="flex-none w-[340px] bg-blue-200 rounded-2xl overflow-hidden snap-start flex flex-col">
                            <!-- Image/Visual Section - Fixed Height -->
                            <div class="h-[180px] flex items-center justify-center p-6 bg-blue-200 relative">
                                <div class="bg-white rounded-lg px-4 py-2 shadow-md absolute top-4 right-8 z-10">
                                    <span class="text-sm font-semibold text-gray-900">Reserve online</span>
                                </div>
                                <img
                                    src="https://images.unsplash.com/photo-1619405399517-d7fce0f13302?w=400&auto=format&fit=crop"
                                    alt="Car"
                                    class="h-32 object-contain"
                                />
                            </div>
                            
                            <!-- Content Section -->
                            <div class="p-6 text-gray-900 flex-1 bg-blue-200">
                                <h3 class="text-xl font-bold mb-3 leading-tight">
                                    Peace of mind with a free vehicle history check
                                </h3>
                                <p class="text-sm leading-relaxed text-gray-700">
                                    Order with confidence with our free vehicle history check to avoid costly surprises if you decide to buy.
                                </p>
                            </div>
                        </div>

                        <!-- Card 3 - Save time -->
                        <div class="flex-none w-[340px] bg-blue-200 rounded-2xl overflow-hidden snap-start flex flex-col">
                            <!-- Image/Visual Section - Fixed Height -->
                            <div class="h-[180px] flex items-center justify-center p-6 bg-blue-200">
                                <svg class="w-24 h-24 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            
                            <!-- Content Section -->
                            <div class="p-6 text-gray-900 flex-1 bg-blue-200">
                                <h3 class="text-xl font-bold mb-3 leading-tight">
                                    Save time by reserving online
                                </h3>
                                <p class="text-sm leading-relaxed text-gray-700">
                                    Get ahead of the queue and secure your car online. Once reserved, you can take your time to chat to the dealer.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Arrow - Right -->
                    <button
                        id="reserveScrollRightBtn"
                        class="hidden lg:flex absolute -right-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white rounded-full shadow-lg items-center justify-center hover:bg-gray-50 transition-colors z-10"
                        aria-label="Scroll right"
                    >
                        <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        #reserveScrollContainer::-webkit-scrollbar {
            display: none;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scrollContainer = document.getElementById('reserveScrollContainer');
            const scrollRightBtn = document.getElementById('reserveScrollRightBtn');

            if (scrollRightBtn && scrollContainer) {
                scrollRightBtn.addEventListener('click', function() {
                    scrollContainer.scrollBy({ left: 400, behavior: 'smooth' });
                });
            }
        });
    </script>
</section>

