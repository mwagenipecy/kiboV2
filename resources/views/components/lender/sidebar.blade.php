<!-- Sidebar -->
<aside id="sidebar" class="fixed left-0 top-0 z-40 h-screen transition-all duration-300 translate-x-0 bg-white border-r border-gray-200 shadow-sm sidebar-expanded">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
            <a href="{{ route('lender.dashboard') }}" class="flex items-center sidebar-logo">
                <img src="{{ asset('logo/green.png') }}" alt="Logo" class="h-8 w-auto transition-opacity duration-300">
            </a>
            <!-- Collapse Button (all screen sizes) -->
            <button id="toggleSidebar" class="text-gray-500 hover:text-gray-700 transition-transform duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4 px-3">
            <div class="space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('lender.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('lender.dashboard') ? 'text-white bg-gradient-to-r from-green-500 to-green-600 shadow-sm' : 'text-gray-700 hover:bg-green-50 hover:text-green-700' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="menu-text">Dashboard</span>
                </a>

                <!-- Loan Requests Section -->
                <div class="pt-4 pb-2 section-title">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Financing</p>
                </div>

                <!-- Loan Requests - Expandable -->
                <div x-data="{ open: {{ request()->is('lender/requests*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors group">
                        <div class="flex items-center min-w-0">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="menu-text">Loan Requests</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('lender.requests.index') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">All Requests</a>
                        <a href="{{ route('lender.requests.pending') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">
                            Pending Review
                            @php
                                $pendingCount = 8; // Replace with actual count
                            @endphp
                            @if($pendingCount > 0)
                                <span class="ml-2 px-2 py-0.5 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('lender.requests.approved') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Approved</a>
                        <a href="{{ route('lender.requests.rejected') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors">Rejected</a>
                    </div>
                </div>

                <!-- Active Loans -->
                <a href="{{ route('lender.loans') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('lender.loans') ? 'text-white bg-gradient-to-r from-green-500 to-green-600 shadow-sm' : 'text-gray-700 hover:bg-green-50 hover:text-green-700' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="menu-text">Active Loans</span>
                </a>

                <!-- Reports Section -->
                <div class="pt-4 pb-2 section-title">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Analytics</p>
                </div>

                <!-- Portfolio -->
                <a href="{{ route('lender.portfolio') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('lender.portfolio') ? 'text-white bg-gradient-to-r from-green-500 to-green-600 shadow-sm' : 'text-gray-700 hover:bg-green-50 hover:text-green-700' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="menu-text">Portfolio</span>
                </a>

                <!-- Reports -->
                <a href="{{ route('lender.reports') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('lender.reports') ? 'text-white bg-gradient-to-r from-green-500 to-green-600 shadow-sm' : 'text-gray-700 hover:bg-green-50 hover:text-green-700' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="menu-text">Reports</span>
                </a>

                <!-- Customers -->
                <a href="{{ route('lender.customers') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('lender.customers') ? 'text-white bg-gradient-to-r from-green-500 to-green-600 shadow-sm' : 'text-gray-700 hover:bg-green-50 hover:text-green-700' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="menu-text">Customers</span>
                </a>

                <!-- Account Section -->
                <div class="pt-4 pb-2 section-title">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Account</p>
                </div>

                <!-- Profile -->
                <a href="{{ route('lender.profile') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('lender.profile') ? 'text-white bg-gradient-to-r from-green-500 to-green-600 shadow-sm' : 'text-gray-700 hover:bg-green-50 hover:text-green-700' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="menu-text">Profile</span>
                </a>

                <!-- Settings -->
                <a href="{{ route('lender.settings') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('lender.settings') ? 'text-white bg-gradient-to-r from-green-500 to-green-600 shadow-sm' : 'text-gray-700 hover:bg-green-50 hover:text-green-700' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="menu-text">Settings</span>
                </a>
            </div>
        </nav>

        <!-- User Profile Section -->
        <x-lender.user-profile />
    </div>
</aside>

