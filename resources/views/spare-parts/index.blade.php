@extends('layouts.customer')

@section('title', 'Spare Parts | Kibo Auto')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-white mb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="relative">
                <!-- Hero Text Section -->
                <div class="relative h-80 rounded-2xl overflow-hidden">
                    <!-- Background Image -->
                    <img src="{{ asset('hero/spare/spareHero.png') }}" alt="Spare Parts" class="absolute inset-0 w-full h-full object-cover">
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
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        @livewire('customer.spare-part-sourcing')
    </section>
@endsection


