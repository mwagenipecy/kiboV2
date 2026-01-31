<div>
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Financing Applications</h1>
        <p class="mt-2 text-gray-600">Manage customer financing applications and lender requests</p>
    </div>

    @if(session()->has('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session()->has('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        {{ session('error') }}
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
                            placeholder="Search by order number, customer, or vehicle..." 
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex overflow-x-auto border-b border-gray-200">
            <button 
                wire:click="setFilter('all')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filter === 'all' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                All Applications
                <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $filter === 'all' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
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
                wire:click="setFilter('approved')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filter === 'approved' ? 'text-green-600 border-b-2 border-green-600 bg-green-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Approved
                <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $filter === 'approved' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $counts['approved'] }}
                </span>
            </button>
            <button 
                wire:click="setFilter('completed')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filter === 'completed' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Completed
                <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $filter === 'completed' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $counts['completed'] }}
                </span>
            </button>
            <button 
                wire:click="setFilter('rejected')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filter === 'rejected' ? 'text-red-600 border-b-2 border-red-600 bg-red-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Rejected
                <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $filter === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $counts['rejected'] }}
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lender</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Amount</th>
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
                            <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->vehicle->full_name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">TZS {{ number_format($order->vehicle->price ?? 0, 0) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->order_data['lender_name'] ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $order->order_data['criteria_name'] ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">TZS {{ number_format($order->order_data['loan_amount'] ?? 0, 2) }}</div>
                            <div class="text-sm text-gray-500">{{ $order->order_data['loan_term_months'] ?? 0 }} months</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $order->status->value === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status->value === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status->value === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status->value === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $order->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.orders.financing.detail', $order->id) }}" 
                                   class="p-2 text-green-600 hover:text-green-900 hover:bg-green-50 rounded-lg transition-colors" 
                                   title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
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
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No financing applications found</h3>
            <p class="text-gray-600">
                @if($search)
                    No applications match your search criteria.
                @else
                    There are no {{ $filter !== 'all' ? $filter : '' }} applications at the moment.
                @endif
            </p>
        </div>
        @endif
    </div>
</div>
