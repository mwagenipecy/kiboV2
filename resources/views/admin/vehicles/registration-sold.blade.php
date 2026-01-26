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
            @php
                $user = auth()->user();
                $userRole = $user->role ?? null;
                $entityId = $user->entity_id ?? null;
                
                // Base query for sold count
                $soldBaseQuery = \App\Models\Vehicle::sold();
                
                // Filter by entity_id if user is not admin
                if ($userRole !== 'admin') {
                    if ($entityId) {
                        $soldBaseQuery->where('entity_id', $entityId);
                    } else {
                        $soldBaseQuery->whereRaw('1 = 0');
                    }
                }
                
                $soldCount = (clone $soldBaseQuery)->count();
                $soldThisMonth = (clone $soldBaseQuery)->whereMonth('sold_at', now()->month)->count();
                $soldThisYear = (clone $soldBaseQuery)->whereYear('sold_at', now()->year)->count();
            @endphp
            <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg font-semibold">
                {{ $soldCount }} Sold
            </div>
        </div>
    </div>

    <!-- Sales Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <p class="text-sm font-medium text-gray-600">Total Sold</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $soldCount }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <p class="text-sm font-medium text-gray-600">This Month</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">
                {{ $soldThisMonth }}
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <p class="text-sm font-medium text-gray-600">This Year</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">
                {{ $soldThisYear }}
            </p>
        </div>
    </div>

    <!-- Sold Vehicles Component -->
    @livewire('admin.vehicle-registration.sold-vehicles')
@endsection

