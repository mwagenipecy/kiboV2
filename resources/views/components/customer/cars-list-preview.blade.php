<style>
    .kibo-text { color: #009866 !important; }
</style>
<!-- Cars List Preview Section (2 rows + View more) -->
<section class="bg-white py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12">
            Browse cars for sale
        </h2>

        @if($vehicles->count() > 0)
        {{-- Grid: 2 rows (4 cols lg, 2 cols sm, 1 col default) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($vehicles as $vehicle)
            @php
                $mainImage = $vehicle->image_front ?? $vehicle->image_back ?? $vehicle->image_side ?? null;
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
            <a href="{{ route('cars.detail', $vehicle->id) }}" class="group bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 block">
                <div class="relative aspect-[4/3] bg-gray-100">
                    @if($mainImage)
                        <img
                            src="{{ asset('storage/' . $mainImage) }}"
                            alt="{{ $vehicle->make->name ?? '' }} {{ $vehicle->model->name ?? '' }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            loading="lazy"
                        />
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    @if($vehicle->condition)
                    <div class="absolute top-3 left-3 bg-white px-2 py-1 rounded text-xs font-semibold text-gray-900 shadow-sm">
                        {{ ucfirst($vehicle->condition) }}
                    </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-base font-bold text-gray-900 mb-1 line-clamp-2">
                        {{ $vehicle->make->name ?? '' }} {{ $vehicle->model->name ?? '' }}
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">{{ $vehicle->year }} • {{ number_format($vehicle->mileage) }} km</p>
                    <p class="text-xl font-bold kibo-text">{{ $symbol }} {{ number_format($vehicle->price, 0) }}</p>
                </div>
            </a>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a
                href="{{ route('cars.search') }}"
                class="inline-flex items-center gap-2 text-gray-900 font-semibold hover:gap-3 transition-all duration-200 group"
            >
                <span class="border-b-2 border-gray-900">View more cars</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-600">No cars available at the moment.</p>
            <a href="{{ route('cars.search') }}" class="inline-block mt-4 font-semibold kibo-text">
                Browse all cars →
            </a>
        </div>
        @endif
    </div>
</section>
