{{-- Filter Bar Component --}}
<div class="flex items-center gap-3 justify-between flex-wrap">
            {{-- Left side - Filter chips --}}
            <div class="flex items-center gap-3 flex-wrap">
                {{-- Condition Filter Chip --}}
                @if($condition)
                    <button wire:click="$toggle('showFilters')" class="px-6 py-2 bg-green-600 text-white rounded-full font-medium hover:bg-green-700 transition-colors">
                        {{ ucfirst($condition) }}
                    </button>
                @endif

                {{-- Make Filter Chip --}}
                @if($make)
                    @php
                        $makeName = $makes->firstWhere('id', $make)?->name ?? 'Make';
                    @endphp
                    <button wire:click="$toggle('showFilters')" class="px-6 py-2 bg-green-600 text-white rounded-full font-medium hover:bg-green-700 transition-colors">
                        {{ $makeName }}
                    </button>
                @endif

                {{-- Model Filter Chip --}}
                @if($model)
                    @php
                        $modelName = $models->firstWhere('id', $model)?->name ?? 'Model';
                    @endphp
                    <button wire:click="$toggle('showFilters')" class="px-6 py-2 bg-green-600 text-white rounded-full font-medium hover:bg-green-700 transition-colors">
                        {{ $modelName }}
                    </button>
                @endif

                {{-- Status Filter Chip (Admin only) --}}
                @if($filterStatus)
                    <button wire:click="$toggle('showFilters')" class="px-6 py-2 bg-green-600 text-white rounded-full font-medium hover:bg-green-700 transition-colors">
                        {{ ucfirst(str_replace('_', ' ', $filterStatus)) }}
                    </button>
                @endif

                {{-- Price Filter Chip (Always visible) --}}
                <button wire:click="$toggle('showFilters')" class="px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-full font-medium hover:border-gray-400 transition-colors">
                    Price
                </button>

                {{-- Year Filter Chip (Always visible) --}}
                <button wire:click="$toggle('showFilters')" class="px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-full font-medium hover:border-gray-400 transition-colors">
                    Year
                </button>

                {{-- Mileage Filter Chip (Always visible) --}}
                <button wire:click="$toggle('showFilters')" class="px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-full font-medium hover:border-gray-400 transition-colors">
                    Mileage
                </button>

                {{-- Gearbox Filter Chip (Always visible) --}}
                <button wire:click="$toggle('showFilters')" class="px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-full font-medium hover:border-gray-400 transition-colors">
                    Gearbox
                </button>

                {{-- Body Type Filter Chip (Always visible) --}}
                <button wire:click="$toggle('showFilters')" class="px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-full font-medium hover:border-gray-400 transition-colors">
                    Body type
                </button>

                {{-- Truck Type Filter Chip (Always visible) --}}
                <button wire:click="$toggle('showFilters')" class="px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-full font-medium hover:border-gray-400 transition-colors">
                    Truck Type
                </button>
            </div>

            {{-- Right side - Filter and sort button --}}
            <button wire:click="$toggle('showFilters')" class="px-6 py-2 bg-green-600 text-white rounded-full font-medium hover:bg-green-700 transition-colors flex items-center gap-2 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
                Filter and sort
            </button>
        </div>
</div>

