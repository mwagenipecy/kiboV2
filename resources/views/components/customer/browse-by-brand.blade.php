@php
    // Fetch all active brands
    $allActiveBrands = \App\Models\VehicleMake::where('status', 'active')
        ->orderBy('name')
        ->get();
    
    // Split into featured (first 4) and remaining brands
    $featuredBrands = $allActiveBrands->take(4);
    $allBrands = $allActiveBrands->skip(4);
@endphp

<!-- Browse by Brand Section -->
<section class="bg-white py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Section Title -->
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12">
            Browse by brand
        </h2>

        @if($featuredBrands->count() > 0)
        <!-- Featured Brands - Large Cards (Always Visible) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach($featuredBrands as $brand)
            <a href="{{ route('cars.search', ['make' => \Illuminate\Support\Str::slug($brand->name)]) }}" class="bg-white border border-gray-200 rounded-xl p-8 hover:shadow-lg hover:border-gray-300 transition-all duration-300 flex flex-col items-center justify-center min-h-[220px] group">
                <div class="mb-6 h-20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    @if($brand->icon)
                        <img src="{{ asset('storage/' . $brand->icon) }}" alt="{{ $brand->name }} logo" class="max-h-20 max-w-[140px] object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div style="display: none;" class="text-4xl font-bold text-gray-400">{{ strtoupper(substr($brand->name, 0, 1)) }}</div>
                    @else
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="text-3xl font-bold text-gray-400">{{ strtoupper(substr($brand->name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>
                <h3 class="text-sm font-bold text-gray-900 tracking-wider text-center uppercase">{{ $brand->name }}</h3>
            </a>
            @endforeach
        </div>
        @endif

        @if($allBrands->count() > 0)
        <!-- Toggle Button -->
        <div class="text-center mb-8">
            <button id="toggleBrandsBtn" class="inline-flex items-center gap-2 text-gray-700 hover:text-gray-900 font-medium transition-colors text-base">
                <span id="toggleText">View more</span>
                <svg id="toggleIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- All Brands Grid - Smaller Cards (Expandable) -->
        <div id="allBrandsGrid" class="hidden grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 animate-fadeIn">
            @foreach($allBrands as $brand)
            <a href="{{ route('cars.search', ['make' => \Illuminate\Support\Str::slug($brand->name)]) }}" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-gray-300 transition-all duration-200 flex items-center gap-4 group">
                <div class="w-12 h-12 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                    @if($brand->icon)
                        <img src="{{ asset('storage/' . $brand->icon) }}" alt="{{ $brand->name }} logo" class="max-h-12 max-w-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div style="display: none;" class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">{{ strtoupper(substr($brand->name, 0, 1)) }}</div>
                    @else
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">{{ strtoupper(substr($brand->name, 0, 1)) }}</div>
                    @endif
                </div>
                <span class="text-gray-900 font-medium text-base">{{ $brand->name }}</span>
            </a>
            @endforeach
        </div>
        @endif

        @if($featuredBrands->count() === 0 && $allBrands->count() === 0)
        <!-- No Brands Message -->
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No brands available</h3>
            <p class="text-gray-600">Vehicle brands will appear here once they are added by administrators.</p>
        </div>
        @endif
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
    </style>

    @if($allBrands->count() > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleBrandsBtn');
            const allBrandsGrid = document.getElementById('allBrandsGrid');
            const toggleText = document.getElementById('toggleText');
            const toggleIcon = document.getElementById('toggleIcon');
            let isExpanded = false;

            if (toggleBtn && allBrandsGrid) {
                toggleBtn.addEventListener('click', function() {
                    isExpanded = !isExpanded;
                    
                    if (isExpanded) {
                        allBrandsGrid.classList.remove('hidden');
                        allBrandsGrid.classList.add('grid');
                        toggleText.textContent = 'Hide all';
                        toggleIcon.classList.remove('hidden');
                    } else {
                        allBrandsGrid.classList.add('hidden');
                        allBrandsGrid.classList.remove('grid');
                        toggleText.textContent = 'View more';
                        toggleIcon.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @endif
</section>
