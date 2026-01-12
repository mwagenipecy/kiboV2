<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('spare-parts.search') }}" class="flex items-center gap-2 text-green-600 hover:text-green-700 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to suppliers
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Supplier Info --}}
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $supplier->company_name ?? $supplier->name }}</h1>
                    <p class="text-lg text-gray-700 mb-4">{{ $supplier->name }}</p>
                    
                    {{-- Address --}}
                    @if($supplier->address)
                        <div class="flex items-start gap-2 mb-4">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="text-gray-700">{{ $supplier->address }}</p>
                        </div>
                    @endif

                    {{-- Contact Information --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        @if($supplier->phone_number)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <a href="tel:{{ $supplier->phone_number }}" class="text-gray-700 hover:text-green-600">{{ $supplier->phone_number }}</a>
                            </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <a href="mailto:{{ $supplier->email }}" class="text-gray-700 hover:text-green-600">{{ $supplier->email }}</a>
                        </div>
                    </div>
                </div>

                {{-- Vehicle Makes --}}
                @if(count($makes) > 0)
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Vehicle Makes We Specialize In</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($makes as $make)
                            <div class="p-3 bg-gray-50 rounded-lg text-center">
                                <p class="font-medium text-gray-900">{{ $make->name }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Spare Part Details --}}
                @if($supplier->spare_part_details)
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Parts Available</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $supplier->spare_part_details }}</p>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    {{-- Contact Card --}}
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Contact Supplier</h3>
                        
                        @if($supplier->phone_number)
                            <a href="tel:{{ $supplier->phone_number }}" class="w-full mb-3 bg-white border-2 border-green-600 text-green-600 py-3 px-6 rounded-full font-semibold hover:bg-green-50 transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ $supplier->phone_number }}
                            </a>
                        @endif

                        <a href="mailto:{{ $supplier->email }}" class="w-full bg-green-600 text-white py-3 px-6 rounded-full font-semibold hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email Supplier
                        </a>
                    </div>

                    {{-- Request Parts Card --}}
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 shadow-sm border border-green-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Need Parts?</h3>
                        <p class="text-sm text-gray-700 mb-4">Request spare parts through our sourcing service</p>
                        <a href="{{ route('spare-parts.sourcing') }}" class="w-full bg-green-600 text-white py-3 px-6 rounded-full font-semibold hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                            Request Parts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

