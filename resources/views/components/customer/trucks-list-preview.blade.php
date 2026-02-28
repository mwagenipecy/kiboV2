<style>
    .kibo-text { color: #009866 !important; }
</style>
<!-- Trucks List Preview Section (2 rows + Used/New + View more) -->
<section class="bg-white py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-6">
            Browse trucks for sale
        </h2>

        {{-- Used / New / All tabs --}}
        <div class="flex flex-wrap items-center justify-center gap-3 mb-10">
            <a href="{{ route('trucks.search') }}" class="px-6 py-3 rounded-full font-medium border-2 border-gray-300 text-gray-700 hover:border-green-600 hover:text-green-600 hover:bg-green-50 transition-colors">
                All trucks
            </a>
            <a href="{{ route('trucks.search', ['condition' => 'used']) }}" class="px-6 py-3 rounded-full font-medium bg-green-600 text-white hover:bg-green-700 transition-colors">
                Used trucks
            </a>
            <a href="{{ route('trucks.search', ['condition' => 'new']) }}" class="px-6 py-3 rounded-full font-medium bg-green-600 text-white hover:bg-green-700 transition-colors">
                New trucks
            </a>
        </div>

        @if($trucks->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($trucks as $truck)
            @php
                $mainImage = $truck->image_front ?? $truck->image_back ?? $truck->image_side ?? null;
            @endphp
            <a href="{{ route('trucks.detail', $truck->id) }}" class="group bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 block">
                <div class="relative aspect-[4/3] bg-gray-100">
                    @if($mainImage)
                        <img
                            src="{{ asset('storage/' . $mainImage) }}"
                            alt="{{ $truck->make->name ?? '' }} {{ $truck->model->name ?? '' }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            loading="lazy"
                        />
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    @endif
                    @if($truck->condition)
                    <div class="absolute top-3 left-3 bg-white px-2 py-1 rounded text-xs font-semibold text-gray-900 shadow-sm">
                        {{ ucfirst($truck->condition) }}
                    </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-base font-bold text-gray-900 mb-1 line-clamp-2">
                        {{ $truck->make->name ?? '' }} {{ $truck->model->name ?? '' }}
                    </h3>
                    <p class="text-sm text-gray-600 mb-2">{{ $truck->year }} • {{ number_format($truck->mileage) }} km</p>
                    <p class="text-xl font-bold kibo-text">£{{ number_format($truck->price, 0) }}</p>
                </div>
            </a>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a
                href="{{ route('trucks.search') }}"
                class="inline-flex items-center gap-2 text-gray-900 font-semibold hover:gap-3 transition-all duration-200 group"
            >
                <span class="border-b-2 border-gray-900">View more trucks</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-600">No trucks available at the moment.</p>
            <a href="{{ route('trucks.search') }}" class="inline-block mt-4 font-semibold kibo-text">
                Browse all trucks →
            </a>
        </div>
        @endif
    </div>
</section>
