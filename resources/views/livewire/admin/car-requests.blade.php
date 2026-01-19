<div class="p-6 space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Find-me-a-car Requests</h1>
        <p class="text-gray-600">View customer requests and submit offers on their behalf.</p>
    </div>

    @if (session()->has('admin_offer_success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('admin_offer_success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offers</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($requests as $r)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ $r->make?->name ?? 'Any make' }} {{ $r->model?->name ?? '' }}
                            </div>
                            <div class="text-sm text-gray-600 mt-1">
                                @if($r->min_year || $r->max_year) Year: {{ $r->min_year ?? 'Any' }} - {{ $r->max_year ?? 'Any' }} · @endif
                                @if($r->max_budget) Budget: Up to {{ number_format($r->max_budget) }} @endif
                            </div>
                            @if($r->location)
                                <div class="text-xs text-gray-500 mt-1">📍 {{ $r->location }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $r->customer_name }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($r->status === 'closed')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 text-gray-800 font-medium text-xs">Closed</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800 font-medium text-xs">Open</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <span class="font-semibold">{{ $r->offers_count }}</span> offer(s)
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $r->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('admin.car-requests.view', ['id' => $r->id]) }}" class="text-green-600 hover:text-green-800 font-medium">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-600">No requests yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
