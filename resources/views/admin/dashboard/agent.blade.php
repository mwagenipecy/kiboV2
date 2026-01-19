@php
    use App\Models\SparePartOrder;
@endphp

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Agent Dashboard - Spare Parts</h1>
    <p class="mt-2 text-sm text-gray-600">Manage spare part orders and track your activity.</p>
    <p class="mt-1 text-xs text-gray-500">Focused on spare parts management and order fulfillment.</p>
</div>

<!-- Top Metrics -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Orders</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($sparePartOrders) }}</p>
                <p class="text-sm text-gray-500 mt-1">All spare part orders</p>
                <a href="{{ route('admin.spare-part-orders') }}" class="text-xs text-green-700 hover:text-green-800 mt-1 inline-block">View all →</a>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Pending</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($sparePartOrdersPending) }}</p>
                <p class="text-sm text-yellow-600 mt-1">Awaiting action</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">In Progress</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($sparePartOrdersInProgress) }}</p>
                <p class="text-sm text-blue-600 mt-1">Being processed</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Completed</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($sparePartOrdersCompleted) }}</p>
                <p class="text-sm text-green-600 mt-1">Successfully fulfilled</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Spare Part Orders Management</h2>
            <p class="text-sm text-gray-600">View and manage all spare part orders from customers.</p>
        </div>
        <a href="{{ route('admin.spare-part-orders') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            Manage Orders
        </a>
    </div>
    <div class="p-6">
        <livewire:admin.spare-part-orders />
    </div>
</div>

