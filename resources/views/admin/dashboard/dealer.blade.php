@php
    use App\Models\Vehicle;
    use App\Models\Order;
    use App\Models\CarRequest;
    use App\Models\DealerCarOffer;
    use App\Enums\OrderStatus;
    use App\Enums\VehicleStatus;
    
    $user = auth()->user();
    $entityId = $user->entity_id ?? null;
    
    // Vehicle statistics
    $totalVehicles = $totalVehicles ?? 0;
    $activeVehicles = $activeVehicles ?? 0;
    $pendingVehicles = $pendingVehicles ?? 0;
    $soldVehicles = $soldVehicles ?? 0;
    
    // Car requests and offers
    $openCarRequests = $openCarRequests ?? 0;
    $myOffers = $myOffers ?? 0;
    $myAcceptedOffers = $myAcceptedOffers ?? 0;
    
    // Orders
    $totalOrders = $totalOrders ?? 0;
    $pendingOrders = $pendingOrders ?? 0;
    
    // Recent data
    $recentVehicles = $recentVehicles ?? collect();
    $recentCarRequests = $recentCarRequests ?? collect();
@endphp

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Dealer Dashboard</h1>
    <p class="mt-2 text-sm text-gray-600">Welcome back, {{ $user->name }}! Manage your vehicles and track your dealership activity.</p>
    <p class="mt-1 text-xs text-gray-500">Focus on vehicle registration, listing management, and customer requests.</p>
</div>

<!-- Top Metrics -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Vehicles -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Vehicles</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalVehicles) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ number_format($activeVehicles) }} active · {{ number_format($pendingVehicles) }} pending</p>
                <a href="{{ route('dealer.vehicles.index') }}" class="text-xs text-green-700 hover:text-green-800 mt-1 inline-block">Manage vehicles →</a>
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
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($activeVehicles) }}</p>
                <p class="text-sm text-blue-600 mt-1">{{ $totalVehicles > 0 ? round(($activeVehicles / $totalVehicles) * 100) : 0 }}% of total</p>
                <a href="{{ route('dealer.vehicles.active') }}" class="text-xs text-green-700 hover:text-green-800 mt-1 inline-block">View active →</a>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Vehicles Sold -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Vehicles Sold</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($soldVehicles) }}</p>
                <p class="text-sm text-green-600 mt-1">Total sold</p>
                <a href="{{ route('dealer.vehicles.sold') }}" class="text-xs text-green-700 hover:text-green-800 mt-1 inline-block">View sold →</a>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pending Approval -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Pending Approval</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($pendingVehicles) }}</p>
                <p class="text-sm text-yellow-600 mt-1">Awaiting admin review</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Car Requests & Offers -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Open Car Requests</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($openCarRequests) }}</p>
                <p class="text-sm text-gray-500 mt-1">Customer requests</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2-3 .895-3 2 1.343 2 3 2m0-8a4 4 0 00-4 4c0 1.657 1.343 3 3 3h2c1.657 0 3 1.343 3 3a4 4 0 01-4 4m0-16v1m0 15v1m8-8h1M3 12h1m12.364 5.364l.707.707M5.636 6.636l.707.707m12.021 0l-.707.707M5.636 17.364l-.707.707"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('dealer.car-requests') }}" class="text-sm text-green-600 hover:text-green-700 mt-3 inline-block">View requests →</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">My Offers</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($myOffers) }}</p>
                <p class="text-sm text-gray-500 mt-1">Total submitted</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('dealer.car-requests') }}" class="text-xs text-green-700 hover:text-green-800 mt-1 inline-block">Manage offers →</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Accepted Offers</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($myAcceptedOffers) }}</p>
                <p class="text-sm text-green-600 mt-1">Successfully won</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Orders</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalOrders) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ number_format($pendingOrders) }} pending</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('dealer.orders') }}" class="text-sm text-green-600 hover:text-green-700 mt-3 inline-block">View orders →</a>
    </div>
</div>

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Recent Vehicles -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Recent Vehicles</h2>
            <a href="{{ route('dealer.vehicles.index') }}" class="text-sm text-green-700 hover:text-green-800">View all →</a>
        </div>
        <div class="p-6">
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
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            @if($vehicle->status === VehicleStatus::AVAILABLE) bg-green-100 text-green-700
                            @elseif($vehicle->status === VehicleStatus::PENDING) bg-yellow-100 text-yellow-700
                            @elseif($vehicle->status === VehicleStatus::SOLD) bg-gray-100 text-gray-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ $vehicle->status?->value ?? '—' }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-600">No vehicles yet. <a href="{{ route('admin.vehicles.registration.create') }}" class="text-green-600 hover:text-green-700">Register your first vehicle →</a></p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('admin.vehicles.registration.create') }}" class="w-full flex items-center px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Register New Vehicle
                </a>
                <a href="{{ route('dealer.vehicles.index') }}" class="w-full flex items-center px-4 py-3 border-2 border-green-500 text-green-600 rounded-lg hover:bg-green-50 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Manage Vehicles
                </a>
                <a href="{{ route('dealer.car-requests') }}" class="w-full flex items-center px-4 py-3 border-2 border-green-500 text-green-600 rounded-lg hover:bg-green-50 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2-3 .895-3 2 1.343 2 3 2m0-8a4 4 0 00-4 4c0 1.657 1.343 3 3 3h2c1.657 0 3 1.343 3 3a4 4 0 01-4 4m0-16v1m0 15v1m8-8h1M3 12h1m12.364 5.364l.707.707M5.636 6.636l.707.707m12.021 0l-.707.707M5.636 17.364l-.707.707"/>
                    </svg>
                    View Car Requests
                </a>
                <a href="{{ route('dealer.analytics') }}" class="w-full flex items-center px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    View Analytics
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Car Requests Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Latest Car Requests</h2>
            <p class="text-sm text-gray-600">New customer requests - submit your offers to win business.</p>
        </div>
        <a href="{{ route('dealer.car-requests') }}" class="text-sm text-green-700 hover:text-green-800">View all →</a>
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
                        @if($req->max_budget) Budget: {{ number_format($req->max_budget) }} TZS · @endif
                        {{ $req->customer_name }}
                    </p>
                    <p class="text-xs text-gray-500">Created {{ $req->created_at?->diffForHumans() }}</p>
                </div>
                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                    Open
                </span>
            </div>
        @empty
            <p class="text-sm text-gray-600">No open car requests at the moment.</p>
        @endforelse
    </div>
</div>

