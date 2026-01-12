@extends('layouts.customer')

@section('title', 'Truck Insurance Calculator | Kibo Auto')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-white mb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="relative">
                <!-- Hero Text Section -->
                <div class="relative h-80 rounded-2xl overflow-hidden bg-black flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        <h1 class="text-5xl md:text-6xl font-bold mb-4">Truck Insurance</h1>
                        <p class="text-xl md:text-2xl text-gray-300">Calculate your truck insurance premium instantly</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Insurance Calculator -->
    <div class="bg-gray-50 py-8">
        @livewire('customer.vehicle-insurance-calculator', ['vehicleId' => request('truck_id'), 'vehicleType' => 'truck'])
    </div>
@endsection

