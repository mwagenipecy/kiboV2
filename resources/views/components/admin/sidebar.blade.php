<!-- Sidebar -->
<aside id="sidebar" class="fixed left-0 top-0 z-40 h-screen transition-all duration-300 -translate-x-full lg:translate-x-0 bg-white border-r border-gray-200 shadow-sm sidebar-expanded">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center sidebar-logo">
                <img src="{{ asset('logo/green.png') }}" alt="Logo" class="h-8 w-auto transition-opacity duration-300">
            </a>
            <!-- Desktop Collapse Button -->
            <button id="toggleSidebar" class="hidden lg:block text-gray-500 hover:text-gray-700 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>
            <!-- Mobile Close Button -->
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
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'text-white bg-gradient-to-r from-green-500 to-green-600 shadow-sm' : 'text-gray-700 hover:bg-green-50 hover:text-green-700' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="menu-text">Dashboard</span>
                </a>

                <!-- Analytics -->
                <a href="{{ route('admin.analytics') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.analytics') ? 'text-white bg-gradient-to-r from-green-500 to-green-600 shadow-sm' : 'text-gray-700 hover:bg-green-50 hover:text-green-700' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="menu-text">Analytics</span>
                </a>

                <!-- Inventory Section -->
                <div class="pt-4 pb-2 section-title">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Inventory</p>
                </div>

                <!-- Vehicles - Expandable -->
                <div x-data="{ open: {{ request()->is('admin/vehicles*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors group">
                        <div class="flex items-center min-w-0">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                            </svg>
                            <span class="menu-text">Vehicles</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.vehicles.registration.index') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">All Vehicles</a>
                        <a href="{{ route('admin.vehicles.registration.create') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Register Vehicle</a>
                        <a href="{{ route('admin.vehicles.registration.pending') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">
                            Pending Approval
                            @if(\App\Models\Vehicle::pending()->count() > 0)
                                <span class="ml-2 px-2 py-0.5 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">
                                    {{ \App\Models\Vehicle::pending()->count() }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('admin.vehicles.registration.sold') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Sold Vehicles</a>
                    </div>
                </div>

                <!-- Listings - Expandable -->
                <div x-data="{ open: {{ request()->is('admin/listings*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors group">
                        <div class="flex items-center min-w-0">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="menu-text">Listings</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.listings.active') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Active Listings</a>
                        <a href="{{ route('admin.listings.pending') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Pending</a>
                        <a href="{{ route('admin.listings.sold') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Sold</a>
                        <a href="{{ route('admin.listings.archived') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Archived</a>
                    </div>
                </div>

                <!-- Sales Section -->
                <div class="pt-4 pb-2 section-title">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Sales</p>
                </div>

                <!-- Orders - Expandable -->
                <div x-data="{ open: {{ request()->is('admin/orders*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors group">
                        <div class="flex items-center min-w-0">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span class="menu-text">Orders</span>
                            <span class="ml-auto mr-2 px-2 py-0.5 text-xs font-semibold text-green-700 bg-green-100 rounded-full menu-text">12</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.orders.index') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">All Orders</a>
                        <a href="{{ route('admin.orders.processing') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Processing</a>
                        <a href="{{ route('admin.orders.completed') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Completed</a>
                        <a href="{{ route('admin.orders.cancelled') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Cancelled</a>
                    </div>
                </div>

                <!-- Customers -->
                <a href="{{ route('admin.customers.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.customers.*') ? 'text-white bg-gradient-to-r from-green-500 to-green-600 shadow-sm' : 'text-gray-700 hover:bg-green-50 hover:text-green-700' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="menu-text">Customers</span>
                </a>

                <!-- Reviews -->
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.reviews.*') ? 'text-white bg-gradient-to-r from-green-500 to-green-600 shadow-sm' : 'text-gray-700 hover:bg-green-50 hover:text-green-700' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <span class="menu-text">Reviews</span>
                </a>

                <!-- Management Section -->
                <div class="pt-4 pb-2 section-title">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</p>
                </div>

                <!-- Registration - Expandable -->
                <div x-data="{ open: {{ request()->is('admin/registration*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors group">
                        <div class="flex items-center min-w-0">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="menu-text">Registration</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.registration.customers') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Customer Registration</a>
                        <a href="{{ route('admin.registration.cfcs') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">CFC Registration</a>
                        <a href="{{ route('admin.registration.agents') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Agent Registration</a>
                        <a href="{{ route('admin.registration.lenders') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Lender Registration</a>
                        <a href="{{ route('admin.registration.dealers') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Dealer Registration</a>
                    </div>
                </div>

                <!-- Users - Expandable -->
                <div x-data="{ open: {{ request()->is('admin/users*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors group">
                        <div class="flex items-center min-w-0">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span class="menu-text">Users</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">All Users</a>
                        <a href="{{ route('admin.users.lenders') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Lenders</a>
                        <a href="{{ route('admin.users.dealers') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Dealers</a>
                        <a href="{{ route('admin.users.admins') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Admins</a>
                        <a href="{{ route('admin.users.create') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Add User</a>
                        <a href="{{ route('admin.users.roles') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Roles</a>
                        <a href="{{ route('admin.users.permissions') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Permissions</a>
                    </div>
                </div>

                <!-- Settings - Expandable -->
                <div x-data="{ open: {{ request()->is('admin/settings*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors group">
                        <div class="flex items-center min-w-0">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="menu-text">Settings</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.settings.vehicles') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Vehicle Settings</a>
                        <a href="{{ route('admin.settings.general') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">General</a>
                        <a href="{{ route('admin.settings.security') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Security</a>
                        <a href="{{ route('admin.settings.notifications') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Notifications</a>
                        <a href="{{ route('admin.settings.billing') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Billing</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- User Profile Section -->
        <x-admin.user-profile />
    </div>
</aside>

<!-- Mobile Sidebar Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-30 lg:hidden hidden"></div>

