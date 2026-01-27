<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">List Your Vehicle</h1>
        <p class="text-gray-600">Fill in the details below to create your vehicle listing</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Step Indicator -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            @for($i = 1; $i <= $totalSteps; $i++)
                <div class="flex items-center flex-1">
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $currentStep >= $i ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-600' }} font-semibold">
                            {{ $i }}
                        </div>
                        <span class="mt-2 text-xs text-gray-600">
                            @if($i === 1) Basic Info
                            @elseif($i === 2) Details
                            @else Photos
                            @endif
                        </span>
                    </div>
                    @if($i < $totalSteps)
                        <div class="flex-1 h-1 mx-2 {{ $currentStep > $i ? 'bg-green-600' : 'bg-gray-200' }}"></div>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Step 1: Basic Information -->
        @if($currentStep === 1)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h3>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea wire:model="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Tell potential buyers about your vehicle..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Condition <span class="text-red-500">*</span></label>
                    <select wire:model="condition" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="used">Used</option>
                        <option value="new">New</option>
                    </select>
                    @error('condition') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year <span class="text-red-500">*</span></label>
                    <select wire:model="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Year</option>
                        @for($y = date('Y') + 1; $y >= 1900; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                    @error('year') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Make <span class="text-red-500">*</span></label>
                    <select wire:model.live="vehicle_make_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Make</option>
                        @foreach($makes as $make)
                            <option value="{{ $make->id }}">{{ $make->name }}</option>
                        @endforeach
                    </select>
                    @error('vehicle_make_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Model <span class="text-red-500">*</span></label>
                    <select wire:model="vehicle_model_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" @if(!$vehicle_make_id) disabled @endif>
                        <option value="">Select Model</option>
                        @foreach($models as $model)
                            <option value="{{ $model->id }}">{{ $model->name }}</option>
                        @endforeach
                    </select>
                    @error('vehicle_model_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price <span class="text-red-500">*</span></label>
                    <div class="flex">
                        <select wire:model="currency" class="px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="TZS">TZS</option>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <option value="GBP">GBP</option>
                            <option value="KES">KES</option>
                        </select>
                        <input type="number" wire:model="price" step="0.01" min="0" class="flex-1 px-4 py-2 border border-gray-300 border-l-0 rounded-r-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="0.00">
                    </div>
                    @error('price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center pt-6">
                    <input type="checkbox" wire:model="negotiable" id="negotiable" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <label for="negotiable" class="ml-2 text-sm text-gray-700">Price is negotiable</label>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="button" wire:click="nextStep" wire:loading.attr="disabled" wire:target="nextStep" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center gap-2">
                    <span wire:loading.remove wire:target="nextStep">Next: Add Details</span>
                    <span wire:loading wire:target="nextStep" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
            </div>
        </div>
        @endif

        <!-- Step 2: Additional Details -->
        @if($currentStep === 2)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Additional Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Body Type</label>
                    <select wire:model="body_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Body Type</option>
                        <option value="Sedan">Sedan</option>
                        <option value="SUV">SUV</option>
                        <option value="Hatchback">Hatchback</option>
                        <option value="Coupe">Coupe</option>
                        <option value="Estate">Estate</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fuel Type</label>
                    <select wire:model="fuel_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Fuel Type</option>
                        <option value="Petrol">Petrol</option>
                        <option value="Diesel">Diesel</option>
                        <option value="Electric">Electric</option>
                        <option value="Hybrid">Hybrid</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transmission</label>
                    <select wire:model="transmission" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Transmission</option>
                        <option value="Manual">Manual</option>
                        <option value="Automatic">Automatic</option>
                        <option value="Semi-Automatic">Semi-Automatic</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Engine Capacity</label>
                    <input type="text" wire:model="engine_capacity" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., 2.0L">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mileage</label>
                    <input type="number" wire:model="mileage" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="0">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                    <input type="text" wire:model="color_exterior" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., Black, White">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Doors</label>
                    <input type="number" wire:model="doors" min="2" max="6" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Seats</label>
                    <input type="number" wire:model="seats" min="1" max="50" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>

            <div class="flex justify-between">
                <button type="button" wire:click="previousStep" wire:loading.attr="disabled" wire:target="previousStep" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-2 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center gap-2">
                    <span wire:loading.remove wire:target="previousStep">Previous</span>
                    <span wire:loading wire:target="previousStep" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
                <button type="button" wire:click="nextStep" wire:loading.attr="disabled" wire:target="nextStep" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center gap-2">
                    <span wire:loading.remove wire:target="nextStep">Next: Add Photos</span>
                    <span wire:loading wire:target="nextStep" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
            </div>
        </div>
        @endif

        <!-- Step 3: Photos -->
        @if($currentStep === 3)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Vehicle Photos</h3>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Main Photo <span class="text-red-500">*</span></label>
                <input type="file" wire:model="image_front" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('image_front') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                @if($image_front)
                    <div class="mt-2">
                        <img src="{{ $image_front->temporaryUrl() }}" alt="Preview" class="h-32 w-auto rounded-lg">
                    </div>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Photos (Optional)</label>
                
                {{-- File Input Container --}}
                <div class="space-y-3">
                    @if(count($other_images) > 0)
                        @foreach($other_images as $index => $image)
                        <div class="flex items-center gap-3 p-3 border border-gray-300 rounded-lg bg-gray-50">
                            <div class="flex-1 flex items-center gap-3">
                                @if(is_object($image) && method_exists($image, 'temporaryUrl'))
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview {{ $index + 1 }}" class="h-16 w-16 object-cover rounded-lg border border-gray-200">
                                @elseif(is_string($image))
                                <img src="{{ asset('storage/' . $image) }}" alt="Preview {{ $index + 1 }}" class="h-16 w-16 object-cover rounded-lg border border-gray-200">
                                @endif
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Photo {{ $index + 1 }}</p>
                                    <p class="text-xs text-gray-500">Uploaded</p>
                                </div>
                            </div>
                            <button type="button" wire:click="removePhoto({{ $index }})" wire:loading.attr="disabled" class="px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors disabled:opacity-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    @endif
                    
                    {{-- Add More Button --}}
                    <div>
                        <input 
                            type="file" 
                            wire:model="newPhotos" 
                            multiple
                            accept="image/*" 
                            id="other_images_input_{{ $currentStep }}"
                            class="hidden"
                        >
                        <button 
                            type="button" 
                            onclick="document.getElementById('other_images_input_{{ $currentStep }}').click()"
                            wire:loading.attr="disabled"
                            class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition-colors flex items-center justify-center gap-2 text-gray-600 hover:text-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="font-medium">
                                <span wire:loading.remove wire:target="newPhotos">Add More Photos</span>
                                <span wire:loading wire:target="newPhotos" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Uploading...
                                </span>
                            </span>
                        </button>
                        <p class="text-xs text-gray-500 mt-1 text-center">You can select multiple photos at once</p>
                    </div>
                </div>
                
                @error('other_images.*') 
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                @enderror
            </div>

            <div class="flex justify-between">
                <button type="button" wire:click="previousStep" wire:loading.attr="disabled" wire:target="previousStep" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-2 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center gap-2">
                    <span wire:loading.remove wire:target="previousStep">Previous</span>
                    <span wire:loading wire:target="previousStep" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
                <button type="submit" wire:loading.attr="disabled" wire:target="save" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center gap-2">
                    <span wire:loading.remove wire:target="save">Submit Listing</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Submitting...
                    </span>
                </button>
            </div>
        </div>
        @endif
    </form>
    </div>
</div>
