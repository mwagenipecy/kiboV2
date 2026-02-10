<div class="p-6 space-y-6">
<div>
        <h1 class="text-2xl font-bold text-gray-900">Car Exchange Requests</h1>
        <p class="text-gray-600">View exchange requests and submit quotations to customers.</p>
    </div>

    @if (session()->has('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Vehicle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Desired Vehicle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($requests as $r)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $r->customer_name }}</div>
                            <div class="text-sm text-gray-600">{{ $r->customer_email }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            {{ $r->current_vehicle_make }} {{ $r->current_vehicle_model }}<br>
                            <span class="text-gray-600">{{ $r->current_vehicle_year }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            {{ $r->desiredMake?->name ?? 'Any' }} {{ $r->desiredModel?->name ?? '' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($r->status === 'sent_to_dealers')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800 font-medium text-xs">Active</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-800 font-medium text-xs">Approved</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('dealer.exchange-requests.quotation', ['id' => $r->id]) }}" class="text-green-600 hover:text-green-800 font-medium">
                                Send Quotation
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-600">No exchange requests available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
