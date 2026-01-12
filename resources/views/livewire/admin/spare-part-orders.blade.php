<div>
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-bold text-gray-900">Spare Part Orders</h2>
        </div>

        {{-- Filters --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <select 
                wire:model.live="statusFilter" 
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
            >
                <option value="">All Statuses</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Search by order number, customer name, email, phone, or part name..."
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
            >
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if (session()->has('success'))
        <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Orders Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->customer_name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->vehicleMake->name ?? 'N/A' }} {{ $order->vehicleModel->name ?? '' }}</div>
                            <div class="text-sm text-gray-500 capitalize">{{ $order->condition }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $order->part_name ?? 'N/A' }}</div>
                            @if($order->description)
                                <div class="text-sm text-gray-500 line-clamp-1">{{ Str::limit($order->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'quoted') bg-purple-100 text-purple-800
                                @elseif($order->status === 'accepted') bg-green-100 text-green-800
                                @elseif($order->status === 'rejected') bg-red-100 text-red-800
                                @elseif($order->status === 'completed') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button
                                wire:click="viewOrder({{ $order->id }})"
                                class="text-green-600 hover:text-green-900 mr-3"
                            >
                                View
                            </button>
                            <button
                                wire:click="openQuoteModal({{ $order->id }})"
                                class="text-blue-600 hover:text-blue-900"
                            >
                                Quote
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No orders found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="px-6 py-4">
        {{ $orders->links() }}
    </div>

    {{-- Order Detail Modal --}}
    @if($showDetailModal && $selectedOrder)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:click="closeDetailModal">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black bg-opacity-50" wire:click="closeDetailModal"></div>
                <div class="relative bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
                    <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-gray-900">Order Details - {{ $selectedOrder->order_number }}</h3>
                        <button wire:click="closeDetailModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6 space-y-6">
                        {{-- Customer Info --}}
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Customer Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Name</p>
                                    <p class="text-gray-900">{{ $selectedOrder->customer_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="text-gray-900">{{ $selectedOrder->customer_email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Phone</p>
                                    <p class="text-gray-900">{{ $selectedOrder->customer_phone }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Vehicle Info --}}
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Vehicle Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Make</p>
                                    <p class="text-gray-900">{{ $selectedOrder->vehicleMake->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Model</p>
                                    <p class="text-gray-900">{{ $selectedOrder->vehicleModel->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Condition</p>
                                    <p class="text-gray-900 capitalize">{{ $selectedOrder->condition }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Part Name</p>
                                    <p class="text-gray-900">{{ $selectedOrder->part_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @if($selectedOrder->description)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-500">Description</p>
                                    <p class="text-gray-900">{{ $selectedOrder->description }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- Images --}}
                        @if($selectedOrder->images && count($selectedOrder->images) > 0)
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-3">Images</h4>
                                <div class="grid grid-cols-3 gap-4">
                                    @foreach($selectedOrder->images as $image)
                                        <img src="{{ asset('storage/' . $image) }}" alt="Part image" class="w-full h-32 object-cover rounded-lg">
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Delivery Info --}}
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Delivery Information</h4>
                            <p class="text-gray-900">{{ $selectedOrder->delivery_address }}</p>
                            <p class="text-gray-600">{{ $selectedOrder->delivery_city }}, {{ $selectedOrder->delivery_region }}, {{ $selectedOrder->delivery_country }}</p>
                            @if($selectedOrder->delivery_postal_code)
                                <p class="text-gray-600">Postal Code: {{ $selectedOrder->delivery_postal_code }}</p>
                            @endif
                        </div>

                        {{-- Contact Info --}}
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Contact Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Name</p>
                                    <p class="text-gray-900">{{ $selectedOrder->contact_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Phone</p>
                                    <p class="text-gray-900">{{ $selectedOrder->contact_phone }}</p>
                                </div>
                                @if($selectedOrder->contact_email)
                                    <div>
                                        <p class="text-sm text-gray-500">Email</p>
                                        <p class="text-gray-900">{{ $selectedOrder->contact_email }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Status Actions --}}
                        <div class="flex gap-3 pt-4 border-t border-gray-200">
                            <button
                                wire:click="updateStatus({{ $selectedOrder->id }}, 'processing')"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                            >
                                Mark as Processing
                            </button>
                            <button
                                wire:click="updateStatus({{ $selectedOrder->id }}, 'completed')"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                            >
                                Mark as Completed
                            </button>
                            <button
                                wire:click="updateStatus({{ $selectedOrder->id }}, 'rejected')"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                            >
                                Reject
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Quote Modal --}}
    @if($showQuoteModal && $selectedOrder)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:click="closeQuoteModal">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black bg-opacity-50" wire:click="closeQuoteModal"></div>
                <div class="relative bg-white rounded-xl shadow-xl max-w-2xl w-full" wire:click.stop>
                    <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-gray-900">Update Quote - {{ $selectedOrder->order_number }}</h3>
                        <button wire:click="closeQuoteModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form wire:submit.prevent="submitQuote" class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quoted Price ({{ $selectedOrder->currency }})</label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model="quotedPrice"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Enter price"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                            <select
                                wire:model="assignedTo"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            >
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                            <textarea
                                wire:model="adminNotes"
                                rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Add notes about this order..."
                            ></textarea>
                        </div>
                        <div class="flex justify-end gap-3 pt-4">
                            <button
                                type="button"
                                wire:click="closeQuoteModal"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                            >
                                Update Quote
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

