<!-- Discovery Section -->
<section class="bg-white py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Section Title -->
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12">
            Discover more from Kibo Auto cars
        </h2>

        <!-- Cards Grid - First card takes half, other two share remaining half on same row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Card 1 - Leasing (Takes 50% - 2 columns) -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 md:col-span-2">
                <!-- Card Image -->
                <div class="h-64 md:h-80 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=800&auto=format&fit=crop" 
                         alt="Leasing you can trust" 
                         class="w-full h-full object-cover">
                </div>

                <!-- Card Content -->
                <div class="p-6 space-y-4">
                    <h3 class="text-xl md:text-2xl font-bold text-gray-900 leading-tight">
                        Leasing you can trust, now with Kibo Auto
                    </h3>
                    
                    <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                        The price you see is the price you get - no admin fees added on.
                    </p>

                    <div class="pt-2">
                        <a href="{{ route('cars.leasing') }}" 
                           class="inline-block px-6 py-3 border-2 border-green-700 text-green-700 rounded-full font-medium hover:bg-green-50 transition-colors duration-200">
                            Find your lease
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2 - Sell Your Car (Takes 25% - 1 column) -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 md:col-span-1">
                <!-- Card Image -->
                <div class="h-64 md:h-80 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&auto=format&fit=crop" 
                         alt="Sell your car" 
                         class="w-full h-full object-cover">
                </div>

                <!-- Card Content -->
                <div class="p-6 space-y-4">
                    <h3 class="text-xl md:text-2xl font-bold text-gray-900 leading-tight">
                        Sell your car, your way
                    </h3>
                    
                    <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                        Get a free, instant valuation in seconds and choose the best way to sell.
                    </p>

                    <div class="pt-2">
                        <a href="{{ route('cars.sell') }}" 
                           class="inline-block px-6 py-3 border-2 border-green-700 text-green-700 rounded-full font-medium hover:bg-green-50 transition-colors duration-200">
                            Sell your car
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 3 - Sign In (Takes 25% - 1 column) -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 md:col-span-1">
                <!-- Card Image -->
                <div class="h-64 md:h-80 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=800&auto=format&fit=crop" 
                         alt="Get the full experience" 
                         class="w-full h-full object-cover">
                </div>

                <!-- Card Content -->
                <div class="p-6 space-y-4">
                    <h3 class="text-xl md:text-2xl font-bold text-gray-900 leading-tight">
                        Get the full experience
                    </h3>
                    
                    <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                        See your saved cars, track progress and pick up right where you left off.
                    </p>

                    <div class="pt-2">
                        @auth
                            <a href="{{ route('dashboard') }}" 
                               class="inline-block px-6 py-3 border-2 border-green-700 text-green-700 rounded-full font-medium hover:bg-green-50 transition-colors duration-200">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="inline-block px-6 py-3 border-2 border-green-700 text-green-700 rounded-full font-medium hover:bg-green-50 transition-colors duration-200">
                                Sign in
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

