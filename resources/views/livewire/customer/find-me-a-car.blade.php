<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Guest: login prompt modal --}}
    @guest
        <div id="findMeACarLoginPrompt" role="dialog" aria-modal="true" class="fixed inset-0 z-[9999] grid place-items-center bg-black/50 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-8 text-center shadow-2xl">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50">
                    <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-.53.21-1.04.586-1.414A2 2 0 0114 9a2 2 0 110 4m-2 8a9 9 0 110-18 9 9 0 010 18z"/>
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-bold text-gray-900">Please sign in</h3>
                <p class="mb-6 text-sm text-gray-600">Login is required to send your request to dealers.</p>
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <button type="button" id="findMeACarLoginBtn" class="rounded-xl bg-emerald-600 px-5 py-2.5 font-semibold text-white hover:bg-emerald-700">Login</button>
                    <button type="button" onclick="window.history.back();" class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Back
                    </button>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const findMeACarLoginBtn = document.getElementById('findMeACarLoginBtn');
                const findMeACarLoginPrompt = document.getElementById('findMeACarLoginPrompt');
                const authModal = document.getElementById('authModal');
                
                if (findMeACarLoginBtn) {
                    findMeACarLoginBtn.addEventListener('click', function() {
                        // Hide the find me a car login prompt
                        if (findMeACarLoginPrompt) {
                            findMeACarLoginPrompt.style.display = 'none';
                        }
                        // Trigger the auth modal
                        const openAuthModalBtn = document.getElementById('openAuthModal');
                        if (openAuthModalBtn) {
                            openAuthModalBtn.click();
                        }
                    });
                }

                // Watch for auth modal closing - if user is still not logged in, show the prompt again
                if (authModal) {
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.attributeName === 'class') {
                                const isHidden = authModal.classList.contains('hidden');
                                // If auth modal is closed and user is still a guest, show the login prompt
                                if (isHidden && findMeACarLoginPrompt && !document.body.hasAttribute('data-user-authenticated')) {
                                    setTimeout(function() {
                                        findMeACarLoginPrompt.style.display = 'grid';
                                    }, 350);
                                }
                            }
                        });
                    });
                    
                    observer.observe(authModal, { attributes: true });
                }
            });
        </script>
        @endpush
    @endguest

    <!-- Simple Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Find Your Perfect Car</h1>
        <p class="text-gray-600">We'll match you with dealers who have what you're looking for</p>
    </div>

    <!-- Success Message -->
    @if (session()->has('find_me_success'))
        <div class="mb-6 rounded-xl px-4 py-3 text-center" style="background-color: rgba(0, 152, 102, 0.1); color: #007a52;">
            {{ session('find_me_success') }}
        </div>
    @endif

    @auth
    <!-- Main Form -->
    <form wire:submit.prevent="submit" class="bg-white rounded-2xl shadow-lg p-8 space-y-8">
        
        <!-- Section 1: Essentials -->
        <div class="space-y-5">
            <h2 class="text-lg font-semibold text-gray-900 pb-2 border-b">The Essentials</h2>
            
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Where are you located? *</label>
                    <input 
                        type="text" 
                        wire:model.defer="location" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                        placeholder="e.g., Dar es Salaam"
                    >
                    @error('location') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your budget (max)</label>
                    <input 
                        type="number" 
                        wire:model.defer="max_budget" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                        placeholder="e.g., 25000000"
                    >
                    @error('max_budget') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Section 2: Car Details -->
        <div class="space-y-5">
            <h2 class="text-lg font-semibold text-gray-900 pb-2 border-b">What car do you want?</h2>
            
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Make</label>
                    <select 
                        wire:model.live="vehicle_make_id" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Any make</option>
                        @foreach($makes as $make)
                            <option value="{{ $make->id }}">{{ $make->name }}</option>
                        @endforeach
                    </select>
                    @error('vehicle_make_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                    <select 
                        wire:model.defer="vehicle_model_id" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        @if(!$vehicle_make_id) disabled @endif
                    >
                        <option value="">@if($vehicle_make_id) Any model @else Select make first @endif</option>
                        @foreach($models as $m)
                            <option value="{{ $m['id'] }}">{{ $m['name'] }}</option>
                        @endforeach
                    </select>
                    @error('vehicle_model_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year range</label>
                    <div class="grid grid-cols-2 gap-3">
                        <input 
                            type="number" 
                            wire:model.defer="min_year" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                            placeholder="From"
                        >
                        <input 
                            type="number" 
                            wire:model.defer="max_year" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                            placeholder="To"
                        >
                    </div>
                    @error('min_year') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    @error('max_year') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Body type</label>
                    <select 
                        wire:model.defer="body_type" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Any type</option>
                        <option value="SUV">SUV</option>
                        <option value="Sedan">Sedan</option>
                        <option value="Hatchback">Hatchback</option>
                        <option value="Pickup">Pickup</option>
                        <option value="Wagon">Wagon</option>
                        <option value="Coupe">Coupe</option>
                        <option value="Van">Van</option>
                    </select>
                    @error('body_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fuel type</label>
                    <select 
                        wire:model.defer="fuel_type" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Any fuel type</option>
                        <option value="Petrol">Petrol</option>
                        <option value="Diesel">Diesel</option>
                        <option value="Hybrid">Hybrid</option>
                        <option value="Electric">Electric</option>
                    </select>
                    @error('fuel_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transmission</label>
                    <select 
                        wire:model.defer="transmission" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Any transmission</option>
                        <option value="Automatic">Automatic</option>
                        <option value="Manual">Manual</option>
                    </select>
                    @error('transmission') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Section 3: Additional Details -->
        <div class="space-y-5">
            <h2 class="text-lg font-semibold text-gray-900 pb-2 border-b">Additional preferences</h2>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Anything else? (color, mileage, features, etc.)
                </label>
                <textarea 
                    wire:model.defer="notes" 
                    rows="4" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                    placeholder="e.g., White or silver color, under 80,000km, must have sunroof and leather seats"
                ></textarea>
                @error('notes') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600">
                    <strong>💡 Tip:</strong> The more specific you are, the better matches we can find. Include preferred colors, maximum mileage, and must-have features.
                </p>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button 
                type="submit" 
                class="w-full text-white font-semibold py-4 px-6 rounded-xl transition-colors duration-200 text-lg" style="background-color: #009866;"
            >
                Find My Car
            </button>
        </div>
    </form>
    @endauth
</div>