@php
    use App\Models\Vehicle;
    use App\Models\Order;
    use App\Models\User;
    use App\Models\CarRequest;
    use App\Models\DealerCarOffer;
    use App\Enums\OrderStatus;
    use App\Enums\OrderType;
@endphp

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Admin Dashboard</h1>
    <p class="mt-2 text-sm text-gray-600">Clear snapshot of vehicles, orders, users, and find-me-a-car activity.</p>
    <p class="mt-1 text-xs text-gray-500">Data refreshed on load — use links to dive deeper.</p>
</div>

<!-- Top Metrics -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Vehicles</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalVehicles) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ number_format($activeVehicles) }} active · {{ number_format($pendingVehicles) }} pending</p>
                <a href="{{ route('admin.vehicles.registration.index') }}" class="text-xs text-green-700 hover:text-green-800 mt-1 inline-block">View vehicles →</a>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Orders</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalOrders) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ number_format($pendingOrders) }} pending · {{ number_format($completedOrders) }} completed</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Revenue (fees)</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($revenue, 0) }}</p>
                <p class="text-sm text-gray-500 mt-1">From completed payments</p>
                <a href="{{ route('admin.analytics') }}" class="text-xs text-green-700 hover:text-green-800 mt-1 inline-block">Analytics →</a>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Users</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($customerCount + $dealerCount) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ number_format($dealerCount) }} dealers · {{ number_format($customerCount) }} customers</p>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-green-700 hover:text-green-800 mt-1 inline-block">Manage users →</a>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Requests & Offers -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Find-me-a-car Requests</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($carRequestsOpen + $carRequestsClosed) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ number_format($carRequestsOpen) }} open · {{ number_format($carRequestsClosed) }} closed</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2-3 .895-3 2 1.343 2 3 2m0-8a4 4 0 00-4 4c0 1.657 1.343 3 3 3h2c1.657 0 3 1.343 3 3a4 4 0 01-4 4m0-16v1m0 15v1m8-8h1M3 12h1m12.364 5.364l.707.707M5.636 6.636l.707.707m12.021 0l-.707.707M5.636 17.364l-.707.707"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.car-requests') }}" class="text-sm text-green-600 hover:text-green-700 mt-3 inline-block">View requests →</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Dealer Offers</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($carRequestOffers) }}</p>
                <p class="text-sm text-gray-500 mt-1">Total offers on requests</p>
                <a href="{{ route('admin.car-requests') }}" class="text-xs text-green-700 hover:text-green-800 mt-1 inline-block">Review offers →</a>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($pendingOrders) }}</p>
                <p class="text-sm text-gray-500 mt-1">Awaiting approval/payment</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-green-600 hover:text-green-700 mt-3 inline-block">Manage orders →</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Reports</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">CSV</p>
                <p class="text-sm text-gray-500 mt-1">Sales · Vehicles · Users</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
        <div class="flex gap-2 mt-3 text-sm">
            <a href="{{ route('admin.reports.sales') }}" class="text-green-700 hover:text-green-800">Sales</a>
            <span class="text-gray-300">•</span>
            <a href="{{ route('admin.reports.vehicles') }}" class="text-green-700 hover:text-green-800">Vehicles</a>
            <span class="text-gray-300">•</span>
            <a href="{{ route('admin.reports.users') }}" class="text-green-700 hover:text-green-800">Users</a>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Vehicles -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Vehicles</h2>
            <div class="space-y-4">
                @forelse($recentVehicles as $vehicle)
                    <div class="flex items-center space-x-4 p-4 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-14 h-14 rounded-lg bg-gray-100 flex items-center justify-center text-sm text-gray-500">
                            {{ $vehicle->make?->name ? strtoupper(substr($vehicle->make->name,0,1)) : 'V' }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 truncate">
                                {{ $vehicle->title ?? ($vehicle->make?->name.' '.$vehicle->model?->name) }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ $vehicle->make?->name ?? 'N/A' }} {{ $vehicle->model?->name ?? '' }}
                            </p>
                            <p class="text-sm text-gray-500">Added {{ $vehicle->created_at?->diffForHumans() }}</p>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">{{ $vehicle->status ?? '—' }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-600">No vehicles yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Insights & Quick Links -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('admin.car-requests') }}" class="w-full flex items-center px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Manage Car Requests
                </a>
                <a href="{{ route('admin.reports.vehicles') }}" class="w-full flex items-center px-4 py-3 border-2 border-green-500 text-green-600 rounded-lg hover:bg-green-50 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download Vehicle Report
                </a>
                <a href="{{ route('admin.orders.index') }}" class="w-full flex items-center px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    View Orders
                </a>
            </div>

            <!-- Recent Orders -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Recent Orders</h3>
                <div class="space-y-3">
                    @forelse($recentOrders as $order)
                        <div class="flex items-start justify-between">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900">#{{ $order->id }} • {{ $order->order_type?->value ?? $order->order_type }}</p>
                                <p class="text-xs text-gray-600">{{ $order->user?->name ?? 'Guest' }}</p>
                                <p class="text-xs text-gray-500">Placed {{ $order->created_at?->diffForHumans() }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ ($order->status?->value ?? $order->status) === 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($order->status?->value ?? $order->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600">No recent orders.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Car Requests -->
<div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Latest Find-me-a-car Requests</h2>
            <p class="text-sm text-gray-600">Track new customer demand and dealer offers.</p>
        </div>
        <a href="{{ route('admin.car-requests') }}" class="text-sm text-green-700 hover:text-green-800">View all →</a>
    </div>
    <div class="p-6 space-y-4">
        @forelse($recentCarRequests as $req)
            <div class="flex items-start justify-between p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="min-w-0">
                    <p class="font-semibold text-gray-900">
                        {{ $req->make?->name ?? 'Any make' }} {{ $req->model?->name ?? '' }}
                    </p>
                    <p class="text-sm text-gray-600">
                        @if($req->location) 📍 {{ $req->location }} · @endif
                        {{ $req->customer_name }}
                    </p>
                    <p class="text-xs text-gray-500">Created {{ $req->created_at?->diffForHumans() }}</p>
                </div>
                <span class="px-2 py-1 text-xs rounded-full {{ $req->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                    {{ ucfirst($req->status) }}
                </span>
            </div>
        @empty
            <p class="text-sm text-gray-600">No recent requests.</p>
        @endforelse
    </div>
</div>

