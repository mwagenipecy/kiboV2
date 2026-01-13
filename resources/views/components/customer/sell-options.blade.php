<!-- Wrapper Section -->
<section class="relative bg-white mb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="relative">
            <!-- Dark background section with rounded corners -->
            <div class="bg-gradient-to-b from-slate-900 to-slate-800 rounded-2xl pb-64 px-4">
                <div class="max-w-6xl mx-auto pt-16">
                    <!-- Header Section -->
                    <div class="text-center mb-12">
                        <p class="text-white text-sm mb-3 font-medium">Sell your car</p>
                        <h1 class="text-white text-4xl md:text-5xl font-bold">
                            More buyers than any other site*
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Cards Container with negative margin to overlap -->
            <div class="max-w-6xl mx-auto px-4 -mt-56 mb-16">
    <div class="grid md:grid-cols-2 gap-6 max-w-5xl mx-auto">
        <!-- Advertise on Kibo Auto Card -->
        <div class="bg-white rounded-2xl p-8 shadow-lg">
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-8">
                Advertise on Kibo Auto
            </h2>
            
            <div class="space-y-4 mb-8">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="text-slate-700">Maximise your selling price</p>
                </div>
                
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="text-slate-700">Advertise to over 10 million people each month—4x more than any other site*</p>
                </div>
                
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="text-slate-700">Your sale, your terms. Sell when you're happy with the offer</p>
                </div>
            </div>
            
            <div class="flex flex-col items-center gap-4">
                <button class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-full transition-colors">
                    Start an advert
                </button>
                @php
                    // Determine category based on current route
                    $category = 'cars';
                    if (request()->routeIs('trucks.*')) {
                        $category = 'trucks';
                    } elseif (request()->routeIs('garage.*')) {
                        $category = 'garage';
                    } elseif (request()->routeIs('cars.*')) {
                        $category = 'cars';
                    }
                @endphp
                <a href="{{ route('pricing.show', ['category' => $category]) }}" class="text-green-600 hover:text-green-700 font-medium underline text-sm">
                    See advertising prices
                </a>
            </div>
        </div>

        <!-- Sell Fast for Free Card -->
        <div class="bg-white rounded-2xl p-8 shadow-lg">
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-8">
                Sell fast for free
            </h2>
            
            <div class="space-y-4 mb-8">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="text-slate-700">Sell in as little as 48 hours**</p>
                </div>
                
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="text-slate-700">Get the best price from thousands of verified dealers</p>
                </div>
                
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="text-slate-700">Free collection and same-day payment</p>
                </div>
            </div>
            
            <div class="flex flex-col items-center gap-4">
                <button class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-full transition-colors">
                    Sell for free
                </button>
                <a href="#" class="text-green-600 hover:text-green-700 font-medium underline text-sm">
                    Learn more about selling to a dealer
                </a>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</section>

