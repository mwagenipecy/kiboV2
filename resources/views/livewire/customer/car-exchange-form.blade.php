<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Guest: login prompt modal --}}
    @guest
        <div id="carExchangeLoginPrompt" role="dialog" aria-modal="true" class="fixed inset-0 z-[9999] grid place-items-center bg-black/50 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-8 text-center shadow-2xl">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50">
                    <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-.53.21-1.04.586-1.414A2 2 0 0114 9a2 2 0 110 4m-2 8a9 9 0 110-18 9 9 0 010 18z"/>
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-bold text-gray-900">Please sign in</h3>
                <p class="mb-6 text-sm text-gray-600">Login is required to submit a car exchange request.</p>
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <button type="button" id="carExchangeLoginBtn" class="rounded-xl bg-emerald-600 px-5 py-2.5 font-semibold text-white hover:bg-emerald-700">Login</button>
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
                const carExchangeLoginBtn = document.getElementById('carExchangeLoginBtn');
                const carExchangeLoginPrompt = document.getElementById('carExchangeLoginPrompt');
                const authModal = document.getElementById('authModal');
                
                if (carExchangeLoginBtn) {
                    carExchangeLoginBtn.addEventListener('click', function() {
                        // Hide the car exchange login prompt
                        if (carExchangeLoginPrompt) {
                            carExchangeLoginPrompt.style.display = 'none';
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
                                if (isHidden && carExchangeLoginPrompt && !document.body.hasAttribute('data-user-authenticated')) {
                                    setTimeout(function() {
                                        carExchangeLoginPrompt.style.display = 'grid';
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

    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Exchange Your Car</h1>
        <p class="text-gray-600">Trade in your current vehicle for a better one</p>
    </div>

    <!-- Success Message -->
    @if (session()->has('exchange_success'))
        <div class="mb-6 rounded-xl px-4 py-3 text-center" style="background-color: rgba(0, 152, 102, 0.1); color: #007a52;">
            {{ session('exchange_success') }}
        </div>
    @endif

    @auth
    <!-- Main Form -->
    <form wire:submit.prevent="submit" class="bg-white rounded-2xl shadow-lg p-8 space-y-8">
        
        <!-- Section 1: Current Vehicle -->
        <div class="space-y-5">
            <h2 class="text-lg font-semibold text-gray-900 pb-2 border-b">Your Current Vehicle</h2>
            
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Make *</label>
                    <select 
                        wire:model.live="current_vehicle_make_id" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Select make</option>
                        @foreach($makes as $make)
                            <option value="{{ $make->id }}">{{ $make->name }}</option>
                        @endforeach
                    </select>
                    @error('current_vehicle_make_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Model *</label>
                    <select 
                        wire:model.defer="current_vehicle_model_id" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        @if(!$current_vehicle_make_id) disabled @endif
                    >
                        <option value="">@if($current_vehicle_make_id) Select model @else Select make first @endif</option>
                        @foreach($currentModels as $m)
                            <option value="{{ $m['id'] }}">{{ $m['name'] }}</option>
                        @endforeach
                    </select>
                    @error('current_vehicle_model_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year *</label>
                    <select 
                        wire:model.defer="current_vehicle_year" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Select year</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                    @error('current_vehicle_year') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Registration Number</label>
                    <input 
                        type="text" 
                        wire:model.defer="current_vehicle_registration" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                        placeholder="e.g., T123ABC"
                    >
                    @error('current_vehicle_registration') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mileage (km)</label>
                    <input 
                        type="number" 
                        wire:model.defer="current_vehicle_mileage" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                        placeholder="e.g., 50000"
                    >
                    @error('current_vehicle_mileage') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Condition *</label>
                    <select 
                        wire:model.defer="current_vehicle_condition" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Select condition</option>
                        <option value="excellent">Excellent</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                    @error('current_vehicle_condition') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Description</label>
                <textarea 
                    wire:model.defer="current_vehicle_description" 
                    rows="3" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                    placeholder="Describe your vehicle's features, any issues, modifications, etc."
                ></textarea>
                @error('current_vehicle_description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Photos</label>
                <input 
                    type="file" 
                    wire:model="current_vehicle_images" 
                    multiple
                    accept="image/*"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                >
                <p class="text-xs text-gray-500 mt-1">You can upload multiple images (max 5MB each)</p>
                @error('current_vehicle_images.*') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Section 2: Desired Vehicle -->
        <div class="space-y-5">
            <h2 class="text-lg font-semibold text-gray-900 pb-2 border-b">Vehicle You Want</h2>
            
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Make</label>
                    <select 
                        wire:model.live="desired_vehicle_make_id" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Any make</option>
                        @foreach($makes as $make)
                            <option value="{{ $make->id }}">{{ $make->name }}</option>
                        @endforeach
                    </select>
                    @error('desired_vehicle_make_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                    <select 
                        wire:model.defer="desired_vehicle_model_id" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        @if(!$desired_vehicle_make_id) disabled @endif
                    >
                        <option value="">@if($desired_vehicle_make_id) Any model @else Select make first @endif</option>
                        @foreach($desiredModels as $m)
                            <option value="{{ $m['id'] }}">{{ $m['name'] }}</option>
                        @endforeach
                    </select>
                    @error('desired_vehicle_model_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year range</label>
                    <div class="grid grid-cols-2 gap-3">
                        <select 
                            wire:model.defer="desired_min_year" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                            <option value="">From</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                        <select 
                            wire:model.defer="desired_max_year" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                            <option value="">To</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('desired_min_year') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    @error('desired_max_year') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Body type</label>
                    <select 
                        wire:model.defer="desired_body_type" 
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
                    @error('desired_body_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fuel type</label>
                    <select 
                        wire:model.defer="desired_fuel_type" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Any fuel type</option>
                        <option value="Petrol">Petrol</option>
                        <option value="Diesel">Diesel</option>
                        <option value="Hybrid">Hybrid</option>
                        <option value="Electric">Electric</option>
                    </select>
                    @error('desired_fuel_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transmission</label>
                    <select 
                        wire:model.defer="desired_transmission" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Any transmission</option>
                        <option value="Automatic">Automatic</option>
                        <option value="Manual">Manual</option>
                    </select>
                    @error('desired_transmission') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Budget</label>
                <input 
                    type="number" 
                    wire:model.defer="max_budget" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                    placeholder="e.g., 25000000"
                >
                @error('max_budget') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Section 3: Additional Info -->
        <div class="space-y-5">
            <h2 class="text-lg font-semibold text-gray-900 pb-2 border-b">Additional Information</h2>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                <input 
                    type="text" 
                    wire:model.defer="location" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                    placeholder="e.g., Dar es Salaam"
                >
                @error('location') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

<div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                <textarea 
                    wire:model.defer="notes" 
                    rows="4" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                    placeholder="Any additional information about your exchange request..."
                ></textarea>
                @error('notes') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button 
                type="submit" 
                class="w-full text-white font-semibold py-4 px-6 rounded-xl transition-colors duration-200 text-lg" style="background-color: #009866;"
            >
                Submit Exchange Request
            </button>
        </div>
    </form>
    @endauth
</div>
