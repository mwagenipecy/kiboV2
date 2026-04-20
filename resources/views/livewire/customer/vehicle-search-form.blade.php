<div>
    <!-- Error Message -->
    @if (session()->has('search_error'))
        <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-xl flex items-start gap-2">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-red-800">{{ session('search_error') }}</p>
            </div>
            <button type="button" wire:click="$refresh" class="shrink-0 text-red-600 hover:text-red-800 p-0.5 -mr-0.5" aria-label="Dismiss">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    <form wire:submit.prevent="search">
        {{-- Mobile: single card, divided rows, 44px+ touch targets --}}
        <div class="md:hidden rounded-2xl border border-gray-200 bg-gray-50/90 overflow-hidden shadow-inner">
            <div class="divide-y divide-gray-200/90">
                <div class="px-4 pt-3 pb-2 bg-white">
                    <label for="vsf-m-make" class="block text-[11px] font-semibold uppercase tracking-wide text-gray-500 mb-1">Make</label>
                    <div class="relative">
                        <select id="vsf-m-make" wire:model.live="make" class="w-full min-h-[44px] pl-0 pr-9 py-2 text-base text-gray-900 border-0 bg-transparent focus:ring-0 focus:outline-none appearance-none">
                            <option value="">Any make</option>
                            @foreach($makes as $makeOption)
                                <option value="{{ $makeOption->id }}">{{ $makeOption->name }}</option>
                            @endforeach
                        </select>
                        <span class="pointer-events-none absolute right-0 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </div>
                </div>
                <div class="px-4 pt-3 pb-2 bg-white">
                    <label for="vsf-m-model" class="block text-[11px] font-semibold uppercase tracking-wide text-gray-500 mb-1">Model</label>
                    <div class="relative">
                        <select id="vsf-m-model" wire:model="model" class="w-full min-h-[44px] pl-0 pr-9 py-2 text-base text-gray-900 border-0 bg-transparent focus:ring-0 focus:outline-none appearance-none disabled:text-gray-400 disabled:opacity-70" {{ empty($models) ? 'disabled' : '' }}>
                            <option value="">Any model</option>
                            @foreach($models as $modelOption)
                                <option value="{{ $modelOption['id'] }}">{{ $modelOption['name'] }}</option>
                            @endforeach
                        </select>
                        <span class="pointer-events-none absolute right-0 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </div>
                </div>
                <div class="px-4 pt-3 pb-2 bg-white">
                    <label for="vsf-m-year" class="block text-[11px] font-semibold uppercase tracking-wide text-gray-500 mb-1">Year from</label>
                    <div class="relative">
                        <select id="vsf-m-year" wire:model="minYear" class="w-full min-h-[44px] pl-0 pr-9 py-2 text-base text-gray-900 border-0 bg-transparent focus:ring-0 focus:outline-none appearance-none">
                            <option value="">Any year</option>
                            @for($year = date('Y'); $year >= 2000; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                        <span class="pointer-events-none absolute right-0 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </div>
                </div>
            </div>
            <div class="p-2.5 bg-gray-50/80 border-t border-gray-200/90">
                <button type="submit" wire:loading.attr="disabled" class="w-full min-h-[48px] disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-full text-[15px] font-semibold flex items-center justify-center gap-2.5 transition-all active:scale-[0.98] shadow-md shadow-[#009866]/20" style="background-color: #009866;">
                    <svg wire:loading wire:target="search" class="animate-spin w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg wire:loading.remove wire:target="search" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="search">
                        @if($condition === 'used')
                            Search used cars
                        @else
                            Search cars
                        @endif
                    </span>
                    <span wire:loading wire:target="search">Searching…</span>
                </button>
            </div>
        </div>

        {{-- Desktop: single pill row, rounded both ends --}}
        <div class="hidden md:flex md:flex-row md:items-stretch md:rounded-full md:border md:border-gray-200 md:bg-gray-50/80 md:overflow-hidden md:shadow-inner">
            <div class="flex-1 min-w-0 md:border-r md:border-gray-200">
                <label for="vsf-make" class="sr-only">Make</label>
                <select id="vsf-make" wire:model.live="make" class="w-full h-full min-h-[2.75rem] px-4 py-2 text-sm border-0 bg-transparent focus:ring-2 focus:ring-inset focus:ring-[#009866]/25 focus:outline-none appearance-none">
                    <option value="">Any make</option>
                    @foreach($makes as $makeOption)
                        <option value="{{ $makeOption->id }}">{{ $makeOption->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-0 md:border-r md:border-gray-200">
                <label for="vsf-model" class="sr-only">Model</label>
                <select id="vsf-model" wire:model="model" class="w-full h-full min-h-[2.75rem] px-4 py-2 text-sm border-0 bg-transparent focus:ring-2 focus:ring-inset focus:ring-[#009866]/25 focus:outline-none appearance-none" {{ empty($models) ? 'disabled' : '' }}>
                    <option value="">Any model</option>
                    @foreach($models as $modelOption)
                        <option value="{{ $modelOption['id'] }}">{{ $modelOption['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-32 shrink-0 md:border-r md:border-gray-200">
                <label for="vsf-year" class="sr-only">Year from</label>
                <select id="vsf-year" wire:model="minYear" class="w-full h-full min-h-[2.75rem] px-3 py-2 text-sm border-0 bg-transparent focus:ring-2 focus:ring-inset focus:ring-[#009866]/25 focus:outline-none appearance-none">
                    <option value="">Year</option>
                    @for($year = date('Y'); $year >= 2000; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex shrink-0 p-1 pl-2">
                <button type="submit" wire:loading.attr="disabled" class="h-full min-h-[2.75rem] px-6 rounded-full disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold flex items-center justify-center gap-2 whitespace-nowrap transition-colors shadow-sm" style="background-color: #009866;">
                    <svg wire:loading wire:target="search" class="animate-spin w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg wire:loading.remove wire:target="search" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="search">
                        @if($condition === 'used')
                            Search used cars
                        @else
                            Search cars
                        @endif
                    </span>
                    <span wire:loading wire:target="search">…</span>
                </button>
            </div>
        </div>
    </form>
</div>
