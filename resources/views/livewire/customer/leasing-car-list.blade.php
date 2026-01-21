<style>
    .kibo-text { color: #009866 !important; }
    .kibo-bg { background-color: #009866 !important; }
    .kibo-border { border-color: #009866 !important; }
    .kibo-radio:checked, .kibo-checkbox:checked { accent-color: #009866 !important; }
</style>
<div class="min-h-screen bg-white">
    {{-- Filter Bar --}}
    <div class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center gap-3 justify-between flex-wrap">
                {{-- Left side - Filter chips --}}
                <div class="flex items-center gap-3 flex-wrap">
                    {{-- Make Filter Chip --}}
                    @if($make)
                        <button wire:click="$toggle('showFilters')" class="px-6 py-2 kibo-bg text-white rounded-full font-medium transition-colors">
                            {{ $make }}
                        </button>
                    @endif

                    {{-- Model Filter Chip --}}
                    @if($model)
                        <button wire:click="$toggle('showFilters')" class="px-6 py-2 kibo-bg text-white rounded-full font-medium transition-colors">
                            {{ $model }}
                        </button>
                    @endif

                    {{-- Monthly Payment Filter Chip (Always visible) --}}
                    <button wire:click="$toggle('showFilters')" class="px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-full font-medium hover:border-gray-400 transition-colors">
                        Monthly Payment
                    </button>

                    {{-- Year Filter Chip (Always visible) --}}
                    <button wire:click="$toggle('showFilters')" class="px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-full font-medium hover:border-gray-400 transition-colors">
                        Year
                    </button>

                    {{-- Lease Term Filter Chip (Always visible) --}}
                    <button wire:click="$toggle('showFilters')" class="px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-full font-medium hover:border-gray-400 transition-colors">
                        Lease Term
                    </button>

                    {{-- Body Type Filter Chip (Always visible) --}}
                    <button wire:click="$toggle('showFilters')" class="px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-full font-medium hover:border-gray-400 transition-colors">
                        Body type
                    </button>
                </div>

                {{-- Right side - Filter and sort button --}}
                <button wire:click="$toggle('showFilters')" class="px-6 py-2 kibo-bg text-white rounded-full font-medium transition-colors flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    Filter and sort
                </button>
            </div>
        </div>
    </div>

    {{-- Filter Modal --}}
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
            {{-- Sort --}}
            <div class="border-b border-gray-200 pb-4 mb-4">
                <button wire:click="toggleSection('sort')" class="w-full flex items-center justify-between py-3">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        <div class="text-left">
                            <div class="font-semibold text-gray-900">Sort</div>
                            <div class="text-sm text-gray-600">
                                @if($sortBy === 'payment_low') Payment: Low to High
                                @elseif($sortBy === 'payment_high') Payment: High to Low
                                @elseif($sortBy === 'year_new') Year: Newest First
                                @elseif($sortBy === 'term_short') Term: Shortest First
                                @else Relevance
                                @endif
                            </div>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-600 transition-transform {{ $expandedSections['sort'] ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                @if($expandedSections['sort'])
                <div class="space-y-2 pl-8">
                    <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                        <input wire:model.live="sortBy" type="radio" value="relevance" class="w-4 h-4 kibo-text">
                        <span class="text-gray-700">Relevance</span>
                    </label>
                    <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                        <input wire:model.live="sortBy" type="radio" value="payment_low" class="w-4 h-4 kibo-text">
                        <span class="text-gray-700">Payment: Low to High</span>
                    </label>
                    <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                        <input wire:model.live="sortBy" type="radio" value="payment_high" class="w-4 h-4 kibo-text">
                        <span class="text-gray-700">Payment: High to Low</span>
                    </label>
                    <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                        <input wire:model.live="sortBy" type="radio" value="year_new" class="w-4 h-4 kibo-text">
                        <span class="text-gray-700">Year: Newest first</span>
                    </label>
                    <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                        <input wire:model.live="sortBy" type="radio" value="term_short" class="w-4 h-4 kibo-text">
                        <span class="text-gray-700">Term: Shortest First</span>
                    </label>
                </div>
                @endif
            </div>

            {{-- Make and Model --}}
            <div class="border-b border-gray-200 pb-4 mb-4">
                <button wire:click="toggleSection('makeModel')" class="w-full flex items-center justify-between py-3">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-left">
                            <div class="font-semibold text-gray-900">Make and model</div>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-600 transition-transform {{ $expandedSections['makeModel'] ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                @if($expandedSections['makeModel'])
                <div class="space-y-3 pl-8">
                    <div>
                        <label class="text-sm text-gray-600 mb-1 block">Make</label>
                        <select wire:model.live="make" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009866] focus:border-[#009866]">
                            <option value="">Any</option>
                            @foreach($availableMakes as $makeOption)
                                <option value="{{ $makeOption }}">{{ $makeOption }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($make && $availableModels->count() > 0)
                    <div>
                        <label class="text-sm text-gray-600 mb-1 block">Model</label>
                        <select wire:model.live="model" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009866] focus:border-[#009866]">
                            <option value="">Any</option>
                            @foreach($availableModels as $modelOption)
                                <option value="{{ $modelOption }}">{{ $modelOption }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            {{-- Year --}}
            <div class="border-b border-gray-200 pb-4 mb-4">
                <button wire:click="toggleSection('year')" class="w-full flex items-center justify-between py-3">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div class="text-left">
                            <div class="font-semibold text-gray-900">Year</div>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-600 transition-transform {{ $expandedSections['year'] ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                @if($expandedSections['year'])
                <div class="pl-8 space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm text-gray-600 mb-1 block">Min year</label>
                            <input wire:model.live="minYear" type="number" placeholder="No min" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009866] focus:border-[#009866]">
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 mb-1 block">Max year</label>
                            <input wire:model.live="maxYear" type="number" placeholder="No max" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009866] focus:border-[#009866]">
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Monthly Payment --}}
            <div class="border-b border-gray-200 pb-4 mb-4">
                <button wire:click="toggleSection('payment')" class="w-full flex items-center justify-between py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 flex items-center justify-center text-gray-600 font-bold">$</div>
                        <div class="text-left">
                            <div class="font-semibold text-gray-900">Monthly Payment</div>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-600 transition-transform {{ $expandedSections['payment'] ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                @if($expandedSections['payment'])
                <div class="pl-8 space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm text-gray-600 mb-1 block">Min payment</label>
                            <input wire:model.live="minPayment" type="number" placeholder="No min" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009866] focus:border-[#009866]">
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 mb-1 block">Max payment</label>
                            <input wire:model.live="maxPayment" type="number" placeholder="No max" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009866] focus:border-[#009866]">
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Lease Term --}}
            <div class="border-b border-gray-200 pb-4 mb-4">
                <button wire:click="toggleSection('leaseTerm')" class="w-full flex items-center justify-between py-3">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-left">
                            <div class="font-semibold text-gray-900">Lease Term</div>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-600 transition-transform {{ $expandedSections['leaseTerm'] ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                @if($expandedSections['leaseTerm'])
                <div class="pl-8 space-y-2">
                    @foreach($availableTerms as $term)
                    <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                        <input wire:model.live="leaseTerm" type="checkbox" value="{{ $term }}" class="w-4 h-4 kibo-text rounded">
                        <span class="text-gray-700">{{ $term }} months</span>
                    </label>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Body Type --}}
            <div class="pb-4 mb-20">
                <button wire:click="toggleSection('bodyType')" class="w-full flex items-center justify-between py-3">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0h-.01M15 17a2 2 0 104 0m-4 0h-.01"></path>
                        </svg>
                        <div class="text-left">
                            <div class="font-semibold text-gray-900">Body type</div>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-600 transition-transform {{ $expandedSections['bodyType'] ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                @if($expandedSections['bodyType'])
                <div class="pl-8 space-y-2">
                    <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                        <input wire:model.live="bodyType" type="checkbox" value="Sedan" class="w-4 h-4 kibo-text rounded">
                        <span class="text-gray-700">Sedan</span>
                    </label>
                    <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                        <input wire:model.live="bodyType" type="checkbox" value="SUV" class="w-4 h-4 kibo-text rounded">
                        <span class="text-gray-700">SUV</span>
                    </label>
                    <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                        <input wire:model.live="bodyType" type="checkbox" value="Hatchback" class="w-4 h-4 kibo-text rounded">
                        <span class="text-gray-700">Hatchback</span>
                    </label>
                    <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                        <input wire:model.live="bodyType" type="checkbox" value="Coupe" class="w-4 h-4 kibo-text rounded">
                        <span class="text-gray-700">Coupe</span>
                    </label>
                    <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                        <input wire:model.live="bodyType" type="checkbox" value="Convertible" class="w-4 h-4 kibo-text rounded">
                        <span class="text-gray-700">Convertible</span>
                    </label>
                    <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                        <input wire:model.live="bodyType" type="checkbox" value="Truck" class="w-4 h-4 kibo-text rounded">
                        <span class="text-gray-700">Truck</span>
                    </label>
                    <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                        <input wire:model.live="bodyType" type="checkbox" value="Van" class="w-4 h-4 kibo-text rounded">
                        <span class="text-gray-700">Van</span>
                    </label>
                    <label class="flex items-center gap-3 py-2 cursor-pointer hover:bg-gray-50 rounded px-2">
                        <input wire:model.live="bodyType" type="checkbox" value="Wagon" class="w-4 h-4 kibo-text rounded">
                        <span class="text-gray-700">Wagon</span>
                    </label>
                </div>
                @endif
            </div>
        </div>

        {{-- Footer --}}
        <div class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4 flex items-center justify-between gap-4">
            <button wire:click="clearFilters" class="kibo-text font-semibold">
                Clear all
            </button>
            <button wire:click="$set('showFilters', false)" class="px-8 py-3 kibo-bg text-white rounded-full font-semibold transition-colors">
                Search {{ number_format($totalCount) }} leases
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

    {{-- Results --}}
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <span class="text-xl font-semibold text-gray-900">{{ number_format($totalCount) }} results</span>
        </div>

        {{-- Lease Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($leases as $lease)
            @php
                $allImages = [];
                if($lease->image_front) {
                    $allImages[] = $lease->image_front;
                }
                if($lease->image_back) {
                    $allImages[] = $lease->image_back;
                }
                if($lease->image_left) {
                    $allImages[] = $lease->image_left;
                }
                if($lease->image_right) {
                    $allImages[] = $lease->image_right;
                }
                if($lease->image_interior) {
                    $allImages[] = $lease->image_interior;
                }
                if($lease->other_images && is_array($lease->other_images)) {
                    $allImages = array_merge($allImages, $lease->other_images);
                }
                $imageCount = count($allImages);
            @endphp
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col">
                {{-- Image Carousel --}}
                <div class="relative aspect-[4/3] bg-gray-100 group" data-carousel="lease-{{ $lease->id }}">
                    @if($imageCount > 0)
                        @foreach($allImages as $index => $image)
                        <a href="{{ route('cars.lease.detail', $lease->id) }}" class="carousel-image absolute inset-0 {{ $index === 0 ? '' : 'hidden' }}" data-index="{{ $index }}">
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $lease->vehicle_title }}" class="w-full h-full object-cover">
                        </a>
                        @endforeach
                    @else
                        <a href="{{ route('cars.lease.detail', $lease->id) }}" class="absolute inset-0">
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </a>
                    @endif

                    {{-- Navigation Arrows --}}
                    @if($imageCount > 1)
                    <button onclick="navigateCarousel(event, 'lease-{{ $lease->id }}', -1)" class="carousel-nav absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity z-10">
                        <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button onclick="navigateCarousel(event, 'lease-{{ $lease->id }}', 1)" class="carousel-nav absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity z-10">
                        <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    @endif

                    {{-- Badges --}}
                    <div class="absolute top-2 left-2 flex flex-col gap-1.5 z-10">
                        @if($lease->featured)
                        <div class="bg-yellow-500 px-3 py-1 rounded text-xs font-semibold text-white shadow-sm">
                            Featured
                        </div>
                        @endif
                        
                        @if($lease->condition)
                        <div class="bg-white px-3 py-1 rounded text-xs font-semibold text-gray-900 shadow-sm">
                            {{ ucfirst($lease->condition) }}
                        </div>
                        @endif
                    </div>

                    {{-- Save Button --}}
                    <button wire:click.prevent="toggleSave({{ $lease->id }})" class="absolute top-2 right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-gray-50 shadow-md transition-colors z-10">
                        <svg class="w-5 h-5 {{ in_array($lease->id, $savedLeases) ? 'fill-red-500 text-red-500' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>

                    {{-- Image counter --}}
                    @if($imageCount > 0)
                    <div class="absolute bottom-3 right-3 bg-gray-900/80 text-white px-2 py-1 rounded text-xs font-medium z-10">
                        <span class="current-image">1</span>/{{ $imageCount }}
                    </div>
                    @endif
                </div>

                {{-- Content --}}
                <a href="{{ route('cars.lease.detail', $lease->id) }}" class="p-4 flex flex-col flex-grow">
                    <h3 class="text-base font-bold text-gray-900 mb-1">{{ $lease->vehicle_make }} {{ $lease->vehicle_model }}</h3>
                    <p class="text-sm text-gray-700 mb-1 line-clamp-2">{{ $lease->vehicle_title }}</p>
                    <p class="text-xs text-gray-600 mb-3">{{ $lease->vehicle_year }} • {{ $lease->lease_term_months }} months</p>

                    {{-- Badges --}}
                    <div class="flex flex-wrap gap-1.5 mb-4">
                        @if($lease->transmission)
                        <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                            {{ $lease->transmission }}
                        </span>
                        @endif
                        @if($lease->fuel_type)
                        <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                            {{ $lease->fuel_type }}
                        </span>
                        @endif
                        @if($lease->body_type)
                        <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                            {{ $lease->body_type }}
                        </span>
                        @endif
                    </div>

                    {{-- Price and Location --}}
                    <div class="mt-auto">
                        <div class="text-2xl font-bold kibo-text mb-2">
                            ${{ number_format($lease->monthly_payment, 0) }}/mo
                        </div>
                        <div class="text-sm text-gray-600 mb-2">
                            ${{ number_format($lease->down_payment, 0) }} down
                        </div>
                        @if($lease->entity)
                        <div class="flex items-center gap-1 text-gray-600 text-xs mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $lease->entity->name }}</span>
                        </div>
                        @endif
                    </div>
                </a>

                {{-- Action Button --}}
                <div class="px-4 pb-4">
                    <a href="{{ route('cars.lease.detail', $lease->id) }}" class="block w-full px-4 py-2 kibo-bg text-white text-sm font-medium rounded-lg transition-colors text-center">
                        View Details
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No leases found</h3>
                <p class="text-gray-600 mb-6">We couldn't find any leases matching your search criteria.</p>
                <button wire:click="clearFilters" class="inline-flex items-center gap-2 px-6 py-3 kibo-bg text-white rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Clear all filters
                </button>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $leases->links() }}
        </div>
    </div>

    {{-- Scroll to top --}}
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="fixed bottom-8 right-8 w-12 h-12 kibo-bg text-white rounded-full flex items-center justify-center shadow-lg">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
        </svg>
    </button>

    {{-- Carousel Navigation Script --}}
    <script>
        function navigateCarousel(event, carouselId, direction) {
            event.preventDefault();
            event.stopPropagation();
            
            const carousel = document.querySelector(`[data-carousel="${carouselId}"]`);
            if (!carousel) return;
            
            const images = carousel.querySelectorAll('.carousel-image');
            if (images.length <= 1) return;
            
            let currentIndex = -1;
            images.forEach((img, index) => {
                if (!img.classList.contains('hidden')) {
                    currentIndex = index;
                }
            });
            
            if (currentIndex === -1) return;
            
            // Hide current image
            images[currentIndex].classList.add('hidden');
            
            // Calculate new index
            let newIndex = currentIndex + direction;
            if (newIndex < 0) newIndex = images.length - 1;
            if (newIndex >= images.length) newIndex = 0;
            
            // Show new image
            images[newIndex].classList.remove('hidden');
            
            // Update counter
            const counter = carousel.querySelector('.current-image');
            if (counter) {
                counter.textContent = newIndex + 1;
            }
        }
    </script>
</div>
