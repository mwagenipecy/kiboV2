<div>
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Evaluation Orders</h1>
        <p class="mt-2 text-gray-600">Manage vehicle valuation report requests and issue certificates</p>
    </div>

    @if(session()->has('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if(session()->has('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Requests</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $counts['all'] }}</p>
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
                    <p class="text-sm text-gray-600">Pending Payment</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $counts['pending-payment'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Paid - Issue Report</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1">{{ $counts['paid'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $counts['completed'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Filter Tabs -->
            <div class="flex-1">
                <div class="flex flex-wrap gap-2">
                    <button wire:click="setFilter('all')" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $filter === 'all' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All ({{ $counts['all'] }})
                    </button>
                    <button wire:click="setFilter('pending-payment')" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $filter === 'pending-payment' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Pending Payment ({{ $counts['pending-payment'] }})
                    </button>
                    <button wire:click="setFilter('paid')" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $filter === 'paid' ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Paid ({{ $counts['paid'] }})
                    </button>
                    <button wire:click="setFilter('completed')" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $filter === 'completed' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Completed ({{ $counts['completed'] }})
                    </button>
                </div>
            </div>

            <!-- Search -->
            <div class="md:w-64">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search orders..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                            @if($order->isCompleted() && $order->completion_data)
                            <div class="text-xs text-green-600 font-medium">{{ $order->completion_data['certificate_number'] ?? '' }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $order->vehicle->year }} {{ $order->vehicle->make->name ?? '' }} {{ $order->vehicle->model->name ?? '' }}</div>
                            <div class="text-xs text-gray-500">£{{ number_format($order->vehicle->price, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">£{{ number_format($order->fee, 2) }}</div>
                            @if($order->payment_completed)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Paid
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                Unpaid
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($order->status->value === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status->value === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status->value === 'completed') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('M d, Y') }}
                            <div class="text-xs text-gray-400">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                @if($order->payment_completed && !$order->isCompleted())
                                <button wire:click="openIssueModal({{ $order->id }})" class="px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition-colors">
                                    Issue Report
                                </button>
                                @endif
                                
                                @if($order->isCompleted() && $order->completion_data)
                                <button wire:click="downloadCertificate({{ $order->id }})" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                                    Download PDF
                                </button>
                                @endif
                                
                                <a href="{{ route('admin.orders.evaluations.view', $order->id) }}" class="px-3 py-1.5 border border-gray-300 text-gray-700 text-xs font-medium rounded hover:bg-gray-50 transition-colors inline-block">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No evaluation orders found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Issue Report Modal -->
    @if($showIssueModal && $selectedOrder)
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" wire:click="closeIssueModal">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
            <!-- Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Issue Evaluation Report</h2>
                    <p class="text-sm text-gray-600 mt-1">Order #{{ $selectedOrder->order_number }}</p>
                </div>
                <button wire:click="closeIssueModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6">
                <!-- Vehicle Info -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Vehicle Information</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Vehicle:</p>
                            <p class="font-medium text-gray-900">{{ $selectedOrder->vehicle->year }} {{ $selectedOrder->vehicle->make->name ?? '' }} {{ $selectedOrder->vehicle->model->name ?? '' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Asking Price:</p>
                            <p class="font-medium text-gray-900">£{{ number_format($selectedOrder->vehicle->price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Mileage:</p>
                            <p class="font-medium text-gray-900">{{ number_format($selectedOrder->vehicle->mileage) }} miles</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Condition:</p>
                            <p class="font-medium text-gray-900 capitalize">{{ $selectedOrder->vehicle->condition }}</p>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-blue-900 mb-2">Customer</h3>
                    <p class="text-sm text-blue-800">{{ $selectedOrder->user->name }} ({{ $selectedOrder->user->email }})</p>
                </div>

                <!-- Order Details -->
                @if($selectedOrder->order_data)
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Request Details</h3>
                    <dl class="space-y-2 text-sm">
                        @foreach($selectedOrder->order_data as $key => $value)
                        <div class="flex justify-between">
                            <dt class="text-gray-600 capitalize">{{ str_replace('_', ' ', $key) }}:</dt>
                            <dd class="font-medium text-gray-900 capitalize">{{ $value }}</dd>
                        </div>
                        @endforeach
                    </dl>
                </div>
                @endif

                <!-- Form -->
                <form wire:submit.prevent="issueReport" class="space-y-6">
                    <!-- Valuation Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Valuation Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">£</span>
                            <input type="number" wire:model="valuationAmount" step="0.01" min="0" class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="0.00">
                        </div>
                        @error('valuationAmount') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Enter the current market valuation for this vehicle</p>
                    </div>

                    <!-- Report Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Report Notes (Optional)</label>
                        <textarea wire:model="reportNotes" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none" placeholder="Additional notes or observations about the valuation..."></textarea>
                        @error('reportNotes') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">{{ strlen($reportNotes) }}/1000 characters</p>
                    </div>

                    <!-- Certificate Info -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="font-semibold text-green-900 mb-2">Certificate Information</h4>
                        <ul class="text-sm text-green-800 space-y-1">
                            <li>• Certificate will be automatically generated</li>
                            <li>• Valid for 2 weeks from issue date</li>
                            <li>• Customer will receive PDF via email</li>
                            <li>• Certificate number will be unique and traceable</li>
                        </ul>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3">
                        <button type="button" wire:click="closeIssueModal" class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                            Issue Report & Certificate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

