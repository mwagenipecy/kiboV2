{{-- Filter Modal Component --}}
@if($showFilters)
<div class="fixed inset-0 bg-black/50 bg-opacity-50 z-40 animate-fadeIn" wire:click="$set('showFilters', false)"></div>
<div class="fixed right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl z-60 overflow-y-auto animate-slideInRight">
    {{-- Header --}}
    <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
        <h2 class="text-2xl font-bold text-gray-900">Filter and sort</h2>
        <button wire:click="$set('showFilters', false)" class="w-10 h-10 flex items-center justify-center hover:bg-gray-100 rounded-full transition-colors">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    {{-- Content --}}
    <div class="px-6 py-4">
        {{-- Sort Section --}}
        <x-admin.trucks.filter-section 
            section="sort" 
            title="Sort" 
            :expanded="$expandedSections['sort'] ?? false">
            <div class="space-y-2 pl-8">
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="sortBy" type="radio" value="relevance" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">Relevance</span>
                </label>
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="sortBy" type="radio" value="price_low" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">Price: Low to High</span>
                </label>
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="sortBy" type="radio" value="price_high" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">Price: High to Low</span>
                </label>
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="sortBy" type="radio" value="year_new" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">Year: Newest first</span>
                </label>
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="sortBy" type="radio" value="mileage_low" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">Mileage: Low to High</span>
                </label>
            </div>
        </x-admin.trucks.filter-section>

        {{-- Status Section (Admin only) --}}
        <x-admin.trucks.filter-section 
            section="status" 
            title="Status" 
            :expanded="$expandedSections['status'] ?? false"
            >
            <div class="pl-8 space-y-2">
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="filterStatus" type="radio" value="" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">All Statuses</span>
                </label>
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="filterStatus" type="radio" value="pending" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">Pending</span>
                </label>
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="filterStatus" type="radio" value="awaiting_approval" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">Awaiting Approval</span>
                </label>
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="filterStatus" type="radio" value="approved" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">Approved</span>
                </label>
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="filterStatus" type="radio" value="hold" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">On Hold</span>
                </label>
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="filterStatus" type="radio" value="sold" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">Sold</span>
                </label>
            </div>
        </x-admin.trucks.filter-section>

        {{-- Origin Section (Admin only) --}}
        <x-admin.trucks.filter-section 
            section="origin" 
            title="Origin" 
            :expanded="$expandedSections['origin'] ?? false"
            >
            <div class="pl-8 space-y-2">
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="filterOrigin" type="radio" value="" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">All Origins</span>
                </label>
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="filterOrigin" type="radio" value="local" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">Local</span>
                </label>
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="filterOrigin" type="radio" value="international" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">International</span>
                </label>
            </div>
        </x-admin.trucks.filter-section>

        {{-- Make and Model Section --}}
        <x-admin.trucks.filter-section 
            section="makeModel" 
            title="Make and model" 
            :expanded="$expandedSections['makeModel'] ?? false"
            >
            <div class="space-y-3 pl-8">
                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Make</label>
                    <select wire:model.live="make" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Any</option>
                        @foreach($makes as $makeOption)
                            <option value="{{ $makeOption->id }}">{{ $makeOption->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if($make && $models->count() > 0)
                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Model</label>
                    <select wire:model.live="model" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Any</option>
                        @foreach($models as $modelOption)
                            <option value="{{ $modelOption->id }}">{{ $modelOption->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </x-admin.trucks.filter-section>

        {{-- Condition Section --}}
        <x-admin.trucks.filter-section 
            section="condition" 
            title="Condition" 
            :expanded="$expandedSections['condition'] ?? false"
            :subtitle="$condition ? ucfirst($condition) : null">
            <div class="pl-8 space-y-2">
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="condition" type="radio" value="" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">All</span>
                </label>
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="condition" type="radio" value="new" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">New</span>
                </label>
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="condition" type="radio" value="used" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">Used</span>
                </label>
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="condition" type="radio" value="certified_pre_owned" class="w-4 h-4 text-green-600">
                    <span class="text-gray-700">Certified Pre-Owned</span>
                </label>
            </div>
        </x-admin.trucks.filter-section>

        {{-- Price Section --}}
        <x-admin.trucks.filter-section 
            section="price" 
            title="Price" 
            subtitle="Pay in full"
            :expanded="$expandedSections['price'] ?? false"
            >
            <div class="pl-8 space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm text-gray-600 mb-1 block">Min price</label>
                        <input wire:model.live="minPrice" type="number" placeholder="No min" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 mb-1 block">Max price</label>
                        <input wire:model.live="maxPrice" type="number" placeholder="No max" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            </div>
        </x-admin.trucks.filter-section>

        {{-- Year Section --}}
        <x-admin.trucks.filter-section 
            section="year" 
            title="Year" 
            :expanded="$expandedSections['year'] ?? false"
            >
            <div class="pl-8 space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm text-gray-600 mb-1 block">Min year</label>
                        <input wire:model.live="minYear" type="number" placeholder="No min" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 mb-1 block">Max year</label>
                        <input wire:model.live="maxYear" type="number" placeholder="No max" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            </div>
        </x-admin.trucks.filter-section>

        {{-- Mileage Section --}}
        <x-admin.trucks.filter-section 
            section="mileage" 
            title="Mileage" 
            :expanded="$expandedSections['mileage'] ?? false"
            >
            <div class="pl-8 space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm text-gray-600 mb-1 block">Min mileage</label>
                        <input wire:model.live="minMileage" type="number" placeholder="No min" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 mb-1 block">Max mileage</label>
                        <input wire:model.live="maxMileage" type="number" placeholder="No max" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            </div>
        </x-admin.trucks.filter-section>

        {{-- Gearbox Section --}}
        <x-admin.trucks.filter-section 
            section="gearbox" 
            title="Gearbox" 
            :expanded="$expandedSections['gearbox'] ?? false"
            >
            <div class="pl-8 space-y-2">
                @foreach($availableTransmissions as $trans)
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="transmission" type="checkbox" value="{{ $trans }}" class="w-4 h-4 text-green-600 rounded">
                    <span class="text-gray-700">{{ ucfirst($trans) }}</span>
                </label>
                @endforeach
            </div>
        </x-admin.trucks.filter-section>

        {{-- Body Type Section --}}
        <x-admin.trucks.filter-section 
            section="bodyType" 
            title="Body type" 
            :expanded="$expandedSections['bodyType'] ?? false"
            >
            <div class="pl-8 space-y-2">
                @foreach($availableBodyTypes as $bodyType)
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="bodyType" type="checkbox" value="{{ $bodyType }}" class="w-4 h-4 text-green-600 rounded">
                    <span class="text-gray-700">{{ ucfirst($bodyType) }}</span>
                </label>
                @endforeach
            </div>
        </x-admin.trucks.filter-section>

        {{-- Fuel Type Section --}}
        <x-admin.trucks.filter-section 
            section="fuelType" 
            title="Fuel type" 
            :expanded="$expandedSections['fuelType'] ?? false"
            >
            <div class="pl-8 space-y-2">
                @foreach($availableFuelTypes as $fuelType)
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="fuelType" type="checkbox" value="{{ $fuelType }}" class="w-4 h-4 text-green-600 rounded">
                    <span class="text-gray-700">{{ ucfirst($fuelType) }}</span>
                </label>
                @endforeach
            </div>
        </x-admin.trucks.filter-section>

        {{-- Truck Type Section --}}
        <x-admin.trucks.filter-section 
            section="truckType" 
            title="Truck type" 
            :expanded="$expandedSections['truckType'] ?? false"
            >
            <div class="pl-8 space-y-2">
                @foreach($availableTruckTypes as $truckType)
                <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                    <input wire:model.live="truckType" type="checkbox" value="{{ $truckType }}" class="w-4 h-4 text-green-600 rounded">
                    <span class="text-gray-700">{{ ucfirst($truckType) }}</span>
                </label>
                @endforeach
            </div>
        </x-admin.trucks.filter-section>
    </div>

    {{-- Footer --}}
    <div class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4 flex items-center justify-between gap-4">
        <button wire:click="clearFilters" class="text-green-600 font-semibold hover:text-green-700">
            Clear all
        </button>
        <button wire:click="$set('showFilters', false)" class="px-8 py-3 bg-green-600 text-white rounded-full font-semibold hover:bg-green-700 transition-colors">
            Search {{ number_format($totalCount) }} trucks
        </button>
    </div>
</div>

{{-- Animations --}}
<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideInRight {
        from { transform: translateX(100%); }
        to { transform: translateX(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.2s ease-out;
    }
    .animate-slideInRight {
        animation: slideInRight 0.3s ease-out;
    }
</style>
@endif

