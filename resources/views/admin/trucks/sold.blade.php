@extends('layouts.admin')

@section('title', 'Sold Trucks - Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Sold Trucks</h1>
                <p class="mt-2 text-sm text-gray-600">Trucks that have been sold</p>
            </div>
        </div>
    </div>

    <!-- Truck List Component with Sold Filter -->
    @livewire('admin.truck-management.truck-list', ['filterStatus' => 'sold'])
@endsection

