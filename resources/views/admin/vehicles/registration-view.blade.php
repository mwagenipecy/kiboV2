@extends('layouts.admin')

@section('title', 'Vehicle Details - Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.vehicles.registration.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Vehicle Details</h1>
                <p class="mt-1 text-sm text-gray-600">View complete vehicle information and statistics</p>
            </div>
        </div>
    </div>

    <!-- Vehicle Detail Component -->
    @livewire('admin.vehicle-registration.vehicle-detail', ['vehicleId' => $id])
@endsection

