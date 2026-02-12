<div class="min-h-screen bg-gray-50">
    <style>
        .kibo-text { color: #009866 !important; }
        .kibo-bg { background-color: #009866 !important; }
        .kibo-border { border-color: #009866 !important; }
    </style>
    {{-- Header --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('cars.search') }}" class="flex items-center gap-2 kibo-text hover:opacity-80 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to results
            </a>
            <div class="flex items-center gap-4">
                <button wire:click="toggleSave" class="w-10 h-10 flex items-center justify-center hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-6 h-6 {{ $isSaved ? 'fill-red-500 text-red-500' : 'text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
                <button class="w-10 h-10 flex items-center justify-center hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-6">
        {{-- Full Width Image Gallery --}}
        <div class="bg-white rounded-xl overflow-hidden shadow-sm mb-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 p-2">
                {{-- Display all images --}}

                
                @if(count($allImages) > 0)
                    @foreach($allImages as $index => $image)
                        <div wire:click="openImageModal({{ $index }})" class="relative aspect-[4/3] bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg overflow-hidden cursor-pointer hover:opacity-90 transition-opacity {{ $index === 0 ? 'md:col-span-2 md:row-span-2 aspect-[16/10]' : '' }}">
                            <img src="{{ asset('storage/' . $image) }}" alt="Vehicle image {{ $index + 1 }}" class="w-full h-full object-cover">
                            @if($index === 0)
                            <div class="absolute bottom-4 right-4 bg-black bg-opacity-70 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">{{ count($allImages) }} photos</span>
                            </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    {{-- Placeholder if no images --}}
                    <div class="md:col-span-2 md:row-span-2 relative aspect-[16/10] bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                        <div class="text-center text-gray-400">
                            <svg class="w-20 h-20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="text-lg font-medium">No images available</p>
                        </div>
                    </div>
                    @for($i = 0; $i < 6; $i++)
                    <div class="relative aspect-[4/3] bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endfor
                @endif
            </div>
        </div>

        {{-- Content with Sidebar --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Car Info --}}
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    @if($vehicle->entity)
                    <p class="text-sm text-gray-600 mb-2">From</p>
                    <p class="text-sm text-gray-700 mb-4">{{ $vehicle->entity->name }}</p>
                    @endif
                    
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $vehicle->make->name ?? '' }} {{ $vehicle->model->name ?? '' }}</h1>
                    @if($vehicle->variant)
                    <p class="text-lg text-gray-700 mb-4">{{ $vehicle->variant }}</p>
                    @endif
                    
                    <div class="text-4xl font-bold text-gray-900">
                        @php
                            $currencySymbols = [
                                'TZS' => 'TSh',
                                'USD' => '$',
                                'GBP' => '£',
                                'EUR' => '€',
                                'KES' => 'KSh',
                                'UGX' => 'UGX',
                            ];
                            $symbol = $currencySymbols[$vehicle->currency] ?? $vehicle->currency;
                        @endphp
                        {{ $symbol }} {{ number_format($vehicle->price, 0) }}
                    </div>
                </div>

                {{-- Overview Section --}}
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Overview</h2>

                    {{-- Specs Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-6">
                        @if($vehicle->mileage)
                        <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium">Mileage</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ number_format($vehicle->mileage) }} km</p>
                        </div>
                        @endif

                        @if($vehicle->year)
                        <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm font-medium">Year</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ $vehicle->year }}</p>
                        </div>
                        @endif

                        @if($vehicle->fuel_type)
                        <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span class="text-sm font-medium">Fuel type</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ ucfirst($vehicle->fuel_type) }}</p>
                        </div>
                        @endif

                        @if($vehicle->body_type)
                        <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0h-.01M15 17a2 2 0 104 0m-4 0h-.01"></path>
                                </svg>
                                <span class="text-sm font-medium">Body type</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ ucfirst($vehicle->body_type) }}</p>
                        </div>
                        @endif

                        @if($vehicle->engine_cc)
                        <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                </svg>
                                <span class="text-sm font-medium">Engine</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ number_format($vehicle->engine_cc / 1000, 1) }}L</p>
                        </div>
                        @endif

                        @if($vehicle->transmission)
                        <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                </svg>
                                <span class="text-sm font-medium">Gearbox</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ ucfirst($vehicle->transmission) }}</p>
                        </div>
                        @endif

                        @if($vehicle->doors)
                        <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                                </svg>
                                <span class="text-sm font-medium">Doors</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ $vehicle->doors }}</p>
                        </div>
                        @endif

                        @if($vehicle->seats)
                        <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="text-sm font-medium">Seats</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ $vehicle->seats }}</p>
                        </div>
                        @endif

                        @if($vehicle->color_exterior)
                        <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                </svg>
                                <span class="text-sm font-medium">Body colour</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ ucfirst($vehicle->color_exterior) }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                @if($vehicle->description)
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Description</h2>
                    
                    <div class="text-gray-700 prose max-w-none">
                        @if($expandedSections['fullDescription'])
                            {!! nl2br(e($vehicle->description)) !!}
                            <button wire:click="toggleSection('fullDescription')" class="mt-4 flex items-center gap-2 text-gray-900 hover:text-gray-700 font-medium">
                                Show less
                                <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        @else
                            <p>{{ \Illuminate\Support\Str::limit($vehicle->description, 200) }}</p>
                            @if(strlen($vehicle->description) > 200)
                            <button wire:click="toggleSection('fullDescription')" class="mt-4 flex items-center gap-2 text-gray-900 hover:text-gray-700 font-medium">
                                Read full description
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            @endif
                        @endif
                    </div>
                </div>
                @endif

                {{-- Features --}}
                @if($vehicle->features && count($vehicle->features) > 0)
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Features</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($vehicle->features as $feature)
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 kibo-text flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Before You Buy --}}
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Before you buy</h2>
                    <p class="text-gray-600 mb-6">Work out some of the most important costs for this car before you go ahead</p>

                    <div class="space-y-4">
                        {{-- History Check --}}
                        <button wire:click="openInfoModal('history')" class="w-full flex items-start gap-4 p-4 border border-gray-200 rounded-lg hover:border-gray-300 hover:shadow-md transition-all cursor-pointer text-left">
                            <svg class="w-6 h-6 kibo-text flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 mb-1">Buy a complete history check</p>
                                <p class="text-sm text-gray-600">Get peace of mind with a complete picture of this vehicle's history and a data guarantee of up to £30,000. All for £4.95.</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>

                        {{-- Insurance Quote --}}
                        <button wire:click="openInfoModal('insurance')" class="w-full flex items-start gap-4 p-4 border border-gray-200 rounded-lg hover:border-gray-300 hover:shadow-md transition-all cursor-pointer text-left">
                            <svg class="w-6 h-6 kibo-text flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 mb-1">Get an insurance quote</p>
                                <p class="text-sm text-gray-600">From our trusted partner Safari Insurance</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Expert Reviews --}}
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Expert reviews for the {{ $vehicle->make->name ?? '' }} {{ $vehicle->model->name ?? '' }}</h2>
                    
                    <div class="flex items-baseline gap-2 mb-2">
                        <span class="text-5xl font-bold text-gray-900">3.7</span>
                        <svg class="w-8 h-8 fill-orange-400 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>

                    <p class="text-gray-600 mb-6">
                        This rating comes from our Kibo Auto vehicle experts, and is based on running costs, reliability, safety, comfort, features and power.
                    </p>

                    <button wire:click="openInfoModal('review')" class="flex items-center gap-2 kibo-text hover:opacity-80 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Read our experts review
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>

                {{-- Buying Safely --}}
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Buying a car safely</h2>
                    <p class="text-gray-600 mb-6">Learn how to stay safe and protect your money with our handy guide</p>

                    <button wire:click="openInfoModal('safety')" class="flex items-center gap-2 kibo-text hover:opacity-80 font-medium">
                        Read our guide on buying safely
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>

                {{-- Report --}}
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Spotted something fishy?</h2>
                    <p class="text-gray-600 mb-4">If you believe this listing violates our policies or contains misleading information, please report it.</p>
                    <button wire:click="$dispatch('openReportModal', { section: 'vehicle', reportableId: {{ $vehicle->id }}, reportableType: 'App\\Models\\Vehicle' })" class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Report this advert
                    </button>
                </div>
            </div>

            {{-- Sidebar - Contact Seller --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    {{-- Quick Actions Card --}}
                    @auth
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            {{-- Valuation Report --}}
                            <button wire:click="openValuationModal({{ $vehicle->id }})" class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors flex items-start gap-3 border border-gray-200 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900">Get Valuation Report</div>
                                    <div class="text-xs text-gray-500">Professional report - £50</div>
                                </div>
                            </button>

                            {{-- Financing - Only show if there are matching lenders --}}
                            @if(count($matchingLenders) > 0)
                            <button wire:click="openFinancingModal({{ $vehicle->id }})" class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors flex items-start gap-3 border border-gray-200 rounded-lg">
                                <svg class="w-6 h-6 kibo-text flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900">Apply for Financing</div>
                                    <div class="text-xs text-gray-500">{{ count($matchingLenders) }} financing option(s) available</div>
                                </div>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full self-center">{{ count($matchingLenders) }}</span>
                            </button>
                            @endif

                            {{-- Cash Purchase --}}
                            <button wire:click="openCashPurchaseModal({{ $vehicle->id }})" class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors flex items-start gap-3 border border-gray-200 rounded-lg">
                                <svg class="w-6 h-6 kibo-text flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900">Buy with Cash</div>
                                    <div class="text-xs text-gray-500">Direct purchase</div>
                                </div>
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 shadow-sm border border-green-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Interested in this vehicle?</h3>
                        <p class="text-sm text-gray-700 mb-4">Login to access valuation reports, financing options, and more!</p>
                        <a href="{{ route('login') }}" class="block w-full px-6 py-3 text-white text-center font-semibold rounded-lg transition-colors" style="background-color: #009866;">
                            Login to Continue
                        </a>
                    </div>
                    @endauth

                    {{-- Insurance Request Card --}}
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 shadow-sm border border-green-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #009866;">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Get Insurance Quote</h3>
                                <p class="text-sm text-gray-600">Protect your investment</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700 mb-4">
                            Get an instant insurance quote for this {{ $vehicle->year }} {{ $vehicle->make->name ?? '' }} {{ $vehicle->model->name ?? '' }}. Compare quotes from multiple insurers.
                        </p>
                        <a href="{{ route('cars.insurance') }}?vehicle_id={{ $vehicle->id }}" class="w-full text-white py-3 px-6 rounded-full font-semibold transition-colors flex items-center justify-center gap-2" style="background-color: #009866;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Request Insurance Quote
                        </a>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Contact seller</h3>
                        
                        @if($vehicle->entity)
                        <div class="bg-gray-50 px-3 py-1 rounded inline-block mb-4">
                            <span class="text-sm font-medium text-gray-700">{{ $vehicle->entity->type ?? 'Dealer' }}</span>
                        </div>

                        <p class="text-gray-700 mb-6">{{ $vehicle->entity->name }}</p>

                        @if($vehicle->entity->phone)
                        <a href="tel:{{ $vehicle->entity->phone }}" class="w-full bg-white border-2 py-3 px-6 rounded-full font-semibold hover:bg-green-50 transition-colors flex items-center justify-center gap-2 mb-4" style="border-color: #009866; color: #009866;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ $vehicle->entity->phone }}
                        </a>
                        @endif

                        @if($vehicle->entity->email)
                        <a href="mailto:{{ $vehicle->entity->email }}" class="w-full text-white py-3 px-6 rounded-full font-semibold transition-colors flex items-center justify-center gap-2" style="background-color: #009866;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email seller
                        </a>
                        @endif
                        @else
                        <p class="text-gray-600">Contact information not available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Image Lightbox Modal --}}
    @if($showImageModal && $currentImage !== null && isset($allImages[$currentImage]))
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-95 animate-fadeIn" wire:click="closeImageModal">
        {{-- Close Button --}}
        <button wire:click="closeImageModal" class="absolute top-4 right-4 w-12 h-12 flex items-center justify-center bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full transition-colors z-10">
       

            <svg class="w-6 h-6 " stroke="currentColor" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>



        </button>

        {{-- Image Counter --}}
        <div class="absolute top-4 left-4 bg-black bg-opacity-50 text-white px-4 py-2 rounded-lg z-10">
            <span class="font-medium">{{ $currentImage + 1 }} / {{ count($allImages) }}</span>
        </div>

        {{-- Previous Button --}}
        @if(count($allImages) > 1)
        <button wire:click.stop="previousImage" class="absolute left-4 w-12 h-12 flex items-center justify-center bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full transition-colors z-10">
            <svg class="w-6 h-6 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>

          



        </button>
        @endif

        {{-- Main Image --}}
        <div class="max-w-6xl max-h-[90vh] mx-auto px-4" wire:click.stop>
            <img src="{{ asset('storage/' . $allImages[$currentImage]) }}" alt="Vehicle image" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl">
        </div>

        {{-- Next Button --}}
        @if(count($allImages) > 1)
        <button wire:click.stop="nextImage" class="absolute right-4 w-12 h-12 flex items-center justify-center bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full transition-colors z-10">
            <svg class="w-6 h-6 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        @endif

        {{-- Keyboard Navigation Hint --}}
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-50 text-white px-4 py-2 rounded-lg text-sm">
            <span>Use arrow keys or click arrows to navigate</span>
        </div>
    </div>

    {{-- Keyboard Navigation Script --}}
    <script>
        document.addEventListener('livewire:init', () => {
            document.addEventListener('keydown', (e) => {
                if (@js($showImageModal)) {
                    if (e.key === 'Escape') {
                        @this.call('closeImageModal');
                    } else if (e.key === 'ArrowLeft') {
                        @this.call('previousImage');
                    } else if (e.key === 'ArrowRight') {
                        @this.call('nextImage');
                    }
                }
            });
        });
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-fadeIn {
            animation: fadeIn 0.2s ease-out;
        }
    </style>
    @endif

    {{-- Info Side Modal --}}
    @if($showInfoModal)
    <div class="fixed inset-0 z-50">
        {{-- Backdrop --}}
        <div wire:click="closeInfoModal" class="fixed inset-0 bg-black/50 bg-opacity-50 animate-fadeIn"></div>
        
        {{-- Modal Panel --}}
        <div class="fixed right-0 top-0 h-full w-full max-w-lg bg-white shadow-2xl overflow-y-auto animate-slideInRight">
            {{-- Header --}}
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
                <h2 class="text-2xl font-bold text-gray-900">
                    @if($modalContent === 'history')
                        Vehicle History Check
                    @elseif($modalContent === 'insurance')
                        Insurance Quote
                    @elseif($modalContent === 'review')
                        Expert Review
                    @elseif($modalContent === 'safety')
                        Buying Safely Guide
                    @endif
                </h2>
                <button wire:click="closeInfoModal" class="w-10 h-10 flex items-center justify-center hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Content --}}
            <div class="px-6 py-6">
                @if($modalContent === 'history')
                    {{-- Vehicle History Check Content --}}
                    <div class="mb-6">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 kibo-text flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="font-semibold text-gray-900 mb-1">Complete History Check - Only £4.95</p>
                                    <p class="text-sm text-gray-700">Data guarantee of up to £30,000</p>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-4">What's included:</h3>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 kibo-text flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Outstanding finance check</p>
                                    <p class="text-sm text-gray-600">Check if there's any money owed on the vehicle</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 kibo-text flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Stolen vehicle check</p>
                                    <p class="text-sm text-gray-600">Verify it hasn't been reported stolen</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 kibo-text flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Write-off check</p>
                                    <p class="text-sm text-gray-600">See if it's been written off by an insurance company</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 kibo-text flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Mileage verification</p>
                                    <p class="text-sm text-gray-600">Check recorded mileage history</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 kibo-text flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Number plate changes</p>
                                    <p class="text-sm text-gray-600">See if the registration has been changed</p>
                                </div>
                            </div>
                        </div>

                        <button class="w-full text-white py-3 px-6 rounded-full font-semibold transition-colors" style="background-color: #009866;">
                            Buy History Check - £4.95
                        </button>
                    </div>

                @elseif($modalContent === 'insurance')
                    {{-- Insurance Quote Content --}}
                    <div class="mb-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-indigo-900 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-bold">SV</span>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">Savana</p>
                                <p class="text-sm text-gray-600">Our trusted insurance partner</p>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-4">Why get a quote?</h3>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 kibo-text flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Compare quotes from over 100 insurers</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 kibo-text flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Quick and easy online process</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 kibo-text flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">See prices before you buy</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 kibo-text flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-700">Potentially save hundreds on your premium</p>
                            </div>
                        </div>

                        <p class="text-sm text-gray-600 mb-6">
                            Vehicle Details: {{ $vehicle->year }} {{ $vehicle->make->name ?? '' }} {{ $vehicle->model->name ?? '' }}
                        </p>

                        <a href="{{ route('cars.insurance') }}?vehicle_id={{ $vehicle->id }}" class="w-full text-white py-3 px-6 rounded-full font-semibold transition-colors flex items-center justify-center gap-2" style="background-color: #009866;">
                            Get Insurance Quote
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>

                @elseif($modalContent === 'review')
                    {{-- Expert Review Content --}}
                    <div class="mb-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="text-center">
                                <div class="text-5xl font-bold text-gray-900 mb-2">3.7</div>
                                <div class="flex justify-center">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-6 h-6 {{ $i < 4 ? 'fill-orange-400 text-orange-400' : 'text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-4">Rating Breakdown</h3>
                        <div class="space-y-4 mb-6">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium text-gray-700">Running Costs</span>
                                    <span class="text-gray-600">4.0</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="kibo-bg h-2 rounded-full" style="width: 80%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium text-gray-700">Reliability</span>
                                    <span class="text-gray-600">3.5</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="kibo-bg h-2 rounded-full" style="width: 70%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium text-gray-700">Safety</span>
                                    <span class="text-gray-600">4.2</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="kibo-bg h-2 rounded-full" style="width: 84%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium text-gray-700">Comfort</span>
                                    <span class="text-gray-600">3.8</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="kibo-bg h-2 rounded-full" style="width: 76%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium text-gray-700">Features</span>
                                    <span class="text-gray-600">3.5</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="kibo-bg h-2 rounded-full" style="width: 70%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium text-gray-700">Power</span>
                                    <span class="text-gray-600">3.5</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="kibo-bg h-2 rounded-full" style="width: 70%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <p class="text-gray-700 leading-relaxed">
                                The {{ $vehicle->make->name ?? '' }} {{ $vehicle->model->name ?? '' }} offers a great balance of performance, comfort, and reliability. It's particularly strong in safety features and running costs, making it an excellent choice for families and daily commuters alike.
                            </p>
                        </div>
                    </div>

                @elseif($modalContent === 'safety')
                    {{-- Buying Safely Content --}}
                    <div class="mb-6">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div>
                                    <p class="font-semibold text-gray-900 mb-1">Stay Safe When Buying</p>
                                    <p class="text-sm text-gray-700">Follow these essential tips to protect yourself</p>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-4">Essential Safety Tips:</h3>
                        <div class="space-y-4 mb-6">
                            <div class="border-l-4 border-green-600 pl-4">
                                <p class="font-medium text-gray-900 mb-1">1. Always meet in person</p>
                                <p class="text-sm text-gray-600">Inspect the vehicle before making any payment</p>
                            </div>
                            <div class="border-l-4 border-green-600 pl-4">
                                <p class="font-medium text-gray-900 mb-1">2. Get a history check</p>
                                <p class="text-sm text-gray-600">Verify the vehicle's past before you buy</p>
                            </div>
                            <div class="border-l-4 border-green-600 pl-4">
                                <p class="font-medium text-gray-900 mb-1">3. Test drive thoroughly</p>
                                <p class="text-sm text-gray-600">Check all features and functions work properly</p>
                            </div>
                            <div class="border-l-4 border-green-600 pl-4">
                                <p class="font-medium text-gray-900 mb-1">4. Use secure payment methods</p>
                                <p class="text-sm text-gray-600">Never transfer money before seeing the vehicle</p>
                            </div>
                            <div class="border-l-4 border-green-600 pl-4">
                                <p class="font-medium text-gray-900 mb-1">5. Verify the seller</p>
                                <p class="text-sm text-gray-600">Check their identity and ownership documents</p>
                            </div>
                            <div class="border-l-4 border-green-600 pl-4">
                                <p class="font-medium text-gray-900 mb-1">6. Check all paperwork</p>
                                <p class="text-sm text-gray-600">Ensure V5C, MOT, and service history are genuine</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700 leading-relaxed">
                                <strong class="text-gray-900">Remember:</strong> If a deal seems too good to be true, it probably is. Take your time, do your research, and never feel pressured to make a quick decision.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        @keyframes slideInRight {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        .animate-slideInRight {
            animation: slideInRight 0.3s ease-out;
        }
    </style>
    @endif

    {{-- Order Modals --}}
    @livewire('customer.valuation-request-modal')
    @livewire('customer.financing-application-modal')
    @livewire('customer.cash-purchase-modal')
    
    {{-- Report Modal --}}
    @livewire('customer.report-modal', ['section' => 'vehicle', 'reportableId' => $vehicle->id, 'reportableType' => 'App\Models\Vehicle'], key('report-modal-'.$vehicle->id))
</div>
