<div>
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Lending Criteria</h1>
                <p class="mt-2 text-gray-600">Manage lending rules and requirements for lenders</p>
            </div>
            <a href="{{ route('admin.lending-criteria.create') }}" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Criteria
            </a>
        </div>
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
            <div class="grid grid-cols-1 {{ $userIsAdmin ? 'md:grid-cols-3' : 'md:grid-cols-1' }} gap-4">
                <!-- Search -->
                <div class="{{ $userIsAdmin ? 'md:col-span-2' : '' }}">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Search by name, description, or lender..." 
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>
                
                <!-- Entity Filter (only for admin) -->
                @if($userIsAdmin)
                <div>
                    <select wire:model.live="filterEntity" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">All Lenders</option>
                        @foreach($entities as $entity)
                        <option value="{{ $entity->id }}">{{ $entity->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="flex overflow-x-auto border-b border-gray-200">
            <button 
                wire:click="$set('filterStatus', 'all')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filterStatus === 'all' ? 'text-green-600 border-b-2 border-green-600 bg-green-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                All Criteria
            </button>
            <button 
                wire:click="$set('filterStatus', 'active')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filterStatus === 'active' ? 'text-green-600 border-b-2 border-green-600 bg-green-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Active
            </button>
            <button 
                wire:click="$set('filterStatus', 'inactive')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filterStatus === 'inactive' ? 'text-red-600 border-b-2 border-red-600 bg-red-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Inactive
            </button>
        </div>
    </div>

    <!-- Criteria List -->
    <div class="space-y-4">
        @forelse($criteria as $item)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $item->name }}</h3>
                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">{{ $item->entity->name }}</p>
                    @if($item->description)
                    <p class="text-sm text-gray-700">{{ $item->description }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.lending-criteria.view', $item->id) }}" class="p-2 text-green-600 hover:text-green-700 transition-colors" title="View Details">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </a>
                    <button 
                        wire:click="toggleStatus({{ $item->id }})" 
                        class="p-2 {{ $item->is_active ? 'text-gray-600 hover:text-gray-900' : 'text-green-600 hover:text-green-700' }} transition-colors"
                        title="{{ $item->is_active ? 'Deactivate' : 'Activate' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($item->is_active)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @endif
                        </svg>
                    </button>
                    <a href="{{ route('admin.lending-criteria.edit', $item->id) }}" class="p-2 text-blue-600 hover:text-blue-700 transition-colors" title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <button 
                        wire:click="deleteCriteria({{ $item->id }})"
                        wire:confirm="Are you sure you want to delete this lending criteria?"
                        class="p-2 text-red-600 hover:text-red-700 transition-colors" 
                        title="Delete">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-gray-600">Interest Rate</p>
                    <p class="font-semibold text-gray-900">{{ number_format($item->interest_rate, 2) }}%</p>
                </div>
                <div>
                    <p class="text-gray-600">Down Payment</p>
                    <p class="font-semibold text-gray-900">{{ number_format($item->down_payment_percentage, 0) }}%</p>
                </div>
                <div>
                    <p class="text-gray-600">Loan Term</p>
                    <p class="font-semibold text-gray-900">{{ $item->min_loan_term_months }}-{{ $item->max_loan_term_months }} months</p>
                </div>
                <div>
                    <p class="text-gray-600">Vehicle Price Range</p>
                    <p class="font-semibold text-gray-900">
                        £{{ $item->min_vehicle_price ? number_format($item->min_vehicle_price) : '0' }} - 
                        £{{ $item->max_vehicle_price ? number_format($item->max_vehicle_price) : '∞' }}
                    </p>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No lending criteria found</h3>
            <p class="text-gray-600 mb-4">
                @if($search || $filterEntity || $filterStatus !== 'all')
                    No criteria match your filters. Try adjusting your search.
                @else
                    Get started by creating your first lending criteria.
                @endif
            </p>
            @if(!$search && !$filterEntity && $filterStatus === 'all')
            <a href="{{ route('admin.lending-criteria.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add First Criteria
            </a>
            @endif
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($criteria->hasPages())
    <div class="mt-6">
        {{ $criteria->links() }}
    </div>
    @endif
</div>

