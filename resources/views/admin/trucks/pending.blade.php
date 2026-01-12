@extends('layouts.admin')

@section('title', 'Pending Trucks - Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Pending Approval</h1>
                <p class="mt-2 text-sm text-gray-600">Trucks awaiting approval</p>
            </div>
        </div>
    </div>

    <!-- Truck List Component with Pending Filter -->
    @livewire('admin.truck-management.truck-list', ['filterStatus' => 'pending'])
@endsection

