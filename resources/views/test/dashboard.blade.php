<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Autotrader Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

          <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>
<body class="bg-gray-50">
    
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-0 z-40 h-screen w-64 transition-transform -translate-x-full lg:translate-x-0 bg-white border-r border-gray-200 shadow-sm">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Autotrader</span>
                </a>
                <button id="closeSidebar" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 px-3">
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-green-500 to-green-600 shadow-sm">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    <!-- Analytics -->
                    <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Analytics
                    </a>

                    <!-- Inventory Section -->
                    <div class="pt-4 pb-2">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Inventory</p>
                    </div>

                    <!-- Vehicles - Expandable -->
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                </svg>
                                Vehicles
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="ml-8 mt-1 space-y-1">
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">All Vehicles</a>
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Add New</a>
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Categories</a>
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Brands</a>
                        </div>
                    </div>

                    <!-- Listings - Expandable -->
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Listings
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="ml-8 mt-1 space-y-1">
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Active Listings</a>
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Pending</a>
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Sold</a>
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Archived</a>
                        </div>
                    </div>

                    <!-- Sales Section -->
                    <div class="pt-4 pb-2">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Sales</p>
                    </div>

                    <!-- Orders - Expandable -->
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                Orders
                                <span class="ml-auto mr-2 px-2 py-0.5 text-xs font-semibold text-green-700 bg-green-100 rounded-full">12</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="ml-8 mt-1 space-y-1">
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">All Orders</a>
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Processing</a>
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Completed</a>
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Cancelled</a>
                        </div>
                    </div>

                    <!-- Customers -->
                    <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Customers
                    </a>

                    <!-- Reviews -->
                    <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        Reviews
                    </a>

                    <!-- Management Section -->
                    <div class="pt-4 pb-2">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</p>
                    </div>

                    <!-- Users - Expandable -->
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                Users
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="ml-8 mt-1 space-y-1">
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">All Users</a>
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Add User</a>
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Roles</a>
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Permissions</a>
                        </div>
                    </div>

                    <!-- Settings - Expandable -->
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Settings
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="ml-8 mt-1 space-y-1">
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">General</a>
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Security</a>
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Notifications</a>
                            <a href="#" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Billing</a>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- User Profile Section -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center space-x-3">
                    <img src="https://ui-avatars.com/api/?name=John+Doe&background=10b981&color=fff" alt="User" class="w-10 h-10 rounded-full">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">John Doe</p>
                        <p class="text-xs text-gray-500 truncate">john@autotrader.com</p>
                    </div>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-30 lg:hidden hidden"></div>

    <!-- Main Content Area -->
    <div class="lg:ml-64">
        <!-- Top Navigation Bar -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-20">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Mobile Menu Button & Search -->
                    <div class="flex items-center flex-1">
                        <button id="openSidebar" class="lg:hidden text-gray-500 hover:text-gray-700 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        <!-- Search Bar -->
                        <div class="relative w-full max-w-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="search" placeholder="Search vehicles, customers, orders..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                        </div>
                    </div>

                    <!-- Right Side Icons -->
                    <div class="flex items-center space-x-4">
                        <!-- Quick Add -->
                        <button class="hidden sm:flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Vehicle
                        </button>

                        <!-- Notifications -->
                        <div class="relative">
                            <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 relative">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-green-500 rounded-full"></span>
                            </button>
                        </div>

                        <!-- Messages -->
                        <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </button>

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100">
                                <img src="https://ui-avatars.com/api/?name=John+Doe&background=10b981&color=fff" alt="User" class="w-8 h-8 rounded-full">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-gray-200">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50">Profile</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50">Settings</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50">Billing</a>
                                <hr class="my-1">
                                <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 sm:p-6 lg:p-8">
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
        </main>
    </div>

    <script>
        // Mobile Sidebar Toggle
        document.getElementById('openSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.remove('hidden');
        });

        document.getElementById('closeSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.add('hidden');
        });

        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.add('hidden');
        });
    </script>
</body>
</html>