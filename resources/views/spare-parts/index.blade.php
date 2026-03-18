@extends('layouts.customer')

@section('title', 'Spare Parts | Kibo Auto')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="relative">
                <!-- Hero Text Section -->
                <div class="relative h-80 rounded-2xl overflow-hidden">
                    <!-- Background Image -->
                    <img src="{{ asset('hero/spare/spareHero.png') }}" alt="Spare Parts" class="absolute inset-0 w-full h-full object-cover">
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-black/10"></div>
                    <!-- Text Content -->
                    <div class="relative h-full flex items-center justify-center z-10">
                        <div class="text-center text-white px-4 drop-shadow-lg">
                            <h1 class="text-5xl md:text-6xl font-bold mb-4 drop-shadow-md">Spare Parts</h1>
                            <p class="text-xl md:text-2xl text-white drop-shadow-md">Find genuine and quality spare parts for your vehicle</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Sourcing Form -->
    <section class="bg-[#f6f8f7] border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @livewire('customer.spare-part-sourcing')
        </div>
    </section>
@endsection


