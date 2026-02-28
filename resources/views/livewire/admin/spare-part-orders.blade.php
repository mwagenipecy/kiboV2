<div class="px-6 py-4">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Spare Part Orders</h1>
        <p class="text-sm text-gray-600 mt-1">Manage spare part requests and submit quotations</p>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button 
                wire:click="setActiveTab('open')"
                class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'open' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                style="{{ $activeTab === 'open' ? 'border-color: #009866; color: #009866;' : '' }}"
            >
                Open Requests
                @if($openRequestsCount > 0)
                <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full {{ $activeTab === 'open' ? 'text-white' : 'bg-gray-100 text-gray-600' }}" style="{{ $activeTab === 'open' ? 'background-color: #009866;' : '' }}">{{ $openRequestsCount }}</span>
                @endif
            </button>
            <button 
                wire:click="setActiveTab('my_quotations')"
                class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'my_quotations' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                style="{{ $activeTab === 'my_quotations' ? 'border-color: #009866; color: #009866;' : '' }}"
            >
                My Quotations
                @if($myQuotationsCount > 0)
                <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full {{ $activeTab === 'my_quotations' ? 'text-white' : 'bg-gray-100 text-gray-600' }}" style="{{ $activeTab === 'my_quotations' ? 'background-color: #009866;' : '' }}">{{ $myQuotationsCount }}</span>
                @endif
            </button>
            <button 
                wire:click="setActiveTab('all')"
                class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $activeTab === 'all' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                style="{{ $activeTab === 'all' ? 'border-color: #009866; color: #009866;' : '' }}"
            >
                All Orders
                @if($allOrdersCount > 0)
                <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full {{ $activeTab === 'all' ? 'text-white' : 'bg-gray-100 text-gray-600' }}" style="{{ $activeTab === 'all' ? 'background-color: #009866;' : '' }}">{{ $allOrdersCount }}</span>
                @endif
            </button>
        </nav>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Search by order number, customer name, or part..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                    style="focus:ring-color: #009866;"
                >
            </div>
            <div>
                <select 
                    wire:model.live="channelFilter" 
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                >
                    <option value="">All Channels</option>
                    <option value="portal">Portal</option>
                    <option value="whatsapp">WhatsApp</option>
                </select>
            </div>
            @if($activeTab === 'all')
            <div>
                <select 
                    wire:model.live="statusFilter" 
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                >
                    <option value="">All Statuses</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>
    </div>

    {{-- Open Requests Tab --}}
    @if($activeTab === 'open')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($openRequests->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Channel</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle / Part</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quotations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($openRequests as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $order->order_number }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(($order->order_channel ?? 'portal') === 'whatsapp')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">WhatsApp</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Portal</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $order->vehicleMake->name ?? 'N/A' }} {{ $order->vehicleModel->name ?? '' }}</div>
                            <div class="text-sm text-gray-500">{{ $order->part_name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $order->delivery_city ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $order->delivery_region ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $order->quotations->count() }} quote(s)
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.spare-part-orders.show', $order) }}" class="inline-flex items-center px-3 py-1.5 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors" title="View order details">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View
                                </a>
                                <button
                                    wire:click="openQuoteModal({{ $order->id }})"
                                    class="inline-flex items-center px-3 py-1.5 text-white text-sm font-medium rounded-lg transition-colors"
                                    style="background-color: #009866;"
                                    onmouseover="this.style.backgroundColor='#007a52'"
                                    onmouseout="this.style.backgroundColor='#009866'"
                                    title="Submit quotation"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Submit Quote
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $openRequests->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No open requests</h3>
            <p class="mt-1 text-sm text-gray-500">There are no spare part requests waiting for quotations.</p>
        </div>
        @endif
    </div>
    @endif

    {{-- My Quotations Tab --}}
    @if($activeTab === 'my_quotations')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($myQuotations->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Channel</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">My Quote</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($myQuotations as $quotation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $quotation->order->order_number }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(($quotation->order->order_channel ?? 'portal') === 'whatsapp')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">WhatsApp</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Portal</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $quotation->order->customer_name }}</div>
                            <div class="text-sm text-gray-500">{{ $quotation->order->customer_email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $quotation->order->part_name }}</div>
                            <div class="text-sm text-gray-500">{{ $quotation->order->vehicleMake->name ?? '' }} {{ $quotation->order->vehicleModel->name ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold" style="color: #009866;">{{ $quotation->currency }} {{ number_format($quotation->quoted_price, 2) }}</div>
                            @if($quotation->estimated_days)
                            <div class="text-xs text-gray-500">{{ $quotation->estimated_days }} days delivery</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($quotation->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($quotation->status === 'accepted') bg-green-100 text-green-800
                                @elseif($quotation->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($quotation->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $quotation->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.spare-part-orders.show', $quotation->order) }}" class="inline-flex items-center px-3 py-1.5 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors" title="View order details">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View
                                </a>
                                @if($quotation->status === 'accepted')
                                <a href="{{ route('admin.spare-part-orders.show', $quotation->order) }}" class="inline-flex items-center px-3 py-1.5 text-white text-sm font-medium rounded-lg transition-colors" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'" title="Manage order">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Manage Order
                                </a>
                                @else
                                <span class="text-sm text-gray-500">Awaiting response</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $myQuotations->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No quotations yet</h3>
            <p class="mt-1 text-sm text-gray-500">You haven't submitted any quotations. Check the "Open Requests" tab to submit quotes.</p>
        </div>
        @endif
    </div>
    @endif

    {{-- All Orders Tab --}}
    @if($activeTab === 'all')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($allOrders->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Channel</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quotations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($allOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $order->order_number }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(($order->order_channel ?? 'portal') === 'whatsapp')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">WhatsApp</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Portal</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $order->part_name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->vehicleMake->name ?? '' }} {{ $order->vehicleModel->name ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $order->quotations->count() }} quote(s)
                            </span>
                            @if($order->acceptedQuotation)
                            <div class="text-xs text-green-600 mt-1">Won by: {{ $order->acceptedQuotation->agent->name ?? 'N/A' }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'quoted') bg-purple-100 text-purple-800
                                @elseif($order->status === 'accepted') bg-green-100 text-green-800
                                @elseif(in_array($order->status, ['awaiting_payment', 'payment_submitted'])) bg-orange-100 text-orange-800
                                @elseif(in_array($order->status, ['payment_verified', 'shipped', 'delivered', 'completed'])) bg-green-100 text-green-800
                                @elseif($order->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $order->status_label ?? ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.spare-part-orders.show', $order) }}" class="inline-flex items-center px-3 py-1.5 text-white text-sm font-medium rounded-lg transition-colors" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $allOrders->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No orders found</h3>
            <p class="mt-1 text-sm text-gray-500">No spare part orders match your criteria.</p>
        </div>
        @endif
    </div>
    @endif

    {{-- Quote Modal --}}
    @if($showQuoteModal && $selectedOrder)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" style="display: block !important;">
        <div class="fixed inset-0 bg-black/50 z-[9998]" wire:click="closeQuoteModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 z-[9999]" wire:click.stop>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Submit Quotation</h3>
                
                {{-- Order Details --}}
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Order Details</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Order #:</span>
                            <span class="font-medium text-gray-900 ml-1">{{ $selectedOrder->order_number }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Customer:</span>
                            <span class="font-medium text-gray-900 ml-1">{{ $selectedOrder->customer_name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Vehicle:</span>
                            <span class="font-medium text-gray-900 ml-1">{{ $selectedOrder->vehicleMake->name ?? 'N/A' }} {{ $selectedOrder->vehicleModel->name ?? '' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Part:</span>
                            <span class="font-medium text-gray-900 ml-1">{{ $selectedOrder->part_name }}</span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-500">Delivery:</span>
                            <span class="font-medium text-gray-900 ml-1">{{ $selectedOrder->delivery_address }}, {{ $selectedOrder->delivery_city }}</span>
                        </div>
                    </div>
                    @if($selectedOrder->description)
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <span class="text-gray-500 text-sm">Description:</span>
                        <p class="text-gray-900 text-sm mt-1">{{ $selectedOrder->description }}</p>
                    </div>
                    @endif
                </div>
                
                <form wire:submit.prevent="submitQuotation" class="space-y-4">
                    @if($isAdmin)
                    <div>
                        @if($sparePartAgents->isNotEmpty())
                        <label class="block text-sm font-medium text-gray-700 mb-2">Submit quote as (Agent) *</label>
                        <select wire:model="selectedAgentId" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                            <option value="">Select agent...</option>
                            @foreach($sparePartAgents as $a)
                                <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->company_name ?? $a->email }})</option>
                            @endforeach
                        </select>
                        @error('selectedAgentId') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        @else
                        <p class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3">No spare part agents available. Please add agents from <strong>Registration → Agents</strong> (with type Spare Part) first.</p>
                        @endif
                    </div>
                    @endif
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quoted Price *</label>
                            <input type="number" step="0.01" wire:model="quotedPrice" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent" placeholder="0.00">
                            @error('quotedPrice') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Currency *</label>
                            <select wire:model="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                                <option value="TZS">TZS</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="KES">KES</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Delivery (Days)</label>
                        <input type="number" wire:model="estimatedDays" min="1" max="365" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent" placeholder="e.g., 7">
                        @error('estimatedDays') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quotation Notes</label>
                        <textarea wire:model="quotationNotes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent resize-none" placeholder="Additional details about your quotation, availability, warranty, etc."></textarea>
                    </div>
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <button type="submit" wire:loading.attr="disabled" wire:target="submitQuotation" class="flex-1 inline-flex justify-center items-center px-4 py-2 text-white font-medium rounded-lg transition-colors disabled:opacity-50" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                            <span wire:loading.remove wire:target="submitQuotation">Submit Quotation</span>
                            <span wire:loading wire:target="submitQuotation" class="flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Submitting...
                            </span>
                        </button>
                        <button type="button" wire:click="closeQuoteModal" class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Success Modal --}}
    @if($showSuccessModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" style="display: block !important;">
        <div class="fixed inset-0 bg-black/50 z-[9998]" wire:click="closeSuccessModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 z-[9999]" wire:click.stop>
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full mb-4" style="background-color: rgba(0, 152, 102, 0.1);">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #009866;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Success!</h3>
                    <p class="text-gray-600 mb-6">{{ $successMessage }}</p>
                    <button wire:click="closeSuccessModal" class="px-6 py-2 text-white font-medium rounded-lg transition-colors" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Error Modal --}}
    @if($showErrorModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" style="display: block !important;">
        <div class="fixed inset-0 bg-black/50 z-[9998]" wire:click="closeErrorModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 z-[9999]" wire:click.stop>
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Error</h3>
                    <p class="text-gray-600 mb-6">{{ $errorMessage }}</p>
                    <button wire:click="closeErrorModal" class="px-6 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
