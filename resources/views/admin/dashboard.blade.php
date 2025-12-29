@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-2 text-sm text-gray-600">Welcome back! Here's what's happening today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Vehicles -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Vehicles</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">1,234</p>
                    <p class="text-sm text-green-600 mt-2">↑ 12% from last month</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Listings -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Listings</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">856</p>
                    <p class="text-sm text-green-600 mt-2">↑ 8% from last month</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">342</p>
                    <p class="text-sm text-red-600 mt-2">↓ 3% from last month</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">$45.2K</p>
                    <p class="text-sm text-green-600 mt-2">↑ 18% from last month</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activity -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Vehicles</h2>
                <div class="space-y-4">
                    <!-- Vehicle Item -->
                    <div class="flex items-center space-x-4 p-4 rounded-lg hover:bg-gray-50 transition-colors">
                        <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=200&q=80" alt="Car" class="w-20 h-20 rounded-lg object-cover">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">2024 Tesla Model 3</h3>
                            <p class="text-sm text-gray-600">Electric • 5,000 miles</p>
                            <p class="text-sm font-semibold text-green-600 mt-1">$42,500</p>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>
                    </div>

                    <!-- Vehicle Item -->
                    <div class="flex items-center space-x-4 p-4 rounded-lg hover:bg-gray-50 transition-colors">
                        <img src="https://images.unsplash.com/photo-1617531653332-bd46c24f2068?auto=format&fit=crop&w=200&q=80" alt="Car" class="w-20 h-20 rounded-lg object-cover">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">2023 BMW X5</h3>
                            <p class="text-sm text-gray-600">SUV • 12,000 miles</p>
                            <p class="text-sm font-semibold text-green-600 mt-1">$58,900</p>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Pending</span>
                    </div>

                    <!-- Vehicle Item -->
                    <div class="flex items-center space-x-4 p-4 rounded-lg hover:bg-gray-50 transition-colors">
                        <img src="https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?auto=format&fit=crop&w=200&q=80" alt="Car" class="w-20 h-20 rounded-lg object-cover">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">2024 Audi A4</h3>
                            <p class="text-sm text-gray-600">Sedan • 8,500 miles</p>
                            <p class="text-sm font-semibold text-green-600 mt-1">$38,750</p>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    <button class="w-full flex items-center px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add New Vehicle
                    </button>
                    <button class="w-full flex items-center px-4 py-3 border-2 border-green-500 text-green-600 rounded-lg hover:bg-green-50 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Add Customer
                    </button>
                    <button class="w-full flex items-center px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        View Reports
                    </button>
                </div>

                <!-- Recent Notifications -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Recent Notifications</h3>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm text-gray-900">New order received</p>
                                <p class="text-xs text-gray-500">2 minutes ago</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm text-gray-900">Vehicle listing approved</p>
                                <p class="text-xs text-gray-500">1 hour ago</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm text-gray-900">Customer review posted</p>
                                <p class="text-xs text-gray-500">3 hours ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

