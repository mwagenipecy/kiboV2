<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Import Requests</h1>
        <p class="mt-1 text-sm text-gray-600">Track your Agiza/Import requests</p>
    </div>

    {{-- Notifications --}}
    @if($notifications->isNotEmpty())
    <div class="mb-6 space-y-3">
        @foreach($notifications as $notification)
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-blue-900">{{ $notification->title }}</p>
                    <p class="mt-1 text-sm text-blue-700">{{ $notification->message }}</p>
                    @if($notification->type === 'quotation_sent' && $notification->data)
                    <div class="mt-2 text-sm text-blue-800">
                        <strong>Import Cost:</strong> {{ number_format($notification->data['import_cost'], 2) }} {{ $notification->data['currency'] }}<br>
                        <strong>Total Cost:</strong> {{ number_format($notification->data['total_cost'], 2) }} {{ $notification->data['currency'] }}
                    </div>
                    @endif
                    <p class="mt-1 text-xs text-blue-600">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Filters --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input type="text" wire:model.live="search" placeholder="Search by request number, make, or model..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" />
        </div>
        <div class="w-full sm:w-48">
            <select wire:model.live="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="under_review">Under Review</option>
                <option value="quote_provided">Quote Provided</option>
                <option value="accepted">Accepted</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    {{-- Requests List --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($requests->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No requests found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by submitting your first import request.</p>
                <div class="mt-6">
                    <a href="{{ route('agiza-import.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        Submit New Request
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quotation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($requests as $request)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $request->request_number }}</div>
                                <div class="text-xs text-gray-500">{{ $request->request_type === 'with_link' ? 'With Link' : 'Dealer Contacted' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $request->vehicle_make }} {{ $request->vehicle_model }}</div>
                                @if($request->vehicle_year)
                                <div class="text-xs text-gray-500">{{ $request->vehicle_year }} • {{ ucfirst($request->vehicle_condition) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->source_country }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($request->quoted_total_cost)
                                <div class="text-sm font-semibold text-green-700">{{ number_format($request->quoted_total_cost, 0) }} {{ $request->quote_currency }}</div>
                                <div class="text-xs text-gray-500">Total Cost</div>
                                @else
                                <span class="text-xs text-gray-400">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $request->statusColor }}">
                                    {{ $request->statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
