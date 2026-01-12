<div>
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Leasing Cars</h1>
                <p class="mt-2 text-gray-600">Manage your fleet of cars available for lease</p>
            </div>
            <a href="{{ route('admin.leasing-cars.create') }}" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Leasing Car
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Search by title, registration number, VIN, make, or model..." 
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <!-- Entity Filter -->
                <div>
                    <select wire:model.live="filterEntity" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Entities</option>
                        @foreach($entities as $entity)
                        <option value="{{ $entity->id }}">{{ $entity->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="flex overflow-x-auto border-b border-gray-200">
            <button 
                wire:click="$set('filterStatus', 'all')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filterStatus === 'all' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                All ({{ $counts['all'] }})
            </button>
            <button 
                wire:click="$set('filterStatus', 'pending')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filterStatus === 'pending' ? 'text-yellow-600 border-b-2 border-yellow-600 bg-yellow-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Pending ({{ $counts['pending'] }})
            </button>
            <button 
                wire:click="$set('filterStatus', 'available')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filterStatus === 'available' ? 'text-green-600 border-b-2 border-green-600 bg-green-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Available ({{ $counts['available'] }})
            </button>
            <button 
                wire:click="$set('filterStatus', 'leased')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filterStatus === 'leased' ? 'text-purple-600 border-b-2 border-purple-600 bg-purple-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Leased ({{ $counts['leased'] }})
            </button>
            <button 
                wire:click="$set('filterStatus', 'maintenance')" 
                class="px-6 py-3 text-sm font-medium whitespace-nowrap {{ $filterStatus === 'maintenance' ? 'text-orange-600 border-b-2 border-orange-600 bg-orange-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Maintenance ({{ $counts['maintenance'] }})
            </button>
        </div>
    </div>

    <!-- Cars Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($leasingCars as $car)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <!-- Car Image -->
            <div class="aspect-video bg-gray-100 relative">
                @if($car->image_front)
                    <img src="{{ asset('storage/' . $car->image_front) }}" alt="{{ $car->title }}" class="w-full h-full object-cover">
                @else
                    <div class="flex items-center justify-center h-full text-gray-400">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute top-2 right-2">
                    @if($car->status === 'available')
                        <span class="px-2.5 py-1 bg-green-600 text-white text-xs font-medium rounded-full">Available</span>
                    @elseif($car->status === 'leased')
                        <span class="px-2.5 py-1 bg-purple-600 text-white text-xs font-medium rounded-full">Leased</span>
                    @elseif($car->status === 'pending')
                        <span class="px-2.5 py-1 bg-yellow-600 text-white text-xs font-medium rounded-full">Pending</span>
                    @elseif($car->status === 'maintenance')
                        <span class="px-2.5 py-1 bg-orange-600 text-white text-xs font-medium rounded-full">Maintenance</span>
                    @else
                        <span class="px-2.5 py-1 bg-gray-600 text-white text-xs font-medium rounded-full">{{ ucfirst($car->status) }}</span>
                    @endif
                </div>
            </div>

            <!-- Car Details -->
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $car->title }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ $car->make->name }} {{ $car->model->name }} ({{ $car->year }})</p>
                
                <!-- Pricing -->
                <div class="mb-3 pb-3 border-b border-gray-200">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm text-gray-600">Daily Rate:</span>
                        <span class="text-lg font-bold text-blue-600">{{ $car->currency }} {{ number_format($car->daily_rate, 0) }}</span>
                    </div>
                    @if($car->weekly_rate)
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>Weekly:</span>
                        <span>{{ $car->currency }} {{ number_format($car->weekly_rate, 0) }}</span>
                    </div>
                    @endif
                </div>

                <!-- Quick Info -->
                <div class="grid grid-cols-3 gap-2 mb-3 text-xs text-gray-600">
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Min {{ $car->min_lease_days }}d</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>{{ $car->min_driver_age }}+ yrs</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>{{ $car->view_count }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.leasing-cars.view', $car->id) }}" class="flex-1 px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors text-center">
                        View Details
                    </a>
                    <a href="{{ route('admin.leasing-cars.edit', $car->id) }}" class="px-3 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    
                    @if($car->status === 'pending')
                    <button wire:click="approveCar({{ $car->id }})" wire:confirm="Are you sure you want to approve this car?" class="px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                    @endif
                    
                    <button wire:click="deleteCar({{ $car->id }})" wire:confirm="Are you sure you want to delete this leasing car?" class="px-3 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No leasing cars found</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by adding your first leasing car.</p>
            <div class="mt-6">
                <a href="{{ route('admin.leasing-cars.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Leasing Car
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $leasingCars->links() }}
    </div>
</div>
