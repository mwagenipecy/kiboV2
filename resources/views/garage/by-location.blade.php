@extends('layouts.customer')

@section('title', 'Garages by Location | Kibo Auto')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-white mb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="relative">
                <!-- Hero Text Section -->
                <div class="relative h-64 rounded-2xl overflow-hidden bg-gradient-to-r from-green-600 to-green-800 flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        <h1 class="text-4xl md:text-5xl font-bold mb-4">Garages by Location</h1>
                        <p class="text-xl text-white">Find trusted garages near you</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Garage Search Results -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        @livewire('customer.garage-search')
    </section>
@endsection

