<div class="p-6 space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Car Exchange Requests</h1>
        <p class="text-gray-600">Review and manage car exchange requests from customers.</p>
    </div>

    <!-- Filter Tabs -->
    <div class="flex space-x-2 border-b border-gray-200">
        <button wire:click="$set('filter', 'all')" class="px-4 py-2 {{ $filter === 'all' ? 'border-b-2 border-green-600 text-green-600 font-medium' : 'text-gray-600 hover:text-gray-900' }}">
            All
        </button>
        <button wire:click="$set('filter', 'pending')" class="px-4 py-2 {{ $filter === 'pending' ? 'border-b-2 border-green-600 text-green-600 font-medium' : 'text-gray-600 hover:text-gray-900' }}">
            Pending
        </button>
        <button wire:click="$set('filter', 'admin_approved')" class="px-4 py-2 {{ $filter === 'admin_approved' ? 'border-b-2 border-green-600 text-green-600 font-medium' : 'text-gray-600 hover:text-gray-900' }}">
            Approved
        </button>
        <button wire:click="$set('filter', 'sent_to_dealers')" class="px-4 py-2 {{ $filter === 'sent_to_dealers' ? 'border-b-2 border-green-600 text-green-600 font-medium' : 'text-gray-600 hover:text-gray-900' }}">
            Sent to Dealers
        </button>
        <button wire:click="$set('filter', 'completed')" class="px-4 py-2 {{ $filter === 'completed' ? 'border-b-2 border-green-600 text-green-600 font-medium' : 'text-gray-600 hover:text-gray-900' }}">
            Completed
        </button>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Vehicle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Desired Vehicle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quotations</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($requests as $r)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $r->customer_name }}</div>
                            <div class="text-sm text-gray-600">{{ $r->customer_email }}</div>
                            @if($r->location)
                                <div class="text-xs text-gray-500 mt-1">📍 {{ $r->location }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ $r->current_vehicle_make }} {{ $r->current_vehicle_model }}
                            </div>
                            <div class="text-xs text-gray-600">
                                {{ $r->current_vehicle_year }} · {{ number_format($r->current_vehicle_mileage ?? 0) }} km
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ $r->desiredMake?->name ?? 'Any' }} {{ $r->desiredModel?->name ?? '' }}
                            </div>
                            @if($r->max_budget)
                                <div class="text-xs text-gray-600">Budget: {{ number_format($r->max_budget) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($r->status === 'pending')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 font-medium text-xs">Pending</span>
                            @elseif($r->status === 'admin_approved')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-800 font-medium text-xs">Approved</span>
                            @elseif($r->status === 'sent_to_dealers')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800 font-medium text-xs">Sent to Dealers</span>
                            @elseif($r->status === 'completed')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 text-gray-800 font-medium text-xs">Completed</span>
                            @elseif($r->status === 'rejected')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-red-100 text-red-800 font-medium text-xs">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <span class="font-semibold">{{ $r->quotations_count }}</span> quotation(s)
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $r->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('admin.exchange-requests.detail', ['id' => $r->id]) }}" class="text-green-600 hover:text-green-800 font-medium">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-600">No exchange requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
