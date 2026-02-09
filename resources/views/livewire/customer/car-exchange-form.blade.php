<div class="max-w-4xl mx-auto px-4 py-8">
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

    <!-- Main Form -->
    <form wire:submit.prevent="submit" class="bg-white rounded-2xl shadow-lg p-8 space-y-8">
        
        <!-- Auth Notice -->
        @guest
            <div class="bg-blue-50 border-l-4 border-blue-500 px-4 py-3 rounded">
                <p class="text-sm text-blue-800">
                    <button type="button" onclick="document.getElementById('openAuthModal')?.click()" class="font-semibold underline">
                        Sign in
                    </button> to submit your exchange request
                </p>
            </div>
        @endguest

        @error('auth')
            <div class="bg-amber-50 border-l-4 border-amber-500 px-4 py-3 rounded">
                <p class="text-sm text-amber-800">{{ $message }}</p>
            </div>
        @enderror

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
</div>
