<div class="max-w-4xl mx-auto px-4 py-8">
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

    <!-- Main Form -->
    <form wire:submit.prevent="submit" class="bg-white rounded-2xl shadow-lg p-8 space-y-8">
        
        <!-- Auth Notice -->
        @guest
            <div class="bg-blue-50 border-l-4 border-blue-500 px-4 py-3 rounded">
                <p class="text-sm text-blue-800">
                    <button type="button" onclick="document.getElementById('openAuthModal')?.click()" class="font-semibold underline">
                        Sign in
                    </button> to send your request to dealers
                </p>
            </div>
        @endguest

        @error('auth')
            <div class="bg-amber-50 border-l-4 border-amber-500 px-4 py-3 rounded">
                <p class="text-sm text-amber-800">{{ $message }}</p>
            </div>
        @enderror

        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('open-auth-modal', () => {
                    const authModal = document.getElementById('authModal');
                    const authPanel = document.getElementById('authPanel');
                    if (authModal && authPanel) {
                        authModal.classList.remove('hidden');
                        setTimeout(() => {
                            authPanel.classList.remove('translate-x-full');
                        }, 10);
                        document.body.style.overflow = 'hidden';
                    }
                });
            });
        </script>

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
</div>