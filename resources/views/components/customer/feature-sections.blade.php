<!-- Feature Sections -->
<section class="bg-white py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-16">
        
        {!! $slot !!}

        <!-- Part Exchange Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            <!-- Text Content - Left -->
            <div class="order-2 lg:order-1">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                    Have a car to part-exchange?
                </h2>
                <p class="text-gray-700 text-lg mb-4">
                    Join the millions who value their car with Kibo Auto.
                </p>
                <p class="text-gray-700 text-lg mb-8">
                    Get a free, instant valuation so you know exactly how much you can put towards your next car.
                </p>
                <a
                    href="{{ route('cars.value') }}"
                    class="inline-block px-8 py-3 border-2 border-green-700 text-green-700 rounded-full font-medium hover:bg-green-50 transition-colors"
                >
                    Value my car
                </a>
            </div>

            <!-- Image - Right -->
            <div class="order-1 lg:order-2">
                <div class="rounded-2xl overflow-hidden shadow-lg">
                    <img
                        src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=1200&auto=format&fit=crop"
                        alt="Couple looking at car"
                        class="w-full h-full object-cover aspect-[4/3]"
                    />
                </div>
            </div>
        </div>

        <!-- Save Time Researching Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            <!-- Image - Left -->
            <div>
                <div class="rounded-2xl overflow-hidden shadow-lg">
                    <img
                        src="https://images.unsplash.com/photo-1551434678-e076c223a692?w=1200&auto=format&fit=crop"
                        alt="Person using smartphone"
                        class="w-full h-full object-cover aspect-[4/3]"
                    />
                </div>
            </div>

            <!-- Text Content - Right -->
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                    Save time researching
                </h2>
                <p class="text-gray-700 text-lg mb-8">
                    Narrow down your options with help from our experts. They review all makes and models to help you find the right car.
                </p>
                <a
                    href="{{ route('cars.reviews') }}"
                    class="inline-block px-8 py-3 border-2 border-green-700 text-green-700 rounded-full font-medium hover:bg-green-50 transition-colors"
                >
                    Read reviews
                </a>
            </div>
        </div>

    </div>
</section>

