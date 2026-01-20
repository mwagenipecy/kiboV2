@extends('layouts.customer')

@section('title', 'Spare Parts Sourcing | Kibo Auto')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-white mb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div
                class="relative h-64 md:h-72 rounded-2xl overflow-hidden bg-center bg-cover shadow"
                style="background-image: url('{{ asset('image/sparepartHero.png') }}');"
            >
                <div class="absolute inset-0 bg-black/45"></div>
                <div class="relative h-full flex flex-col justify-center items-center text-center text-white px-6">
                    <p class="text-sm uppercase tracking-[0.3em] text-gray-200 mb-2">Spare Parts</p>
                    <h1 class="text-3xl md:text-4xl font-black mb-3">Order Spare Parts</h1>
                    <p class="text-base md:text-lg text-gray-100 max-w-2xl">
                        Tell us what you need and we’ll source genuine parts for your vehicle.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sourcing Form -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        @livewire('customer.spare-part-sourcing')
    </section>
@endsection

