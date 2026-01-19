<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">My Spare Parts Orders</h1>
        <p class="text-gray-600">View and track your spare parts orders</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Orders</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by order number, part name..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                <select
                    wire:model.live="statusFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="quoted">Quoted</option>
                    <option value="accepted">Accepted</option>
                    <option value="rejected">Rejected</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $order->order_number }}</h3>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $this->getStatusColor($order->status) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Vehicle:</span>
                                    <span class="text-gray-900 font-medium ml-2">
                                        {{ $order->vehicleMake->name ?? 'N/A' }} {{ $order->vehicleModel->name ?? '' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Part:</span>
                                    <span class="text-gray-900 font-medium ml-2">{{ $order->part_name ?? 'N/A' }}</span>
                                </div>
                                @if($order->quoted_price)
                                    <div>
                                        <span class="text-gray-500">Quoted Price:</span>
                                        <span class="text-gray-900 font-medium ml-2">
                                            {{ number_format($order->quoted_price, 2) }} {{ $order->currency ?? 'TZS' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <a
                                href="{{ route('spare-parts.order-detail', $order->id) }}"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium"
                            >
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Orders Found</h3>
            <p class="text-gray-600 mb-6">You haven't placed any spare parts orders yet.</p>
            <a href="{{ route('spare-parts.sourcing') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                Place Your First Order
            </a>
        </div>
    @endif

</div>

