<div>
    <!-- Page Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Vehicle Leasing</h1>
            <p class="mt-1 text-sm text-gray-600">Manage vehicle lease offerings</p>
        </div>
        <a href="{{ route('admin.leasing.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create New Lease
        </a>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" wire:model.live="search" placeholder="Search by title or vehicle..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>

            <!-- Entity Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dealer/Entity</label>
                <select wire:model.live="filterEntity" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">All Entities</option>
                    @foreach($entities as $entity)
                        <option value="{{ $entity->id }}">{{ $entity->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sort By -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select wire:model.live="sortBy" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="created_at">Latest</option>
                    <option value="monthly_payment">Monthly Payment</option>
                    <option value="lease_term_months">Lease Term</option>
                    <option value="priority">Priority</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="mb-6 flex flex-wrap gap-2">
        <button wire:click="$set('filterStatus', 'all')" class="px-4 py-2 rounded-lg font-medium {{ $filterStatus === 'all' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} border border-gray-200 transition-colors">
            All ({{ $counts['all'] }})
        </button>
        <button wire:click="$set('filterStatus', 'active')" class="px-4 py-2 rounded-lg font-medium {{ $filterStatus === 'active' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} border border-gray-200 transition-colors">
            Active ({{ $counts['active'] }})
        </button>
        <button wire:click="$set('filterStatus', 'inactive')" class="px-4 py-2 rounded-lg font-medium {{ $filterStatus === 'inactive' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} border border-gray-200 transition-colors">
            Inactive ({{ $counts['inactive'] }})
        </button>
        <button wire:click="$set('filterStatus', 'reserved')" class="px-4 py-2 rounded-lg font-medium {{ $filterStatus === 'reserved' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} border border-gray-200 transition-colors">
            Reserved ({{ $counts['reserved'] }})
        </button>
    </div>

    <!-- Leases List -->
    @if($leases->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lease Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Term</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($leases as $lease)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($lease->image_front)
                                            <img src="{{ asset('storage/' . $lease->image_front) }}" alt="{{ $lease->vehicle_title }}" class="w-16 h-12 object-cover rounded-lg mr-3">
                                        @else
                                            <div class="w-16 h-12 bg-gray-200 rounded-lg mr-3 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $lease->vehicle_title }}</div>
                                            <div class="text-xs text-gray-500">{{ $lease->vehicle_year }} {{ $lease->vehicle_make }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $lease->lease_title }}</div>
                                    @if($lease->is_featured)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                            ⭐ Featured
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-green-600">${{ number_format($lease->monthly_payment, 2) }}</div>
                                    <div class="text-xs text-gray-500">per month</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $lease->lease_term_months }} months</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $lease->entity->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $lease->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $lease->status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $lease->status === 'reserved' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ ucfirst($lease->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.leasing.view', $lease->id) }}" class="text-green-600 hover:text-green-900">View</a>
                                        <a href="{{ route('admin.leasing.edit', $lease->id) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        <button wire:click="toggleStatus({{ $lease->id }})" class="text-purple-600 hover:text-purple-900">
                                            {{ $lease->status === 'active' ? 'Deactivate' : 'Activate' }}
                                        </button>
                                        <button wire:click="toggleFeatured({{ $lease->id }})" class="text-yellow-600 hover:text-yellow-900">
                                            {{ $lease->is_featured ? 'Unfeature' : 'Feature' }}
                                        </button>
                                        <button wire:click="deleteLease({{ $lease->id }})" wire:confirm="Are you sure you want to delete this lease?" class="text-red-600 hover:text-red-900">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $leases->links() }}
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No leases found</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new lease offering.</p>
            <div class="mt-6">
                <a href="{{ route('admin.leasing.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create New Lease
                </a>
            </div>
        </div>
    @endif
</div>
