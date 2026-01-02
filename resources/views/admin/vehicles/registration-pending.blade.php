@extends('layouts.admin')

@section('title', 'Pending Vehicles - Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Pending Vehicles</h1>
                <p class="mt-2 text-sm text-gray-600">Review and approve vehicle registrations</p>
            </div>
            <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg font-semibold">
                {{ \App\Models\Vehicle::pending()->count() }} Pending
            </div>
        </div>
    </div>

    <!-- Pending Vehicles Component -->
    @livewire('admin.vehicle-registration.pending-vehicles')
@endsection

