@extends('layouts.customer')

@section('title', 'Vans for Sale - Find Your Perfect Van')

@section('content')
    <!-- Hero Section with Search -->
    <section class="relative bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="relative">
                <!-- Hero Image -->
                <div class="relative h-80 rounded-2xl overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1527786356703-4b100091cd2c?auto=format&fit=crop&w=2000&q=80" 
                         alt="Commercial van" 
                         class="w-full h-full object-cover">
                </div>

                <!-- Search Form Overlay -->
                <div class="absolute bottom-0 left-0 right-0 transform translate-y-1/2 px-4">
                    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-6">
                        <form action="{{ route('vans.search') }}" method="GET">
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
                                    </div>
                                </div>

                                <!-- Make Dropdown -->
                                <div class="flex-1 w-full">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Make</label>
                                    <select name="make" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent appearance-none bg-white">
                                        <option value="">Any</option>
                                        <option value="ford">Ford</option>
                                        <option value="mercedes">Mercedes-Benz</option>
                                        <option value="volkswagen">Volkswagen</option>
                                        <option value="renault">Renault</option>
                                        <option value="peugeot">Peugeot</option>
                                    </select>
                                </div>

                                <!-- Model Dropdown -->
                                <div class="flex-1 w-full">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                                    <select name="model" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent appearance-none bg-white">
                                        <option value="">Any</option>
                                    </select>
                                </div>

                                <!-- Search Button -->
                                <div class="flex items-center gap-4 w-full md:w-auto">
                                    <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-8 py-3 rounded-lg font-medium flex items-center gap-2 whitespace-nowrap">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        Search vans
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
            Discover more vans
        </h2>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <img src="https://images.unsplash.com/photo-1519003722824-194d4455a60c?auto=format&fit=crop&w=800&q=80" 
                     alt="Commercial van" 
                     class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Find your perfect van</h3>
                    <p class="text-gray-600">Browse commercial vehicles from trusted dealers</p>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <img src="https://images.unsplash.com/photo-1527786356703-4b100091cd2c?auto=format&fit=crop&w=800&q=80" 
                     alt="Van delivery" 
                     class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Expert van reviews</h3>
                    <p class="text-gray-600">Read detailed reviews for business vehicles</p>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <img src="https://images.unsplash.com/photo-1511994477422-b69e44bd4ea9?auto=format&fit=crop&w=800&q=80" 
                     alt="Van financing" 
                     class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Flexible financing</h3>
                    <p class="text-gray-600">Get competitive rates for your business</p>
                </div>
            </div>
        </div>
    </section>
@endsection

