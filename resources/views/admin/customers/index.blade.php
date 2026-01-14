@extends('layouts.admin')

@section('title', 'Customers - Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Customers</h1>
        <p class="mt-2 text-sm text-gray-600">Manage and view all customer accounts</p>
    </div>

    @php
        $totalCustomers = \App\Models\Customer::count();
        $activeCustomers = \App\Models\Customer::where('status', 'active')->count();
        $inactiveCustomers = \App\Models\Customer::where('status', 'inactive')->count();
        $pendingCustomers = \App\Models\Customer::where('approval_status', 'pending')->count();
        $newThisMonth = \App\Models\Customer::whereMonth('created_at', now()->month)->count();
    @endphp

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Customers -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Customers</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalCustomers) }}</p>
                    <p class="text-sm text-gray-500 mt-2">All time</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Customers -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Customers</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($activeCustomers) }}</p>
                    <p class="text-sm text-green-600 mt-2">{{ $totalCustomers > 0 ? round(($activeCustomers / $totalCustomers) * 100, 1) : 0 }}% of total</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Inactive Customers -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Inactive Customers</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($inactiveCustomers) }}</p>
                    <p class="text-sm text-gray-500 mt-2">{{ $totalCustomers > 0 ? round(($inactiveCustomers / $totalCustomers) * 100, 1) : 0 }}% of total</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- New This Month -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">New This Month</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($newThisMonth) }}</p>
                    <p class="text-sm text-purple-600 mt-2">Recent registrations</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <a href="{{ route('admin.customers.index') }}" class="py-4 px-6 text-sm font-medium border-b-2 {{ request()->get('filter') != 'inactive' && request()->get('filter') != 'pending' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    All Customers
                    <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">{{ $totalCustomers }}</span>
                </a>
                <a href="{{ route('admin.customers.index') }}?filter=active" id="active" class="py-4 px-6 text-sm font-medium border-b-2 {{ request()->get('filter') == 'active' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Active
                    <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">{{ $activeCustomers }}</span>
                </a>
                <a href="{{ route('admin.customers.index') }}?filter=inactive" id="inactive" class="py-4 px-6 text-sm font-medium border-b-2 {{ request()->get('filter') == 'inactive' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Inactive
                    <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">{{ $inactiveCustomers }}</span>
                </a>
                @if($pendingCustomers > 0)
                <a href="{{ route('admin.customers.index') }}?filter=pending" class="py-4 px-6 text-sm font-medium border-b-2 {{ request()->get('filter') == 'pending' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Pending Approval
                    <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">{{ $pendingCustomers }}</span>
                </a>
                @endif
            </nav>
        </div>
    </div>

    <!-- Customer List Component -->
    @livewire('admin.registration.customer-list')
@endsection

