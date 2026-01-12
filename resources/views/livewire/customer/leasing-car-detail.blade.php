<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('cars.lease.index') }}" class="flex items-center gap-2 text-green-600 hover:text-green-700 font-medium">
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
                            <img src="{{ asset('storage/' . $image) }}" alt="Lease vehicle image {{ $index + 1 }}" class="w-full h-full object-cover">
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

                {{-- Lease Info --}}
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    @if($lease->entity)
                    <p class="text-sm text-gray-600 mb-2">From</p>
                    <p class="text-sm text-gray-700 mb-4">{{ $lease->entity->name }}</p>
                    @endif
                    
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $lease->vehicle_make }} {{ $lease->vehicle_model }}</h1>
                    @if($lease->vehicle_title)
                    <p class="text-lg text-gray-700 mb-4">{{ $lease->vehicle_title }}</p>
                    @endif

                    <div class="flex items-baseline gap-3">
                        <div class="text-4xl font-bold text-green-600">${{ number_format($lease->monthly_payment, 0) }}</div>
                        <span class="text-xl text-gray-600">/month</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">{{ $lease->lease_term_months }} month lease term</p>
                </div>

                {{-- Overview Section --}}
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Overview</h2>

                    {{-- Specs Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-6">
                        @if($lease->vehicle_year)
                        <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm font-medium">Year</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ $lease->vehicle_year }}</p>
                        </div>
                        @endif

                        @if($lease->mileage)
                                <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium">Mileage</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ number_format($lease->mileage) }} km</p>
                        </div>
                        @endif

                        @if($lease->fuel_type)
                        <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span class="text-sm font-medium">Fuel type</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ ucfirst($lease->fuel_type) }}</p>
                        </div>
                        @endif

                        @if($lease->body_type)
                        <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0h-.01M15 17a2 2 0 104 0m-4 0h-.01"></path>
                                </svg>
                                <span class="text-sm font-medium">Body type</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ ucfirst($lease->body_type) }}</p>
                        </div>
                        @endif

                        @if($lease->transmission)
                    <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                </svg>
                                <span class="text-sm font-medium">Gearbox</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ ucfirst($lease->transmission) }}</p>
                        </div>
                        @endif

                        @if($lease->engine_cc)
                            <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                </svg>
                                <span class="text-sm font-medium">Engine</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ number_format($lease->engine_cc / 1000, 1) }}L</p>
                        </div>
                        @endif

                        @if($lease->lease_term_months)
                            <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium">Lease Term</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ $lease->lease_term_months }} months</p>
                        </div>
                        @endif

                        @if($lease->mileage_limit_per_year)
                            <div>
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span class="text-sm font-medium">Mileage Limit</span>
                            </div>
                            <p class="text-gray-900 font-semibold">{{ number_format($lease->mileage_limit_per_year) }} km/year</p>
                        </div>
                        @endif
                </div>
            </div>

                {{-- Lease Terms Details --}}
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Lease Terms</h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Down Payment</p>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($lease->down_payment, 0) }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Security Deposit</p>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($lease->security_deposit ?? 0, 0) }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Total Upfront</p>
                            <p class="text-2xl font-bold text-green-600">${{ number_format($lease->total_upfront_cost ?? ($lease->down_payment + ($lease->security_deposit ?? 0)), 0) }}</p>
                        </div>
                        @if($lease->excess_mileage_charge)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Excess Mileage</p>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($lease->excess_mileage_charge, 2) }}/km</p>
                        </div>
                        @endif
                        @if($lease->total_lease_cost)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Total Lease Cost</p>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($lease->total_lease_cost, 0) }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                @if($lease->lease_description)
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Description</h2>
                    
                    <div class="text-gray-700 prose max-w-none">
                        @if($expandedSections['fullDescription'])
                            {!! nl2br(e($lease->lease_description)) !!}
                            <button wire:click="toggleSection('fullDescription')" class="mt-4 flex items-center gap-2 text-gray-900 hover:text-gray-700 font-medium">
                                Show less
                                <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        @else
                            <p>{{ \Illuminate\Support\Str::limit($lease->lease_description, 200) }}</p>
                            @if(strlen($lease->lease_description) > 200)
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
                @if($lease->features && count($lease->features) > 0)
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Features</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($lease->features as $feature)
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- What's Included --}}
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">What's Included</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 {{ $lease->maintenance_included ? 'text-green-600' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="{{ $lease->maintenance_included ? 'text-gray-900' : 'text-gray-400' }}">Maintenance & Service</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 {{ $lease->insurance_included ? 'text-green-600' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="{{ $lease->insurance_included ? 'text-gray-900' : 'text-gray-400' }}">Insurance Coverage</span>
                        </div>
                        @if($lease->included_services && count($lease->included_services) > 0)
                            @foreach($lease->included_services as $service)
                            <div class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-900">{{ $service }}</span>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Purchase Options --}}
                @if($lease->purchase_option_available)
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h2 class="text-2xl font-bold text-blue-900 mb-4">Purchase Option Available</h2>
                    <p class="text-blue-800 mb-4">At the end of your lease, you have the option to purchase this vehicle.</p>
                    
                    @if($lease->residual_value)
                    <div class="mb-2">
                        <p class="text-sm text-blue-700">Buy-out Price</p>
                        <p class="text-2xl font-bold text-blue-900">${{ number_format($lease->residual_value, 0) }}</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            {{-- Sidebar - Pricing and Actions --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    {{-- Pricing Card --}}
                    <div class="bg-white rounded-xl p-6 shadow-sm border-2 border-green-500">
                        <div class="text-center mb-6">
                            <p class="text-sm text-gray-600 mb-2">Monthly Payment</p>
                            <p class="text-5xl font-bold text-green-600">${{ number_format($lease->monthly_payment, 0) }}</p>
                            <p class="text-gray-600 mt-2">for {{ $lease->lease_term_months }} months</p>
                        </div>

                        <div class="space-y-3 mb-6 pb-6 border-b border-gray-200">
                            @if($lease->total_lease_cost)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Lease Cost</span>
                                <span class="font-semibold text-gray-900">${{ number_format($lease->total_lease_cost, 0) }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Down Payment</span>
                                <span class="font-semibold text-gray-900">${{ number_format($lease->down_payment, 0) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Due at Signing</span>
                                <span class="font-semibold text-green-600">${{ number_format($lease->total_upfront_cost ?? ($lease->down_payment + ($lease->security_deposit ?? 0)), 0) }}</span>
                            </div>
                        </div>

                        @auth
                            {{-- Show existing application status if exists --}}
                            @if($existingOrder)
                                <div class="mb-3 p-4 rounded-lg border-2 
                                    {{ $applicationStatus === 'pending' ? 'bg-yellow-50 border-yellow-200' : '' }}
                                    {{ $applicationStatus === 'approved' ? 'bg-blue-50 border-blue-200' : '' }}
                                    {{ $applicationStatus === 'active' ? 'bg-green-50 border-green-200' : '' }}
                                    {{ $applicationStatus === 'rejected' ? 'bg-red-50 border-red-200' : '' }}
                                    {{ $applicationStatus === 'completed' ? 'bg-gray-50 border-gray-200' : '' }}">
                                    <div class="flex items-start gap-3">
                                        @if($applicationStatus === 'pending')
                                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-yellow-900 mb-1">Application Under Review</h4>
                                                <p class="text-sm text-yellow-700">Your application (Order #{{ $existingOrder->order_number }}) is currently being reviewed. We'll contact you soon.</p>
                                                <p class="text-xs text-yellow-600 mt-2">Submitted: {{ $existingOrder->created_at->format('M d, Y') }}</p>
                                            </div>
                                        @elseif($applicationStatus === 'approved')
                                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-blue-900 mb-1">Application Approved</h4>
                                                <p class="text-sm text-blue-700">Your application (Order #{{ $existingOrder->order_number }}) has been approved! Quotation and contract details will be sent to you.</p>
                                                <p class="text-xs text-blue-600 mt-2">Approved: {{ $existingOrder->updated_at->format('M d, Y') }}</p>
                                            </div>
                                        @elseif($applicationStatus === 'active')
                                            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-green-900 mb-1">Lease Active</h4>
                                                <p class="text-sm text-green-700">Your lease (Order #{{ $existingOrder->order_number }}) is currently active. Check your account for lease details and payments.</p>
                                                <p class="text-xs text-green-600 mt-2">Started: {{ $existingOrder->order_data['lease_started_at'] ?? $existingOrder->updated_at->format('M d, Y') }}</p>
                                            </div>
                                        @elseif($applicationStatus === 'rejected')
                                            <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-red-900 mb-1">Application Rejected</h4>
                                                <p class="text-sm text-red-700">Your previous application (Order #{{ $existingOrder->order_number }}) was rejected. You can submit a new application.</p>
                                                @if($existingOrder->admin_notes)
                                                <p class="text-xs text-red-600 mt-2">{{ $existingOrder->admin_notes }}</p>
                                                @endif
                                                <p class="text-xs text-red-600 mt-2">Rejected: {{ $existingOrder->updated_at->format('M d, Y') }}</p>
                                            </div>
                                        @elseif($applicationStatus === 'completed')
                                            <svg class="w-5 h-5 text-gray-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900 mb-1">Lease Terminated</h4>
                                                <p class="text-sm text-gray-700">Your previous lease (Order #{{ $existingOrder->order_number }}) has been completed. You can apply for a new lease.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Apply button --}}
                            @if($canApply)
                                <button wire:click="$dispatch('open-leasing-modal', {leaseId: {{ $lease->id }}})" class="w-full px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-colors mb-3">
                                    {{ $existingOrder && $applicationStatus === 'rejected' ? 'Apply Again' : 'Apply for This Lease' }}
                                </button>
                            @else
                                <button disabled class="w-full px-6 py-3 bg-gray-400 text-white font-bold rounded-lg cursor-not-allowed mb-3 opacity-75">
                                    @if($applicationStatus === 'pending')
                                        Application Under Review
                                    @elseif($applicationStatus === 'approved')
                                        Application Approved
                                    @elseif($applicationStatus === 'active')
                                        Lease Active
                                    @else
                                        Application in Process
                                    @endif
                                </button>
                                @if($existingOrder)
                                <p class="text-xs text-gray-500 text-center mb-3">
                                    Order #{{ $existingOrder->order_number }} • 
                                    @if($applicationStatus === 'pending')
                                        Submitted on {{ $existingOrder->created_at->format('M d, Y') }}
                                    @elseif($applicationStatus === 'approved')
                                        Approved on {{ $existingOrder->updated_at->format('M d, Y') }}
                                    @elseif($applicationStatus === 'active')
                                        Active since {{ $existingOrder->order_data['lease_started_at'] ?? $existingOrder->updated_at->format('M d, Y') }}
                                    @endif
                                </p>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block w-full px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg text-center transition-colors mb-3">
                                Login to Apply
                            </a>
                        @endauth
                    </div>

                    {{-- Eligibility Requirements --}}
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Eligibility Requirements</h3>
                        
                        <div class="space-y-3 text-sm">
                            @if($lease->min_credit_score)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Min. Credit Score</span>
                                <span class="font-semibold text-gray-900">{{ $lease->min_credit_score }}</span>
                            </div>
                            @endif
                            
                            @if($lease->min_monthly_income)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Min. Monthly Income</span>
                                <span class="font-semibold text-gray-900">${{ number_format($lease->min_monthly_income, 0) }}</span>
                            </div>
                            @endif
                            
                            @if($lease->min_age)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Min. Age</span>
                                <span class="font-semibold text-gray-900">{{ $lease->min_age }} years</span>
                            </div>
                            @endif
                        </div>

                        @if($lease->additional_requirements)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-600">{{ $lease->additional_requirements }}</p>
                        </div>
                        @endif
                    </div>

                    {{-- Dealer Info --}}
                    @if($lease->entity)
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Seller</h3>
                        <p class="font-semibold text-gray-900 mb-4">{{ $lease->entity->name }}</p>
                        
                        @if($lease->entity->phone)
                        <a href="tel:{{ $lease->entity->phone }}" class="w-full bg-white border-2 border-green-600 text-green-600 py-3 px-6 rounded-full font-semibold hover:bg-green-50 transition-colors flex items-center justify-center gap-2 mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ $lease->entity->phone }}
                        </a>
                        @endif

                        @if($lease->entity->email)
                        <a href="mailto:{{ $lease->entity->email }}" class="w-full bg-green-600 text-white py-3 px-6 rounded-full font-semibold hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email seller
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Image Lightbox Modal --}}
    @if($showImageModal && $currentImage !== null && isset($allImages[$currentImage]))
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-95 animate-fadeIn" wire:click="closeImageModal">
        {{-- Close Button --}}
        <button wire:click="closeImageModal" class="absolute top-4 right-4 w-12 h-12 flex items-center justify-center bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full transition-colors z-10">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        {{-- Image Counter --}}
        <div class="absolute top-4 left-4 bg-black bg-opacity-50 text-white px-4 py-2 rounded-lg z-10">
            <span class="font-medium">{{ $currentImage + 1 }} / {{ count($allImages) }}</span>
        </div>

        {{-- Previous Button --}}
        @if(count($allImages) > 1)
        <button wire:click.stop="previousImage" class="absolute left-4 w-12 h-12 flex items-center justify-center bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full transition-colors z-10">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        @endif

        {{-- Main Image --}}
        <div class="max-w-6xl max-h-[90vh] mx-auto px-4" wire:click.stop>
            <img src="{{ asset('storage/' . $allImages[$currentImage]) }}" alt="Lease vehicle image" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl">
        </div>

        {{-- Next Button --}}
        @if(count($allImages) > 1)
        <button wire:click.stop="nextImage" class="absolute right-4 w-12 h-12 flex items-center justify-center bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full transition-colors z-10">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    {{-- Leasing Application Modal --}}
    @livewire('customer.leasing-application-modal')
</div>
