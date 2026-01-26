<div>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Garage Service Orders</h1>
        <p class="mt-2 text-sm text-gray-600">Manage and track all garage service bookings from customers.</p>
    </div>

    @if(session()->has('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-4 border-b border-gray-200">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Search by order number, customer, or service..." 
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex overflow-x-auto border-b border-gray-200">
            <button 
                wire:click="setFilter('all')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filter === 'all' ? 'text-green-600 border-b-2 border-green-600 bg-green-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                All Orders
                <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $filter === 'all' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $counts['all'] }}
                </span>
            </button>
            <button 
                wire:click="setFilter('pending')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filter === 'pending' ? 'text-yellow-600 border-b-2 border-yellow-600 bg-yellow-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Pending
                <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $filter === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $counts['pending'] }}
                </span>
            </button>
            <button 
                wire:click="setFilter('confirmed')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filter === 'confirmed' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Confirmed
                <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $filter === 'confirmed' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $counts['confirmed'] }}
                </span>
            </button>
            <button 
                wire:click="setFilter('quoted')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filter === 'quoted' ? 'text-purple-600 border-b-2 border-purple-600 bg-purple-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Quoted
                <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $filter === 'quoted' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $counts['quoted'] }}
                </span>
            </button>
            <button 
                wire:click="setFilter('completed')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filter === 'completed' ? 'text-green-600 border-b-2 border-green-600 bg-green-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Completed
                <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $filter === 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $counts['completed'] }}
                </span>
            </button>
        </div>
    </div>

    <!-- Orders List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Garage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ ucwords(str_replace('_', ' ', $order->service_type)) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->agent->company_name ?? $order->agent->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ ucfirst($order->booking_type) }}</div>
                            @if($order->booking_type === 'scheduled' && $order->scheduled_date)
                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order->scheduled_date)->format('M d, Y') }}</div>
                            @if($order->scheduled_time)
                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order->scheduled_time)->format('h:i A') }}</div>
                            @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                @elseif($order->status === 'rejected') bg-red-100 text-red-800
                                @elseif($order->status === 'quoted') bg-purple-100 text-purple-800
                                @elseif($order->status === 'in_progress') bg-indigo-100 text-indigo-800
                                @elseif($order->status === 'completed') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                            @if($order->quoted_price)
                            <div class="text-xs text-gray-600 mt-1">{{ $order->currency }} {{ number_format($order->quoted_price, 2) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="viewOrder({{ $order->id }})" title="View Details" class="text-blue-600 hover:bg-blue-50 p-2 rounded-full transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                @if($order->status === 'pending')
                                <button wire:click="openQuoteModal({{ $order->id }})" title="Send Quotation" class="text-purple-600 hover:bg-purple-50 p-2 rounded-full transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
        @endif
        @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0h-.01M15 17a2 2 0 104 0m-4 0h-.01"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Garage Orders Found</h3>
            <p class="text-gray-600">There are no garage service orders matching your search criteria.</p>
        </div>
        @endif
    </div>

    <!-- Detail Modal -->
    @if($showDetailModal && $selectedOrder)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeDetailModal"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Order Details - {{ $selectedOrder->order_number }}
                        </h3>
                        <button wire:click="closeDetailModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Customer</p>
                                <p class="text-sm text-gray-900">{{ $selectedOrder->customer_name }}</p>
                                <p class="text-sm text-gray-600">{{ $selectedOrder->customer_email }}</p>
                                <p class="text-sm text-gray-600">{{ $selectedOrder->customer_phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Garage</p>
                                <p class="text-sm text-gray-900">{{ $selectedOrder->agent->company_name ?? $selectedOrder->agent->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Service</p>
                                <p class="text-sm text-gray-900">{{ ucwords(str_replace('_', ' ', $selectedOrder->service_type)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Booking Type</p>
                                <p class="text-sm text-gray-900">{{ ucfirst($selectedOrder->booking_type) }}</p>
                                @if($selectedOrder->booking_type === 'scheduled' && $selectedOrder->scheduled_date)
                                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($selectedOrder->scheduled_date)->format('M d, Y') }} 
                                @if($selectedOrder->scheduled_time)
                                at {{ \Carbon\Carbon::parse($selectedOrder->scheduled_time)->format('h:i A') }}
                                @endif
                                </p>
                                @endif
                            </div>
                        </div>

                        @if($selectedOrder->vehicle_make || $selectedOrder->vehicle_model)
                        <div>
                            <p class="text-sm font-medium text-gray-700">Vehicle Information</p>
                            <p class="text-sm text-gray-900">
                                {{ $selectedOrder->vehicle_year }} {{ $selectedOrder->vehicle_make }} {{ $selectedOrder->vehicle_model }}
                                @if($selectedOrder->vehicle_registration)
                                ({{ $selectedOrder->vehicle_registration }})
                                @endif
                            </p>
                        </div>
                        @endif

                        @if($selectedOrder->service_description)
                        <div>
                            <p class="text-sm font-medium text-gray-700">Service Description</p>
                            <p class="text-sm text-gray-900">{{ $selectedOrder->service_description }}</p>
                        </div>
                        @endif

                        @if($selectedOrder->customer_notes)
                        <div>
                            <p class="text-sm font-medium text-gray-700">Customer Notes</p>
                            <p class="text-sm text-gray-900">{{ $selectedOrder->customer_notes }}</p>
                        </div>
                        @endif

                        @if($selectedOrder->quoted_price)
                        <div class="p-3 bg-purple-50 border border-purple-200 rounded-lg">
                            <p class="text-sm font-medium text-purple-900">Quoted Price</p>
                            <p class="text-lg font-bold text-purple-900">{{ $selectedOrder->currency }} {{ number_format($selectedOrder->quoted_price, 2) }}</p>
                            @if($selectedOrder->quotation_notes)
                            <p class="text-sm text-purple-700 mt-1">{{ $selectedOrder->quotation_notes }}</p>
                            @endif
                        </div>
                        @endif

                        @if($selectedOrder->rejection_reason)
                        <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm font-medium text-red-900">Rejection Reason</p>
                            <p class="text-sm text-red-800">{{ $selectedOrder->rejection_reason }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    @if($selectedOrder->status === 'pending')
                    <button wire:click="confirmOrder({{ $selectedOrder->id }})" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirm Order
                    </button>
                    <button wire:click="openQuoteModal({{ $selectedOrder->id }})" class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Send Quotation
                    </button>
                    <button wire:click="$set('rejectionReason', '')" onclick="$refs.rejectModal.showModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Reject
                    </button>
                    @endif
                    <button wire:click="closeDetailModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <dialog x-ref="rejectModal" class="rounded-lg shadow-xl p-6 max-w-md">
        <div class="space-y-4">
            <h3 class="text-lg font-semibold">Reject Order</h3>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason *</label>
                <textarea wire:model="rejectionReason" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"></textarea>
                @error('rejectionReason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="flex gap-3">
                <button wire:click="rejectOrder({{ $selectedOrder->id }})" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Confirm Rejection
                </button>
                <button onclick="$refs.rejectModal.close()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
            </div>
        </div>
    </dialog>
    @endif

    <!-- Quote Modal -->
    @if($showQuoteModal && $selectedOrder)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeQuoteModal"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="submitQuote">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Send Quotation
                            </h3>
                            <button type="button" wire:click="closeQuoteModal" class="text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quoted Price (TZS) *</label>
                                <input type="number" step="0.01" wire:model="quotedPrice" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                @error('quotedPrice') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quotation Notes</label>
                                <textarea wire:model="quotationNotes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Additional notes about the quotation..."></textarea>
                                @error('quotationNotes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Send Quotation
                        </button>
                        <button type="button" wire:click="closeQuoteModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
