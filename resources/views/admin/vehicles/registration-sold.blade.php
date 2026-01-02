@extends('layouts.admin')

@section('title', 'Sold Vehicles - Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Sold Vehicles</h1>
                <p class="mt-2 text-sm text-gray-600">View all vehicles that have been sold</p>
            </div>
            <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg font-semibold">
                {{ \App\Models\Vehicle::sold()->count() }} Sold
            </div>
        </div>
    </div>

    <!-- Sales Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <p class="text-sm font-medium text-gray-600">Total Sold</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ \App\Models\Vehicle::sold()->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <p class="text-sm font-medium text-gray-600">This Month</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">
                {{ \App\Models\Vehicle::sold()->whereMonth('sold_at', now()->month)->count() }}
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <p class="text-sm font-medium text-gray-600">This Year</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">
                {{ \App\Models\Vehicle::sold()->whereYear('sold_at', now()->year)->count() }}
            </p>
        </div>
    </div>

    <!-- Sold Vehicles Component -->
    @livewire('admin.vehicle-registration.sold-vehicles')
@endsection

