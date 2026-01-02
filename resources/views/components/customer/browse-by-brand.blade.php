<!-- Browse by Brand Section -->
<section class="bg-white py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Section Title -->
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12">
            Browse by brand
        </h2>

        <!-- Featured Brands - Large Cards (Always Visible) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Audi -->
            <a href="{{ route('cars.search', ['make' => 'audi']) }}" class="bg-white border border-gray-200 rounded-xl p-8 hover:shadow-lg hover:border-gray-300 transition-all duration-300 flex flex-col items-center justify-center min-h-[220px] group">
                <div class="mb-6 h-20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <img src="https://www.carlogos.org/car-logos/audi-logo.png" alt="Audi logo" class="max-h-20 max-w-[140px] object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div style="display: none;" class="text-4xl font-bold text-gray-400">A</div>
                </div>
                <h3 class="text-sm font-bold text-gray-900 tracking-wider text-center">AUDI</h3>
            </a>

            <!-- BMW -->
            <a href="{{ route('cars.search', ['make' => 'bmw']) }}" class="bg-white border border-gray-200 rounded-xl p-8 hover:shadow-lg hover:border-gray-300 transition-all duration-300 flex flex-col items-center justify-center min-h-[220px] group">
                <div class="mb-6 h-20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <img src="https://www.carlogos.org/car-logos/bmw-logo.png" alt="BMW logo" class="max-h-20 max-w-[140px] object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div style="display: none;" class="text-4xl font-bold text-gray-400">B</div>
                </div>
                <h3 class="text-sm font-bold text-gray-900 tracking-wider text-center">BMW</h3>
            </a>

            <!-- Mercedes-Benz -->
            <a href="{{ route('cars.search', ['make' => 'mercedes-benz']) }}" class="bg-white border border-gray-200 rounded-xl p-8 hover:shadow-lg hover:border-gray-300 transition-all duration-300 flex flex-col items-center justify-center min-h-[220px] group">
                <div class="mb-6 h-20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <img src="https://www.carlogos.org/car-logos/mercedes-benz-logo.png" alt="Mercedes-Benz logo" class="max-h-20 max-w-[140px] object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div style="display: none;" class="text-4xl font-bold text-gray-400">M</div>
                </div>
                <h3 class="text-sm font-bold text-gray-900 tracking-wider text-center">MERCEDES-BENZ</h3>
            </a>

            <!-- Volvo -->
            <a href="{{ route('cars.search', ['make' => 'volvo']) }}" class="bg-white border border-gray-200 rounded-xl p-8 hover:shadow-lg hover:border-gray-300 transition-all duration-300 flex flex-col items-center justify-center min-h-[220px] group">
                <div class="mb-6 h-20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <img src="https://www.carlogos.org/car-logos/volvo-logo.png" alt="Volvo logo" class="max-h-20 max-w-[140px] object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div style="display: none;" class="text-4xl font-bold text-gray-400">V</div>
                </div>
                <h3 class="text-sm font-bold text-gray-900 tracking-wider text-center">VOLVO</h3>
            </a>
        </div>

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
            <!-- Abarth -->
            <a href="{{ route('cars.search', ['make' => 'abarth']) }}" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-gray-300 transition-all duration-200 flex items-center gap-4 group">
                <div class="w-12 h-12 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                    <img src="https://www.carlogos.org/car-logos/abarth-logo.png" alt="Abarth logo" class="max-h-12 max-w-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display: none;" class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">A</div>
                </div>
                <span class="text-gray-900 font-medium text-base">Abarth</span>
            </a>

            <!-- Alfa Romeo -->
            <a href="{{ route('cars.search', ['make' => 'alfa-romeo']) }}" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-gray-300 transition-all duration-200 flex items-center gap-4 group">
                <div class="w-12 h-12 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                    <img src="https://www.carlogos.org/car-logos/alfa-romeo-logo.png" alt="Alfa Romeo logo" class="max-h-12 max-w-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display: none;" class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">A</div>
                </div>
                <span class="text-gray-900 font-medium text-base">Alfa Romeo</span>
            </a>

            <!-- Alpine -->
            <a href="{{ route('cars.search', ['make' => 'alpine']) }}" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-gray-300 transition-all duration-200 flex items-center gap-4 group">
                <div class="w-12 h-12 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                    <img src="https://www.carlogos.org/car-logos/alpine-logo.png" alt="Alpine logo" class="max-h-12 max-w-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display: none;" class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">A</div>
                </div>
                <span class="text-gray-900 font-medium text-base">Alpine</span>
            </a>

            <!-- Aston Martin -->
            <a href="{{ route('cars.search', ['make' => 'aston-martin']) }}" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-gray-300 transition-all duration-200 flex items-center gap-4 group">
                <div class="w-12 h-12 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                    <img src="https://www.carlogos.org/car-logos/aston-martin-logo.png" alt="Aston Martin logo" class="max-h-12 max-w-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display: none;" class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">A</div>
                </div>
                <span class="text-gray-900 font-medium text-base">Aston Martin</span>
            </a>

            <!-- Bentley -->
            <a href="{{ route('cars.search', ['make' => 'bentley']) }}" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-gray-300 transition-all duration-200 flex items-center gap-4 group">
                <div class="w-12 h-12 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                    <img src="https://www.carlogos.org/car-logos/bentley-logo.png" alt="Bentley logo" class="max-h-12 max-w-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display: none;" class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">B</div>
                </div>
                <span class="text-gray-900 font-medium text-base">Bentley</span>
            </a>

            <!-- Citroen -->
            <a href="{{ route('cars.search', ['make' => 'citroen']) }}" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-gray-300 transition-all duration-200 flex items-center gap-4 group">
                <div class="w-12 h-12 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                    <img src="https://www.carlogos.org/car-logos/citroen-logo.png" alt="Citroen logo" class="max-h-12 max-w-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display: none;" class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">C</div>
                </div>
                <span class="text-gray-900 font-medium text-base">Citroen</span>
            </a>

            <!-- CUPRA -->
            <a href="{{ route('cars.search', ['make' => 'cupra']) }}" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-gray-300 transition-all duration-200 flex items-center gap-4 group">
                <div class="w-12 h-12 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                    <img src="https://www.carlogos.org/car-logos/cupra-logo.png" alt="CUPRA logo" class="max-h-12 max-w-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display: none;" class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">C</div>
                </div>
                <span class="text-gray-900 font-medium text-base">CUPRA</span>
            </a>

            <!-- Dacia -->
            <a href="{{ route('cars.search', ['make' => 'dacia']) }}" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-gray-300 transition-all duration-200 flex items-center gap-4 group">
                <div class="w-12 h-12 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                    <img src="https://www.carlogos.org/car-logos/dacia-logo.png" alt="Dacia logo" class="max-h-12 max-w-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display: none;" class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">D</div>
                </div>
                <span class="text-gray-900 font-medium text-base">Dacia</span>
            </a>

            <!-- Ford -->
            <a href="{{ route('cars.search', ['make' => 'ford']) }}" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-gray-300 transition-all duration-200 flex items-center gap-4 group">
                <div class="w-12 h-12 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                    <img src="https://www.carlogos.org/car-logos/ford-logo.png" alt="Ford logo" class="max-h-12 max-w-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display: none;" class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">F</div>
                </div>
                <span class="text-gray-900 font-medium text-base">Ford</span>
            </a>

            <!-- Honda -->
            <a href="{{ route('cars.search', ['make' => 'honda']) }}" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-gray-300 transition-all duration-200 flex items-center gap-4 group">
                <div class="w-12 h-12 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                    <img src="https://www.carlogos.org/car-logos/honda-logo.png" alt="Honda logo" class="max-h-12 max-w-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display: none;" class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">H</div>
                </div>
                <span class="text-gray-900 font-medium text-base">Honda</span>
            </a>

            <!-- Nissan -->
            <a href="{{ route('cars.search', ['make' => 'nissan']) }}" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-gray-300 transition-all duration-200 flex items-center gap-4 group">
                <div class="w-12 h-12 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                    <img src="https://www.carlogos.org/car-logos/nissan-logo.png" alt="Nissan logo" class="max-h-12 max-w-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display: none;" class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">N</div>
                </div>
                <span class="text-gray-900 font-medium text-base">Nissan</span>
            </a>

            <!-- Toyota -->
            <a href="{{ route('cars.search', ['make' => 'toyota']) }}" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-gray-300 transition-all duration-200 flex items-center gap-4 group">
                <div class="w-12 h-12 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                    <img src="https://www.carlogos.org/car-logos/toyota-logo.png" alt="Toyota logo" class="max-h-12 max-w-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display: none;" class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">T</div>
                </div>
                <span class="text-gray-900 font-medium text-base">Toyota</span>
            </a>
        </div>
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
</section>

