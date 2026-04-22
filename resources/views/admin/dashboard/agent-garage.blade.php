<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Agent Dashboard - Garage</h1>
    <p class="mt-2 text-sm text-gray-600">Track garage service orders for your workshop.</p>
</div>

<!-- Top Metrics -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <p class="text-sm font-medium text-gray-600">Total Orders</p>
        <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($garageOrders ?? 0) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <p class="text-sm font-medium text-gray-600">Pending</p>
        <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($garageOrdersPending ?? 0) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <p class="text-sm font-medium text-gray-600">In Progress</p>
        <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($garageOrdersInProgress ?? 0) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <p class="text-sm font-medium text-gray-600">Completed</p>
        <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($garageOrdersCompleted ?? 0) }}</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Garage Service Orders</h2>
            <p class="text-sm text-gray-600">View and manage your garage service requests.</p>
        </div>
        <a href="{{ route('admin.garage-orders') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            Manage Orders
        </a>
    </div>
    <div class="p-6">
        <livewire:admin.garage-orders />
    </div>
</div>
