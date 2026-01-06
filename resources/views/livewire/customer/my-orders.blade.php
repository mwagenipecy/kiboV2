<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
        <p class="mt-2 text-gray-600">Track your valuation reports, financing applications, and purchases</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $statusCounts['all'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $statusCounts['pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Approved</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $statusCounts['approved'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $statusCounts['completed'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                <select wire:model.live="filterStatus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="approved">Approved</option>
                    <option value="completed">Completed</option>
                    <option value="rejected">Rejected</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Type</label>
                <select wire:model.live="filterType" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="all">All Types</option>
                    <option value="valuation_report">Valuation Report</option>
                    <option value="financing_application">Financing Application</option>
                    <option value="cash_purchase">Cash Purchase</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="space-y-4">
        @forelse($orders as $order)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <!-- Order Info -->
                    <div class="flex-1">
                        <div class="flex items-start gap-4">
                            <!-- Vehicle Image -->
                            @if($order->vehicle)
                            <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                @if($order->vehicle->image_front)
                                <img src="{{ asset('storage/' . $order->vehicle->image_front) }}" alt="Vehicle" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0h-.01M15 17a2 2 0 104 0m-4 0h-.01"/>
                                    </svg>
                                </div>
                                @endif
                            </div>
                            @endif

                            <!-- Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $order->order_type_label }}</h3>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($order->status->value === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status->value === 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status->value === 'approved') bg-green-100 text-green-800
                                        @elseif($order->status->value === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status->value === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $order->status_label }}
                                    </span>
                                </div>

                                @if($order->vehicle)
                                <p class="text-sm text-gray-600 mb-1">
                                    {{ $order->vehicle->year }} {{ $order->vehicle->make->name ?? '' }} {{ $order->vehicle->model->name ?? '' }}
                                </p>
                                @endif

                                <p class="text-sm text-gray-500">Order #{{ $order->order_number }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment & Actions -->
                    <div class="flex flex-col items-end gap-3">
                        <!-- Payment Status -->
                        @if($order->payment_required)
                        <div class="text-right">
                            <p class="text-2xl font-bold text-gray-900">£{{ number_format($order->fee, 2) }}</p>
                            @if($order->payment_completed)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Paid
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                Payment Required
                            </span>
                            @endif
                        </div>
                        @else
                        <p class="text-sm text-gray-500">Free Service</p>
                        @endif

                        <!-- Actions -->
                        <div class="flex gap-2">
                            @if($order->requiresPayment())
                            <button wire:click="payOrder({{ $order->id }})" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                Pay Now
                            </button>
                            @endif
                            <button wire:click="viewOrder({{ $order->id }})" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                @if($order->customer_notes)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600"><span class="font-medium">Notes:</span> {{ $order->customer_notes }}</p>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No orders yet</h3>
            <p class="text-gray-600 mb-6">Start by requesting a valuation report or applying for financing</p>
            <a href="{{ route('cars.search') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                Browse Vehicles
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $orders->links() }}
    </div>

    <!-- View Order Modal (Side Panel) -->
    @if($showViewModal && $selectedOrder)
    <div class="fixed inset-0 bg-black/50 z-50 flex justify-end" wire:click="closeViewModal">
        <div class="bg-white w-full max-w-2xl h-full overflow-y-auto shadow-2xl" wire:click.stop>
            <!-- Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Order Details</h2>
                    <p class="text-sm text-gray-600 mt-1">Order #{{ $selectedOrder->order_number }}</p>
                </div>
                <button wire:click="closeViewModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-6">
                <!-- Status Banner -->
                <div class="rounded-lg p-4
                    @if($selectedOrder->status->value === 'pending') bg-yellow-50 border border-yellow-200
                    @elseif($selectedOrder->status->value === 'processing') bg-blue-50 border border-blue-200
                    @elseif($selectedOrder->status->value === 'approved') bg-green-50 border border-green-200
                    @elseif($selectedOrder->status->value === 'completed') bg-green-50 border border-green-200
                    @elseif($selectedOrder->status->value === 'rejected') bg-red-50 border border-red-200
                    @else bg-gray-50 border border-gray-200
                    @endif">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium
                                @if($selectedOrder->status->value === 'pending') text-yellow-900
                                @elseif($selectedOrder->status->value === 'processing') text-blue-900
                                @elseif($selectedOrder->status->value === 'approved') text-green-900
                                @elseif($selectedOrder->status->value === 'completed') text-green-900
                                @elseif($selectedOrder->status->value === 'rejected') text-red-900
                                @else text-gray-900
                                @endif">Current Status</p>
                            <p class="text-2xl font-bold mt-1
                                @if($selectedOrder->status->value === 'pending') text-yellow-700
                                @elseif($selectedOrder->status->value === 'processing') text-blue-700
                                @elseif($selectedOrder->status->value === 'approved') text-green-700
                                @elseif($selectedOrder->status->value === 'completed') text-green-700
                                @elseif($selectedOrder->status->value === 'rejected') text-red-700
                                @else text-gray-700
                                @endif">{{ $selectedOrder->status_label }}</p>
                        </div>
                        <svg class="w-16 h-16
                            @if($selectedOrder->status->value === 'pending') text-yellow-400
                            @elseif($selectedOrder->status->value === 'processing') text-blue-400
                            @elseif($selectedOrder->status->value === 'approved') text-green-400
                            @elseif($selectedOrder->status->value === 'completed') text-green-400
                            @elseif($selectedOrder->status->value === 'rejected') text-red-400
                            @else text-gray-400
                            @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Order Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Order Information</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Order Type:</dt>
                            <dd class="font-medium text-gray-900">{{ $selectedOrder->order_type_label }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Order Number:</dt>
                            <dd class="font-medium text-gray-900">{{ $selectedOrder->order_number }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Submitted:</dt>
                            <dd class="font-medium text-gray-900">{{ $selectedOrder->created_at->format('M d, Y h:i A') }}</dd>
                        </div>
                        @if($selectedOrder->processed_at)
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Processed:</dt>
                            <dd class="font-medium text-gray-900">{{ $selectedOrder->processed_at->format('M d, Y h:i A') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Vehicle Information -->
                @if($selectedOrder->vehicle)
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Vehicle Information</h3>
                    <div class="flex gap-4">
                        @if($selectedOrder->vehicle->image_front)
                        <img src="{{ asset('storage/' . $selectedOrder->vehicle->image_front) }}" alt="Vehicle" class="w-32 h-32 object-cover rounded-lg">
                        @endif
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 text-lg">
                                {{ $selectedOrder->vehicle->year }} {{ $selectedOrder->vehicle->make->name ?? '' }} {{ $selectedOrder->vehicle->model->name ?? '' }}
                            </p>
                            <dl class="mt-2 space-y-1 text-sm">
                                <div class="flex">
                                    <dt class="text-gray-600 w-24">Price:</dt>
                                    <dd class="font-medium text-gray-900">£{{ number_format($selectedOrder->vehicle->price, 2) }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="text-gray-600 w-24">Mileage:</dt>
                                    <dd class="font-medium text-gray-900">{{ number_format($selectedOrder->vehicle->mileage) }} miles</dd>
                                </div>
                                <div class="flex">
                                    <dt class="text-gray-600 w-24">Condition:</dt>
                                    <dd class="font-medium text-gray-900 capitalize">{{ $selectedOrder->vehicle->condition }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Payment Information -->
                @if($selectedOrder->payment_required)
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Payment Information</h3>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-gray-600">Service Fee:</span>
                        <span class="text-2xl font-bold text-gray-900">£{{ number_format($selectedOrder->fee, 2) }}</span>
                    </div>
                    @if($selectedOrder->payment_completed)
                    <div class="flex items-center gap-2 text-green-700 bg-green-50 px-3 py-2 rounded-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-medium">Payment Completed</p>
                            @if($selectedOrder->paid_at)
                            <p class="text-sm">Paid on {{ $selectedOrder->paid_at->format('M d, Y h:i A') }}</p>
                            @endif
                            @if($selectedOrder->payment_method)
                            <p class="text-sm">Method: {{ ucfirst($selectedOrder->payment_method) }}</p>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-2 text-red-700 bg-red-50 px-3 py-2 rounded-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="font-medium">Payment Required</p>
                    </div>
                    <button wire:click="payOrder({{ $selectedOrder->id }})" class="w-full mt-3 px-4 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                        Pay Now - £{{ number_format($selectedOrder->fee, 2) }}
                    </button>
                    @endif
                </div>
                @endif

                <!-- Order Details -->
                @if($selectedOrder->order_data)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Order Details</h3>
                    <dl class="space-y-2 text-sm">
                        @foreach($selectedOrder->order_data as $key => $value)
                        <div class="flex justify-between">
                            <dt class="text-gray-600 capitalize">{{ str_replace('_', ' ', $key) }}:</dt>
                            <dd class="font-medium text-gray-900 capitalize">{{ is_array($value) ? implode(', ', $value) : $value }}</dd>
                        </div>
                        @endforeach
                    </dl>
                </div>
                @endif

                <!-- Customer Notes -->
                @if($selectedOrder->customer_notes)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-900 mb-2">Your Notes</h3>
                    <p class="text-sm text-blue-800">{{ $selectedOrder->customer_notes }}</p>
                </div>
                @endif

                <!-- Admin Notes -->
                @if($selectedOrder->admin_notes)
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <h3 class="font-semibold text-purple-900 mb-2">Admin Notes</h3>
                    <p class="text-sm text-purple-800">{{ $selectedOrder->admin_notes }}</p>
                </div>
                @endif

                <!-- Rejection Reason -->
                @if($selectedOrder->rejection_reason)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 class="font-semibold text-red-900 mb-2">Rejection Reason</h3>
                    <p class="text-sm text-red-800">{{ $selectedOrder->rejection_reason }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Payment Modal (Side Panel) -->
    @if($showPaymentModal && $selectedOrder)
    <div class="fixed inset-0 bg-black/50 z-50 flex justify-end" wire:click="closePaymentModal">
        <div class="bg-white w-full max-w-xl h-full overflow-y-auto shadow-2xl" wire:click.stop>
            <!-- Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Complete Payment</h2>
                    <p class="text-sm text-gray-600 mt-1">Order #{{ $selectedOrder->order_number }}</p>
                </div>
                <button wire:click="closePaymentModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-6">
                <!-- Amount Due -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                    <p class="text-sm text-green-700 mb-1">Amount Due</p>
                    <p class="text-4xl font-bold text-green-900">£{{ number_format($selectedOrder->fee, 2) }}</p>
                    <p class="text-sm text-green-600 mt-2">{{ $selectedOrder->order_type_label }}</p>
                </div>

                <!-- Payment Methods -->
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Select Payment Method</h3>
                    
                    <!-- Credit/Debit Card -->
                    <button wire:click="processPayment('card')" class="w-full mb-3 p-4 border-2 border-gray-300 rounded-lg hover:border-green-600 hover:bg-green-50 transition-colors text-left">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Credit/Debit Card</p>
                                    <p class="text-sm text-gray-600">Visa, Mastercard, American Express</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </button>

                    <!-- PayPal -->
                    <button wire:click="processPayment('paypal')" class="w-full mb-3 p-4 border-2 border-gray-300 rounded-lg hover:border-green-600 hover:bg-green-50 transition-colors text-left">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.067 8.478c.492.88.556 2.014.3 3.327-.74 3.806-3.276 5.12-6.514 5.12h-.5a.805.805 0 00-.794.68l-.04.22-.63 3.993-.032.17a.804.804 0 01-.794.679H7.72a.483.483 0 01-.477-.558L8.926 12.2h.008c.162-.975.97-1.689 1.993-1.689h1.331c3.238 0 5.774-1.314 6.514-5.12a3.95 3.95 0 00-.133-2.262 6.738 6.738 0 011.428 5.35z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">PayPal</p>
                                    <p class="text-sm text-gray-600">Pay with your PayPal account</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </button>

                    <!-- Mobile Money -->
                    <button wire:click="processPayment('mobile_money')" class="w-full p-4 border-2 border-gray-300 rounded-lg hover:border-green-600 hover:bg-green-50 transition-colors text-left">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Mobile Money</p>
                                    <p class="text-sm text-gray-600">M-Pesa, Airtel Money, etc.</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </button>
                </div>

                <!-- Security Notice -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Secure Payment</p>
                            <p class="text-xs text-gray-600 mt-1">Your payment information is encrypted and secure. We never store your card details.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session()->has('success'))
    <div class="fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
    @endif
</div>

