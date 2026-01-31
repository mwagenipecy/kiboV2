<style>
    .kibo-sidebar-active { background: linear-gradient(to right, #009866, #007a52) !important; }
    .kibo-sidebar-hover:hover { background-color: rgba(0, 152, 102, 0.1) !important; color: #009866 !important; }
    .kibo-badge { color: #007a52 !important; background-color: rgba(0, 152, 102, 0.15) !important; }
</style>
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
            @php
                $userRole = auth()->user()->role ?? null;
                $userEntityId = auth()->user()->entity_id ?? null;
                $isAdmin = $userRole === 'admin';
            @endphp
            <div class="space-y-1">
                <!-- Dashboard (All roles) -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'text-white kibo-sidebar-active shadow-sm' : 'text-gray-700 kibo-sidebar-hover' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="menu-text whitespace-nowrap">Dashboard</span>
                </a>

                <!-- Analytics - Expandable (Admin only) -->
                @if($userRole === 'admin')
                <div x-data="{ open: {{ request()->is('admin/analytics*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->is('admin/analytics*') ? 'text-white kibo-sidebar-active shadow-sm' : 'text-gray-700 kibo-sidebar-hover' }} transition-colors group">
                        <div class="flex items-center min-w-0">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span class="menu-text whitespace-nowrap">Analytics</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.analytics') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Overview</a>
                        <a href="{{ route('admin.analytics') }}#reports" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Reports</a>
                        <a href="{{ route('admin.analytics') }}#insights" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Insights</a>
                    </div>
                </div>
                @endif

                <!-- Inventory Section (Admin and Dealer) -->
                @if($userRole === 'admin' || $userRole === 'dealer')
                <div class="pt-4 pb-2 section-title">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Inventory</p>
                </div>

                <!-- Vehicles - Expandable (Admin and Dealer) -->
                @if($userRole === 'admin' || $userRole === 'dealer')
                <div x-data="{ open: {{ request()->is('admin/vehicles*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg kibo-sidebar-hover transition-colors group">
                        <div class="flex items-center min-w-0">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                            </svg>
                            <span class="menu-text whitespace-nowrap">Vehicles</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.vehicles.registration.index') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">All Vehicles</a>
                        <a href="{{ route('admin.vehicles.registration.create') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Register Vehicle</a>
                        <a href="{{ route('admin.vehicles.registration.pending') }}" class="flex items-center justify-between px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">
                            <span class="whitespace-nowrap">Pending Approval</span>
                            @php
                                $pendingVehiclesCount = $isAdmin 
                                    ? \App\Models\Vehicle::pending()->count()
                                    : \App\Models\Vehicle::pending()->where('entity_id', $userEntityId)->count();
                            @endphp
                            @if($pendingVehiclesCount > 0)
                                <span class="ml-2 px-2 py-0.5 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full whitespace-nowrap flex-shrink-0">
                                    {{ $pendingVehiclesCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('admin.vehicles.registration.sold') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Sold Vehicles</a>
                    </div>
                </div>
                @endif

                <!-- Vehicle Leasing - Expandable (Admin and Dealer) -->
                @if($userRole === 'admin' || $userRole === 'dealer')
                <div x-data="{ open: {{ request()->is('admin/leasing*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg kibo-sidebar-hover transition-colors group">
                        <div class="flex items-center min-w-0">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="menu-text whitespace-nowrap">Vehicle Leasing</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.leasing.index') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">All Leases</a>
                        <a href="{{ route('admin.leasing.create') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Register Lease Vehicle</a>
                    </div>
                </div>
                @endif

                <!-- Trucks - Expandable (Admin and Dealer) -->
                @if($userRole === 'admin' || $userRole === 'dealer')
                <div x-data="{ open: {{ request()->is('admin/trucks*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg kibo-sidebar-hover transition-colors group">
                        <div class="flex items-center min-w-0">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span class="menu-text whitespace-nowrap">Trucks</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.trucks.index') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">All Trucks</a>
                        <a href="{{ route('admin.trucks.create') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Register Truck</a>
                        <a href="{{ route('admin.trucks.pending') }}" class="flex items-center justify-between px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">
                            <span class="whitespace-nowrap">Pending Approval</span>
                            @php
                                $pendingTrucksQuery = \App\Models\Truck::where('status', 'pending');
                                if (!$isAdmin && $userEntityId) {
                                    $pendingTrucksQuery->where('entity_id', $userEntityId);
                                }
                                $pendingTrucksCount = $pendingTrucksQuery->count();
                            @endphp
                            @if($pendingTrucksCount > 0)
                                <span class="ml-2 px-2 py-0.5 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full whitespace-nowrap flex-shrink-0">
                                    {{ $pendingTrucksCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('admin.trucks.sold') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Sold Trucks</a>
                    </div>
                </div>
                @endif
                @endif

                <!-- Orders Section -->
                <div class="pt-4 pb-2 section-title">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Orders</p>
                </div>

                <!-- Evaluation Orders - Expandable (Admin and Agent) -->
                @if($userRole === 'admin' || $userRole === 'agent')
                <div x-data="{ open: {{ request()->is('admin/orders/evaluations*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg kibo-sidebar-hover transition-colors group">
                        <div class="flex items-center min-w-0 flex-1">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="menu-text whitespace-nowrap">Evaluation Orders</span>
                            @php
                                $evaluationQuery = \App\Models\Order::where('order_type', \App\Enums\OrderType::VALUATION_REPORT->value)
                                    ->whereIn('status', [\App\Enums\OrderStatus::PENDING->value, \App\Enums\OrderStatus::PROCESSING->value]);
                                if (!$isAdmin && $userEntityId) {
                                    $evaluationQuery->whereHas('vehicle', function($q) use ($userEntityId) {
                                        $q->where('entity_id', $userEntityId);
                                    });
                                }
                                $evaluationCount = $evaluationQuery->count();
                            @endphp
                            @if($evaluationCount > 0)
                            <span class="ml-2 px-2 py-0.5 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full whitespace-nowrap flex-shrink-0">{{ $evaluationCount }}</span>
                            @endif
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.orders.evaluations.index') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">All Evaluations</a>
                        <a href="{{ route('admin.orders.evaluations.pending-payment') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Pending Payment</a>
                        <a href="{{ route('admin.orders.evaluations.paid') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Paid - Issue Report</a>
                        <a href="{{ route('admin.orders.evaluations.completed') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Completed</a>
                    </div>
                </div>
                @endif

                <!-- Cash Purchase Orders - Expandable (Admin and Dealer) -->
                @if($userRole === 'admin' || $userRole === 'dealer')
                <div x-data="{ open: {{ request()->is('admin/orders/cash*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg kibo-sidebar-hover transition-colors group">
                        <div class="flex items-center min-w-0 flex-1">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <span class="menu-text whitespace-nowrap">Cash Purchase</span>
                            @php
                                $cashPendingQuery = \App\Models\Order::where('order_type', \App\Enums\OrderType::CASH_PURCHASE->value)
                                    ->where('status', \App\Enums\OrderStatus::PENDING->value);
                                if (!$isAdmin && $userEntityId) {
                                    $cashPendingQuery->whereHas('vehicle', function($q) use ($userEntityId) {
                                        $q->where('entity_id', $userEntityId);
                                    });
                                }
                                $cashPendingCount = $cashPendingQuery->count();
                            @endphp
                            @if($cashPendingCount > 0)
                            <span class="ml-2 px-2 py-0.5 text-xs font-semibold kibo-badge rounded-full whitespace-nowrap flex-shrink-0">{{ $cashPendingCount }}</span>
                            @endif
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.orders.cash.index') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">All Cash Orders</a>
                        <a href="{{ route('admin.orders.cash.pending') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Pending</a>
                        <a href="{{ route('admin.orders.cash.approved') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Approved</a>
                        <a href="{{ route('admin.orders.cash.completed') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Completed</a>
                        <a href="{{ route('admin.orders.cash.rejected') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Rejected</a>
                    </div>
                </div>
                @endif

                <!-- Financing Applications - Expandable (Admin, Dealer, and Lender) -->
                @if($userRole === 'admin' || $userRole === 'dealer' || $userRole === 'lender')
                <div x-data="{ open: {{ request()->is('admin/orders/financing*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg kibo-sidebar-hover transition-colors group">
                        <div class="flex items-center min-w-0 flex-1">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="menu-text whitespace-nowrap">Financing Applications</span>
                            @php
                                if ($userRole === 'lender' && !$isAdmin && $userEntityId) {
                                    // For lenders, show dealer-approved applications awaiting their review
                                    $financingPendingQuery = \App\Models\Order::where('order_type', \App\Enums\OrderType::FINANCING_APPLICATION->value)
                                        ->where('status', \App\Enums\OrderStatus::APPROVED->value) // Dealer approved
                                        ->whereJsonContains('order_data->lender_entity_id', $userEntityId)
                                        ->where(function($q) {
                                            $q->whereJsonContains('order_data->lender_approval', 'pending')
                                              ->orWhereNull('order_data->lender_approval');
                                        });
                                    $financingPendingCount = $financingPendingQuery->count();
                                } else {
                                    $financingPendingQuery = \App\Models\Order::where('order_type', \App\Enums\OrderType::FINANCING_APPLICATION->value)
                                        ->where('status', \App\Enums\OrderStatus::PENDING->value);
                                    if (!$isAdmin && $userEntityId && $userRole === 'dealer') {
                                        // For dealers, show requests for their vehicles
                                        $financingPendingQuery->whereHas('vehicle', function($q) use ($userEntityId) {
                                            $q->where('entity_id', $userEntityId);
                                        });
                                    }
                                    $financingPendingCount = $financingPendingQuery->count();
                                }
                            @endphp
                            @if($financingPendingCount > 0)
                            <span class="ml-2 px-2 py-0.5 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full whitespace-nowrap flex-shrink-0">{{ $financingPendingCount }}</span>
                            @endif
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.orders.financing.index') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">All Applications</a>
                        <a href="{{ route('admin.orders.financing.pending') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Pending</a>
                        <a href="{{ route('admin.orders.financing.approved') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Approved</a>
                        <a href="{{ route('admin.orders.financing.completed') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Completed</a>
                        <a href="{{ route('admin.orders.financing.rejected') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Rejected</a>
                    </div>
                </div>
                @endif

                <!-- Leasing Orders - Expandable (Admin and Dealer) -->
                @if($userRole === 'admin' || $userRole === 'dealer')
                <div x-data="{ open: {{ request()->is('admin/orders/leasing*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg kibo-sidebar-hover transition-colors group">
                        <div class="flex items-center min-w-0 flex-1">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="menu-text whitespace-nowrap">Leasing Orders</span>
                            @php
                                $leasingPendingQuery = \App\Models\Order::where('order_type', \App\Enums\OrderType::LEASING_APPLICATION->value)
                                    ->where('status', \App\Enums\OrderStatus::PENDING->value);
                                if (!$isAdmin && $userEntityId) {
                                    $leasingPendingQuery->whereJsonContains('order_data->entity_id', $userEntityId);
                                }
                                $leasingPendingCount = $leasingPendingQuery->count();
                            @endphp
                            @if($leasingPendingCount > 0)
                            <span class="ml-2 px-2 py-0.5 text-xs font-semibold kibo-badge rounded-full whitespace-nowrap flex-shrink-0">{{ $leasingPendingCount }}</span>
                            @endif
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.orders.leasing.index') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">All Orders</a>
                        <a href="{{ route('admin.orders.leasing.pending') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Pending</a>
                        <a href="{{ route('admin.orders.leasing.approved') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Approved</a>
                        <a href="{{ route('admin.orders.leasing.active') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Active Leases</a>
                        <a href="{{ route('admin.orders.leasing.completed') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Completed</a>
                        <a href="{{ route('admin.orders.leasing.rejected') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Rejected</a>
                    </div>
                </div>
                @endif

                <!-- Garage Service Orders - Expandable (Admin and Agent) -->
                @if($userRole === 'admin' || $userRole === 'agent')
                <div x-data="{ open: {{ request()->is('admin/garage-orders*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg kibo-sidebar-hover transition-colors group">
                        <div class="flex items-center min-w-0 flex-1">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0h-.01M15 17a2 2 0 104 0m-4 0h-.01"></path>
                            </svg>
                            <span class="menu-text whitespace-nowrap">Garage Service Orders</span>
                            @php
                                // For agents, show only their garage's pending orders
                                if ($userRole === 'agent') {
                                    $agent = \App\Models\Agent::where('user_id', auth()->id())->first();
                                    $garageOrdersPendingCount = $agent 
                                        ? \App\Models\GarageServiceOrder::where('agent_id', $agent->id)->where('status', 'pending')->count()
                                        : 0;
                                } else {
                                    $garageOrdersPendingCount = \App\Models\GarageServiceOrder::where('status', 'pending')->count();
                                }
                            @endphp
                            @if($garageOrdersPendingCount > 0)
                            <span class="ml-2 px-2 py-0.5 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full whitespace-nowrap flex-shrink-0">{{ $garageOrdersPendingCount }}</span>
                            @endif
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.garage-orders') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">All Orders</a>
                        <a href="{{ route('admin.garage-orders') }}?filter=pending" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Pending</a>
                        <a href="{{ route('admin.garage-orders') }}?filter=confirmed" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Confirmed</a>
                        <a href="{{ route('admin.garage-orders') }}?filter=quoted" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Quoted</a>
                        <a href="{{ route('admin.garage-orders') }}?filter=completed" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Completed</a>
                    </div>
                </div>
                @endif

                <!-- Spare Part Orders - Expandable (Admin and Agent) -->
                @if($userRole === 'admin' || $userRole === 'agent')
                <div x-data="{ open: {{ request()->is('admin/spare-part-orders*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg kibo-sidebar-hover transition-colors group">
                        <div class="flex items-center min-w-0 flex-1">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="menu-text whitespace-nowrap">Spare Part Orders</span>
                            @php
                                // For agents, show only their pending orders
                                if ($userRole === 'agent') {
                                    $agent = \App\Models\Agent::where('user_id', auth()->id())->first();
                                    $sparePartsPendingCount = $agent 
                                        ? \App\Models\SparePartOrder::where('agent_id', $agent->id)->where('status', 'pending')->count()
                                        : \App\Models\SparePartOrder::where('status', 'pending')->count();
                                } else {
                                    $sparePartsPendingCount = \App\Models\SparePartOrder::where('status', 'pending')->count();
                                }
                            @endphp
                            @if($sparePartsPendingCount > 0)
                            <span class="ml-2 px-2 py-0.5 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full whitespace-nowrap flex-shrink-0">{{ $sparePartsPendingCount }}</span>
                            @endif
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.spare-part-orders') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">All Orders</a>
                        <a href="{{ route('admin.spare-part-orders') }}?status=pending" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Pending</a>
                        <a href="{{ route('admin.spare-part-orders') }}?status=quoted" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Quoted</a>
                        <a href="{{ route('admin.spare-part-orders') }}?status=accepted" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Accepted</a>
                        <a href="{{ route('admin.spare-part-orders') }}?status=awaiting_payment" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Awaiting Payment</a>
                        <a href="{{ route('admin.spare-part-orders') }}?status=completed" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Completed</a>
                    </div>
                </div>
                @endif

                <!-- Auctions (Admin and Dealer) -->
                @if($userRole === 'admin' || $userRole === 'dealer')
                <a href="{{ route('admin.auctions') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.auctions*') ? 'text-white kibo-sidebar-active shadow-sm' : 'text-gray-700 kibo-sidebar-hover' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="menu-text whitespace-nowrap">Auctions</span>
                    @php
                        $pendingAuctionsQuery = \App\Models\AuctionVehicle::where('status', 'pending')->where('admin_approved', false);
                        if (!$isAdmin && $userEntityId) {
                            $pendingAuctionsQuery->where('entity_id', $userEntityId);
                        }
                        $pendingAuctions = $pendingAuctionsQuery->count();
                    @endphp
                    @if($pendingAuctions > 0)
                    <span class="ml-2 px-2 py-0.5 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full whitespace-nowrap flex-shrink-0">{{ $pendingAuctions }}</span>
                    @endif
                </a>
                @endif

                <!-- Car Requests (Admin and Dealer) -->
                @if($userRole === 'admin' || $userRole === 'dealer')
                <a href="{{ route('admin.car-requests') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.car-requests') ? 'text-white kibo-sidebar-active shadow-sm' : 'text-gray-700 kibo-sidebar-hover' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 11h8M8 15h5M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                    </svg>
                    <span class="menu-text whitespace-nowrap">Car Requests</span>
                </a>
                @endif

                <!-- Customers - Expandable (Admin only) -->
                @if($userRole === 'admin')
                <div x-data="{ open: {{ request()->is('admin/customers*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->is('admin/customers*') ? 'text-white kibo-sidebar-active shadow-sm' : 'text-gray-700 kibo-sidebar-hover' }} transition-colors group">
                        <div class="flex items-center min-w-0">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span class="menu-text whitespace-nowrap">Customers</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.customers.index') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">All Customers</a>
                        <a href="{{ route('admin.customers.index') }}#active" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Active</a>
                        <a href="{{ route('admin.customers.index') }}#inactive" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Inactive</a>
                    </div>
                </div>
                @endif

                <!-- Reviews - Expandable (Admin only) -->
                @if($userRole === 'admin')
                <div x-data="{ open: {{ request()->is('admin/reviews*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->is('admin/reviews*') ? 'text-white kibo-sidebar-active shadow-sm' : 'text-gray-700 kibo-sidebar-hover' }} transition-colors group">
                        <div class="flex items-center min-w-0">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            <span class="menu-text whitespace-nowrap">Reviews</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.reviews.index') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">All Reviews</a>
                        <a href="{{ route('admin.reviews.index') }}#pending" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Pending</a>
                        <a href="{{ route('admin.reviews.index') }}#approved" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Approved</a>
                    </div>
                </div>
                @endif

                <!-- Management Section (Admin only) -->
                @if($userRole === 'admin')
                <div class="pt-4 pb-2 section-title">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</p>
                </div>

                <!-- Registration - Expandable (Admin only) -->
                <div x-data="{ open: {{ request()->is('admin/registration*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg kibo-sidebar-hover transition-colors group">
                        <div class="flex items-center min-w-0">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="menu-text whitespace-nowrap">Registration</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.registration.customers') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Customer Registration</a>
                        <a href="{{ route('admin.registration.cfcs') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">CFC Registration</a>
                        <a href="{{ route('admin.registration.agents') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Agent Registration</a>
                        <a href="{{ route('admin.registration.lenders') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Lender Registration</a>
                        <a href="{{ route('admin.registration.dealers') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Dealer Registration</a>
                    </div>
                </div>

                <!-- Users - Expandable (Admin only) -->
                <div x-data="{ open: {{ request()->is('admin/users*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg kibo-sidebar-hover transition-colors group">
                        <div class="flex items-center min-w-0">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span class="menu-text whitespace-nowrap">Users</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform menu-text flex-shrink-0" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">All Users</a>
                        <a href="{{ route('admin.users.lenders') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Lenders</a>
                        <a href="{{ route('admin.users.dealers') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Dealers</a>
                        <a href="{{ route('admin.users.admins') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Admins</a>
                        <a href="{{ route('admin.users.create') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Add User</a>
                        <a href="{{ route('admin.users.roles') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Roles</a>
                        <a href="{{ route('admin.users.permissions') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Permissions</a>
                    </div>
                </div>
                @endif

                <!-- Lending Criteria (Admin and Lender) -->
                @if($userRole === 'admin' || $userRole === 'lender')
                <a href="{{ route('admin.lending-criteria.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.lending-criteria.*') ? 'text-white kibo-sidebar-active shadow-sm' : 'text-gray-700 kibo-sidebar-hover' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span class="menu-text whitespace-nowrap">Lending Criteria</span>
                </a>
                @endif

                <!-- Valuation Pricing (Admin only) -->
                @if($userRole === 'admin')
                <a href="{{ route('admin.valuation-pricing.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.valuation-pricing.*') ? 'text-white kibo-sidebar-active shadow-sm' : 'text-gray-700 kibo-sidebar-hover' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="menu-text whitespace-nowrap">Valuation Pricing</span>
                </a>
                @endif

                <!-- Reports (Admin only) -->
                @if($userRole === 'admin')
                <a href="{{ route('admin.reports') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.reports') ? 'text-white kibo-sidebar-active shadow-sm' : 'text-gray-700 kibo-sidebar-hover' }} transition-colors group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="menu-text whitespace-nowrap">Reports</span>
                    @php
                        $pendingReportsCount = \App\Models\Report::where('status', 'pending')->count();
                    @endphp
                    @if($pendingReportsCount > 0)
                        <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-red-100 text-red-800 rounded-full whitespace-nowrap flex-shrink-0">{{ $pendingReportsCount }}</span>
                    @endif
                </a>
                @endif

                <!-- Settings - Expandable (Admin only) -->
                @if($userRole === 'admin')
                <div x-data="{ open: {{ request()->is('admin/settings*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg kibo-sidebar-hover transition-colors group">
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
                    <div x-show="open" x-transition class="ml-8 mt-1 space-y-1 submenu">
                        <a href="{{ route('admin.pricing.index') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Pricing Management</a>
                        <a href="{{ route('admin.settings.vehicles') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Vehicle Settings</a>
                        <a href="{{ route('admin.settings.general') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">General</a>
                        <a href="{{ route('admin.settings.security') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Security</a>
                        <a href="{{ route('admin.settings.notifications') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Notifications</a>
                        <a href="{{ route('admin.settings.billing') }}" class="block px-3 py-2 text-sm text-gray-600 rounded-lg kibo-sidebar-hover transition-colors">Billing</a>
                    </div>
                </div>
                @endif
            </div>
        </nav>

        <!-- User Profile Section -->
        <x-admin.user-profile />
    </div>
</aside>

<!-- Mobile Sidebar Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-30 lg:hidden hidden"></div>

