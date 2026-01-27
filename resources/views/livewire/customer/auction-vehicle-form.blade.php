<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Sell Your Vehicle to Dealers</h1>
        <p class="text-gray-600">List your vehicle for free and receive offers from verified dealers</p>
    </div>

    <!-- Progress Steps -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            @for($i = 1; $i <= $totalSteps; $i++)
                <div class="flex items-center {{ $i < $totalSteps ? 'flex-1' : '' }}">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $currentStep >= $i ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-600' }} font-semibold">
                        @if($currentStep > $i)
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            {{ $i }}
                        @endif
                    </div>
                    @if($i < $totalSteps)
                        <div class="flex-1 h-1 mx-2 {{ $currentStep > $i ? 'bg-green-600' : 'bg-gray-200' }}"></div>
                    @endif
                </div>
            @endfor
        </div>
        <div class="flex justify-between mt-2 text-sm text-gray-600">
            <span>Vehicle Info</span>
            <span>Specifications</span>
            <span>Photos</span>
            <span>Contact</span>
        </div>
    </div>

    <form wire:submit.prevent="save">
        <!-- Step 1: Basic Information -->
        @if($currentStep === 1)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Vehicle Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                    <select wire:model="vehicle_model_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Model</option>
                        @foreach($models as $model)
                            <option value="{{ $model->id }}">{{ $model->name }}</option>
                        @endforeach
                    </select>
                    @error('vehicle_model_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year <span class="text-red-500">*</span></label>
                    <select wire:model="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Year</option>
                        @for($y = date('Y') + 1; $y >= 1990; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                    @error('year') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Condition <span class="text-red-500">*</span></label>
                    <select wire:model="condition" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="used">Used</option>
                        <option value="new">New</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Variant</label>
                    <input type="text" wire:model="variant" placeholder="e.g., GX, VX, ZX" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea wire:model="description" rows="4" placeholder="Describe your vehicle's condition, features, history..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Registration Number</label>
                    <input type="text" wire:model="registration_number" placeholder="e.g., T123ABC" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">VIN/Chassis Number</label>
                    <input type="text" wire:model="vin" placeholder="e.g., JTMHY..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex justify-end pt-4">
                <button type="button" wire:click="nextStep" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors flex items-center">
                    <span wire:loading.remove wire:target="nextStep">Next Step</span>
                    <span wire:loading wire:target="nextStep" class="flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
            </div>
        </div>
        @endif

        <!-- Step 2: Specifications -->
        @if($currentStep === 2)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Vehicle Specifications</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Body Type</label>
                    <select wire:model="body_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Body Type</option>
                        <option value="Sedan">Sedan</option>
                        <option value="SUV">SUV</option>
                        <option value="Hatchback">Hatchback</option>
                        <option value="Pickup">Pickup</option>
                        <option value="Van">Van</option>
                        <option value="Coupe">Coupe</option>
                        <option value="Wagon">Wagon</option>
                        <option value="Convertible">Convertible</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fuel Type</label>
                    <select wire:model="fuel_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Fuel Type</option>
                        <option value="Petrol">Petrol</option>
                        <option value="Diesel">Diesel</option>
                        <option value="Hybrid">Hybrid</option>
                        <option value="Electric">Electric</option>
                        <option value="LPG">LPG</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transmission</label>
                    <select wire:model="transmission" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Transmission</option>
                        <option value="Automatic">Automatic</option>
                        <option value="Manual">Manual</option>
                        <option value="Semi-Automatic">Semi-Automatic</option>
                        <option value="CVT">CVT</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Engine Capacity</label>
                    <input type="text" wire:model="engine_capacity" placeholder="e.g., 2000cc" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Doors</label>
                    <select wire:model="doors" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select</option>
                        @for($d = 2; $d <= 5; $d++)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Seats</label>
                    <select wire:model="seats" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select</option>
                        @for($s = 2; $s <= 12; $s++)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Exterior Color</label>
                    <input type="text" wire:model="color_exterior" placeholder="e.g., White, Black, Silver" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mileage (km)</label>
                    <input type="number" wire:model="mileage" placeholder="e.g., 50000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Pricing (Optional)</h4>
                <p class="text-sm text-gray-600 mb-4">Set your asking price or leave blank to receive open offers from dealers</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                        <select wire:model="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="TZS">TZS</option>
                            <option value="USD">USD</option>
                            <option value="GBP">GBP</option>
                            <option value="EUR">EUR</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Asking Price</label>
                        <input type="number" wire:model="asking_price" placeholder="Your asking price" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Price</label>
                        <input type="number" wire:model="minimum_price" placeholder="Lowest you'll accept" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex justify-between pt-4">
                <button type="button" wire:click="previousStep" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-2 rounded-lg transition-colors flex items-center">
                    <span wire:loading.remove wire:target="previousStep">Previous</span>
                    <span wire:loading wire:target="previousStep" class="flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
                <button type="button" wire:click="nextStep" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors flex items-center">
                    <span wire:loading.remove wire:target="nextStep">Next Step</span>
                    <span wire:loading wire:target="nextStep" class="flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
            <p class="text-sm text-gray-600">Upload clear photos of your vehicle. Good photos attract more offers!</p>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Main Photo <span class="text-red-500">*</span></label>
                <input type="file" wire:model="image_front" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('image_front') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                @if($image_front)
                    <div class="mt-2">
                        <img src="{{ $image_front->temporaryUrl() }}" alt="Preview" class="h-48 w-auto rounded-lg object-cover">
                    </div>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Photos</label>
                <p class="text-xs text-gray-500 mb-3">You can upload up to 10 additional photos. Click "Add Photos" to select one or multiple images at once.</p>
                
                <!-- Image Previews (show first if there are images) -->
                @if(count($other_images) > 0)
                    <div class="mb-4 grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                        @foreach($other_images as $index => $image)
                            <div class="relative group">
                                <img src="{{ $image->temporaryUrl() }}" alt="Photo {{ $index + 1 }}" class="h-24 w-24 rounded-lg object-cover border border-gray-200">
                                <!-- Remove Button -->
                                <button 
                                    type="button" 
                                    wire:click="removeOtherImage({{ $index }})"
                                    class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity"
                                    title="Remove image"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mb-3">{{ count($other_images) }} of 10 photo(s) added. Hover over an image to remove it.</p>
                @endif
                
                <!-- Hidden file input - uses newImages to append -->
                <input 
                    type="file" 
                    wire:model="newImages" 
                    accept="image/*" 
                    multiple 
                    class="hidden"
                    id="other-images-input"
                >
                
                <!-- Add Photos Button -->
                @if(count($other_images) < 10)
                    <button 
                        type="button" 
                        onclick="document.getElementById('other-images-input').click()"
                        class="inline-flex items-center px-4 py-2 bg-green-50 hover:bg-green-100 text-green-700 font-medium rounded-lg border border-green-300 transition-colors"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        {{ count($other_images) > 0 ? 'Add More Photos' : 'Add Photos' }}
                        <span wire:loading wire:target="newImages" class="ml-2">
                            <svg class="animate-spin h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                    <span class="text-xs text-gray-500 ml-2">Select one or multiple photos</span>
                @else
                    <p class="text-sm text-amber-600">Maximum 10 photos reached. Remove some to add more.</p>
                @endif
            </div>

            <!-- Navigation -->
            <div class="flex justify-between pt-4">
                <button type="button" wire:click="previousStep" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-2 rounded-lg transition-colors flex items-center">
                    <span wire:loading.remove wire:target="previousStep">Previous</span>
                    <span wire:loading wire:target="previousStep" class="flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
                <button type="button" wire:click="nextStep" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors flex items-center">
                    <span wire:loading.remove wire:target="nextStep">Next Step</span>
                    <span wire:loading wire:target="nextStep" class="flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
            </div>
        </div>
        @endif

        <!-- Step 4: Contact & Location -->
        @if($currentStep === 4)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Contact & Location</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Name <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="contact_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('contact_name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone <span class="text-red-500">*</span></label>
                    <input type="tel" wire:model="contact_phone" placeholder="+255..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('contact_phone') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email <span class="text-red-500">*</span></label>
                <input type="email" wire:model="contact_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('contact_email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Vehicle Location</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Region</label>
                        <input type="text" wire:model="region" placeholder="e.g., Dar es Salaam" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">City/District</label>
                        <input type="text" wire:model="city" placeholder="e.g., Kinondoni" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location/Area</label>
                        <input type="text" wire:model="location" placeholder="e.g., Masaki, Mikocheni" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Summary Box -->
            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                <h4 class="font-semibold text-green-800 mb-2">What happens next?</h4>
                <ul class="text-sm text-green-700 space-y-1">
                    <li>• Your listing will be reviewed by our team (usually within 24 hours)</li>
                    <li>• Once approved, verified dealers will be able to view and make offers</li>
                    <li>• You'll receive notifications when dealers make offers</li>
                    <li>• You can accept, reject, or counter any offer</li>
                    <li>• Close the deal with the dealer of your choice</li>
                </ul>
            </div>

            <!-- Navigation -->
            <div class="flex justify-between pt-4">
                <button type="button" wire:click="previousStep" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-2 rounded-lg transition-colors flex items-center">
                    <span wire:loading.remove wire:target="previousStep">Previous</span>
                    <span wire:loading wire:target="previousStep" class="flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
                <button type="submit" wire:loading.attr="disabled" class="bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white font-semibold px-8 py-2 rounded-lg transition-colors flex items-center">
                    <span wire:loading.remove wire:target="save">Submit for Auction</span>
                    <span wire:loading wire:target="save" class="flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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

