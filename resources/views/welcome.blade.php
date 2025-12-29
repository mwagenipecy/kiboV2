@extends('layouts.customer')

@section('title', 'Find Your Perfect Car')

@section('content')
    <!-- Hero Section with Search -->
    <section class="relative bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="relative">
                <!-- Hero Image -->
                <div class="relative h-80 rounded-2xl overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=2000&q=80" 
                         alt="Person with dog and car" 
                         class="w-full h-full object-cover">
                </div>

                <!-- Search Form Overlay -->
                <div class="absolute bottom-0 left-0 right-0 transform translate-y-1/2 px-4">
                    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-6">
                        <form action="#" method="GET">
                            <div class="flex flex-col md:flex-row items-end gap-4">
                                <!-- Postcode Input -->
                                <div class="flex-1 w-full">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Postcode <span class="text-red-600">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               name="postcode"
                                               placeholder="Postcode" 
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Make Dropdown -->
                                <div class="flex-1 w-full">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Make</label>
                                    <select name="make" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent appearance-none bg-white">
                                        <option value="">Any</option>
                                        <option value="audi">Audi</option>
                                        <option value="bmw">BMW</option>
                                        <option value="ford">Ford</option>
                                        <option value="honda">Honda</option>
                                        <option value="mercedes">Mercedes-Benz</option>
                                        <option value="nissan">Nissan</option>
                                        <option value="toyota">Toyota</option>
                                        <option value="volkswagen">Volkswagen</option>
                                    </select>
                                </div>

                                <!-- Model Dropdown -->
                                <div class="flex-1 w-full">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                                    <select name="model" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent appearance-none bg-white">
                                        <option value="">Any</option>
                                    </select>
                                </div>

                                <!-- More Options & Search Button -->
                                <div class="flex items-center gap-4 w-full md:w-auto">
                                    <button type="button" class="text-green-700 font-medium hover:underline whitespace-nowrap">
                                        More options
                                    </button>
                                    <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-8 py-3 rounded-lg font-medium flex items-center gap-2 whitespace-nowrap">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        Search 451,499 cars
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h2 class="text-4xl font-bold text-gray-900 text-center mb-12">
            Discover more cars
        </h2>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&w=800&q=80" 
                     alt="People in car" 
                     class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Find your perfect car</h3>
                    <p class="text-gray-600">Browse thousands of vehicles from trusted dealers</p>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <img src="https://images.unsplash.com/photo-1560179707-f14e90ef3623?auto=format&fit=crop&w=800&q=80" 
                     alt="Car interior" 
                     class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Expert car reviews</h3>
                    <p class="text-gray-600">Read detailed reviews to make informed decisions</p>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <img src="https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?auto=format&fit=crop&w=800&q=80" 
                     alt="Mobile notification" 
                     class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Get instant alerts</h3>
                    <p class="text-gray-600">Never miss out on your dream car with notifications</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Features Section -->
    <section class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <!-- Feature 1 -->
                <div>
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Verified Dealers</h3>
                    <p class="text-gray-600">Buy with confidence from trusted sellers</p>
                </div>

                <!-- Feature 2 -->
                <div>
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Best Prices</h3>
                    <p class="text-gray-600">Compare prices and find great deals</p>
                </div>

                <!-- Feature 3 -->
                <div>
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Quick & Easy</h3>
                    <p class="text-gray-600">Find and buy your car in minutes</p>
                </div>

                <!-- Feature 4 -->
                <div>
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">24/7 Support</h3>
                    <p class="text-gray-600">We're here to help anytime you need</p>
                </div>
            </div>
        </div>
    </section>
@endsection
