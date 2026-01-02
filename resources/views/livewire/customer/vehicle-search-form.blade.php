<div>
    <!-- Error Message -->
    @if (session()->has('search_error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="text-sm font-medium text-red-800">{{ session('search_error') }}</p>
            </div>
            <button type="button" wire:click="$refresh" class="ml-auto text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    <form wire:submit.prevent="search">
        <div class="flex flex-col md:flex-row items-end gap-4">
            <!-- Make Dropdown -->
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium text-gray-700 mb-2">Make</label>
                <select wire:model.live="make" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent appearance-none bg-white">
                    <option value="">Any</option>
                    @foreach($makes as $makeOption)
                        <option value="{{ $makeOption->id }}">{{ $makeOption->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Model Dropdown -->
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                <select wire:model="model" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent appearance-none bg-white" {{ empty($models) ? 'disabled' : '' }}>
                    <option value="">Any</option>
                    @foreach($models as $modelOption)
                        <option value="{{ $modelOption['id'] }}">{{ $modelOption['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Year Dropdown -->
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                <select wire:model="minYear" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent appearance-none bg-white">
                    <option value="">Any</option>
                    @for($year = date('Y'); $year >= 2000; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>

            <!-- Search Button -->
            <div class="w-full md:w-auto">
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="w-full bg-green-700 hover:bg-green-800 disabled:bg-green-400 disabled:cursor-not-allowed text-white px-8 py-3 rounded-lg font-medium flex items-center justify-center gap-2 whitespace-nowrap transition-colors">
                    <!-- Loading Spinner -->
                    <svg wire:loading wire:target="search" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    
                    <!-- Search Icon -->
                    <svg wire:loading.remove wire:target="search" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    
                    <span wire:loading.remove wire:target="search">
                        @if($condition === 'used')
                            Search used cars
                        @else
                            Search cars
                        @endif
                    </span>
                    <span wire:loading wire:target="search">Searching...</span>
                </button>
            </div>
        </div>
    </form>
</div>
