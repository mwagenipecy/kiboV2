@extends('layouts.customer')

@section('title', 'Used Cars for Sale | Kibo Auto')

@section('content')
    <!-- Hero Section with Search -->
    <section class="relative bg-white mb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="relative">
                <!-- Hero Text Section (Instead of Image) -->
                <div class="relative h-80 rounded-2xl overflow-hidden bg-black flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        <h1 class="text-5xl md:text-6xl font-bold mb-4">Used cars</h1>
                        <p class="text-xl md:text-2xl text-white">Meet your perfect car</p>
                    </div>
                </div>

                <!-- Search Form Overlay -->
                <div class="absolute bottom-0 left-0 right-0 transform translate-y-1/2 px-4">
                    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-6">
                        <form action="{{ route('cars.search') }}" method="GET">
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
                                        Search used cars
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

