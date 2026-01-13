<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Adverts</h1>
        <p class="text-gray-600">Manage your vehicle listings</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter and Actions -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex gap-2">
            <button wire:click="$set('filterStatus', 'all')" class="px-4 py-2 rounded-lg font-medium transition-colors {{ $filterStatus === 'all' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                All
            </button>
            <button wire:click="$set('filterStatus', 'pending')" class="px-4 py-2 rounded-lg font-medium transition-colors {{ $filterStatus === 'pending' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Pending
            </button>
            <button wire:click="$set('filterStatus', 'active')" class="px-4 py-2 rounded-lg font-medium transition-colors {{ $filterStatus === 'active' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Active
            </button>
            <button wire:click="$set('filterStatus', 'sold')" class="px-4 py-2 rounded-lg font-medium transition-colors {{ $filterStatus === 'sold' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Sold
            </button>
        </div>
        <a href="{{ route('cars.list-vehicle') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors">
            + Create New Listing
        </a>
    </div>

    <!-- Vehicles Table -->
    @if($vehicles->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Condition</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($vehicles as $vehicle)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($vehicle->image_front)
                                            <img src="{{ asset('storage/' . $vehicle->image_front) }}" alt="{{ $vehicle->title }}" class="h-12 w-12 rounded object-cover mr-3">
                                        @else
                                            <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center mr-3">
                                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $vehicle->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $vehicle->make->name ?? '' }} {{ $vehicle->model->name ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $vehicle->year }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $vehicle->condition === 'new' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($vehicle->condition) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $vehicle->currency }} {{ number_format($vehicle->price, 2) }}</div>
                                    @if($vehicle->negotiable)
                                        <div class="text-xs text-gray-500">Negotiable</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusValue = $vehicle->status instanceof \App\Enums\VehicleStatus ? $vehicle->status->value : $vehicle->status;
                                    @endphp
                                    @if($statusValue === 'approved')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    @elseif($statusValue === 'pending' || $statusValue === 'awaiting_approval')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $vehicle->status_label }}</span>
                                    @elseif($statusValue === 'sold')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Sold</span>
                                    @elseif($statusValue === 'hold')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">On Hold</span>
                                    @elseif($statusValue === 'removed')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Removed</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $vehicle->status_label }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $vehicle->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $vehicle->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('cars.search') }}?vehicle_id={{ $vehicle->id }}" class="text-green-600 hover:text-green-900 font-medium">
                                            View
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <button wire:click="delete({{ $vehicle->id }})" wire:confirm="Are you sure you want to delete this listing?" class="text-red-600 hover:text-red-900 font-medium">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $vehicles->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No listings found</h3>
            <p class="text-gray-600 mb-6">You haven't created any vehicle listings yet.</p>
            <a href="{{ route('cars.list-vehicle') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors">
                Create Your First Listing
            </a>
        </div>
    @endif
</div>
