@extends('layouts.admin')

@section('title', 'Analytics - Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Analytics</h1>
        <p class="mt-2 text-sm text-gray-600">Comprehensive insights and statistics for your platform</p>
    </div>

    @php
        // Statistics calculations
        $totalVehicles = \App\Models\Vehicle::count();
        $activeVehicles = \App\Models\Vehicle::where('status', 'available')->count();
        $totalOrders = \App\Models\Order::count();
        $pendingOrders = \App\Models\Order::where('status', \App\Enums\OrderStatus::PENDING->value)->count();
        $completedOrders = \App\Models\Order::where('status', \App\Enums\OrderStatus::COMPLETED->value)->count();
        $totalRevenue = \App\Models\Order::where('payment_completed', true)->sum('fee');
        $totalUsers = \App\Models\User::count();
        $totalCustomers = \App\Models\Customer::count();
        $activeCustomers = \App\Models\Customer::where('status', 'active')->count();
    @endphp

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($totalRevenue, 0) }}</p>
                    <p class="text-sm text-green-600 mt-2">All time</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Vehicles -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Vehicles</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalVehicles) }}</p>
                    <p class="text-sm text-blue-600 mt-2">{{ $activeVehicles }} active</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalOrders) }}</p>
                    <p class="text-sm text-yellow-600 mt-2">{{ $pendingOrders }} pending</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalUsers) }}</p>
                    <p class="text-sm text-gray-600 mt-2">{{ $totalCustomers }} customers</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Orders Overview -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Orders Overview</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-700">Total Orders</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $totalOrders }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-700">Pending</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $pendingOrders }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-700">Completed</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $completedOrders }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-green-600 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-700">Revenue</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">TZS {{ number_format($totalRevenue, 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Statistics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Vehicle Statistics</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-700">Total Vehicles</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $totalVehicles }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-700">Active</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $activeVehicles }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-700">Inactive</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $totalVehicles - $activeVehicles }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Section -->
    <div id="reports" class="mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Reports</h2>
                <p class="text-sm text-gray-600 mt-1">Generate and view detailed reports</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button class="flex items-center justify-center px-4 py-3 border-2 border-green-500 text-green-600 rounded-lg hover:bg-green-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Sales Report
                    </button>
                    <button class="flex items-center justify-center px-4 py-3 border-2 border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                        </svg>
                        Vehicle Report
                    </button>
                    <button class="flex items-center justify-center px-4 py-3 border-2 border-purple-500 text-purple-600 rounded-lg hover:bg-purple-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        User Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Insights Section -->
    <div id="insights" class="mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Insights</h2>
                <p class="text-sm text-gray-600 mt-1">Key insights and trends</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start space-x-4 p-4 bg-green-50 rounded-lg">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Platform Growth</h3>
                            <p class="text-sm text-gray-600 mt-1">Your platform has {{ $totalUsers }} registered users and {{ $totalVehicles }} vehicles listed.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4 p-4 bg-blue-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Revenue Performance</h3>
                            <p class="text-sm text-gray-600 mt-1">Total revenue generated: TZS {{ number_format($totalRevenue, 0) }} with {{ $completedOrders }} completed orders.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4 p-4 bg-yellow-50 rounded-lg">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Pending Actions</h3>
                            <p class="text-sm text-gray-600 mt-1">You have {{ $pendingOrders }} pending orders that require attention.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

