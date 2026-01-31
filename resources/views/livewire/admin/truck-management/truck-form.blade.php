<div class="max-w-7xl mx-auto">
    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Success Message -->
        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Basic Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea wire:model="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Detailed description of the truck..."></textarea>
                    @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Origin -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Origin <span class="text-red-500">*</span></label>
                    <select wire:model="origin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="local">Local (Tanzania)</option>
                        <option value="international">International</option>
                    </select>
                    @error('origin') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Condition -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Condition <span class="text-red-500">*</span></label>
                    <select wire:model="condition" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="new">New</option>
                        <option value="used">Used</option>
                        <option value="certified_pre_owned">Certified Pre-Owned</option>
                    </select>
                    @error('condition') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Registration Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Registration Number</label>
                    <input type="text" wire:model="registration_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., T123ABC">
                    @error('registration_number') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- VIN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">VIN (Vehicle Identification Number)</label>
                    <input type="text" wire:model="vin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="17-character VIN">
                    @error('vin') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Make and Model -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Make and Model</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Make -->
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

                <!-- Model -->
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

                <!-- Variant -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Variant</label>
                    <input type="text" wire:model="variant" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., Executive, Limited Edition">
                    @error('variant') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Year -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year <span class="text-red-500">*</span></label>
                    <select wire:model="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Year</option>
                        @for($y = date('Y') + 2; $y >= 1900; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                    @error('year') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Truck Specifications -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Truck Specifications</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Truck Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Truck Type</label>
                    <select wire:model="truck_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Truck Type</option>
                        <option value="Pickup">Pickup</option>
                        <option value="Semi">Semi</option>
                        <option value="Delivery">Delivery</option>
                        <option value="Flatbed">Flatbed</option>
                        <option value="Box Truck">Box Truck</option>
                        <option value="Dump Truck">Dump Truck</option>
                        <option value="Tow Truck">Tow Truck</option>
                        <option value="Refrigerated">Refrigerated</option>
                        <option value="Tanker">Tanker</option>
                        <option value="Crane Truck">Crane Truck</option>
                        <option value="Garbage Truck">Garbage Truck</option>
                        <option value="Fire Truck">Fire Truck</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('truck_type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Body Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Body Type</label>
                    <select wire:model="body_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Body Type</option>
                        <option value="Single Cab">Single Cab</option>
                        <option value="Double Cab">Double Cab</option>
                        <option value="Crew Cab">Crew Cab</option>
                        <option value="Extended Cab">Extended Cab</option>
                        <option value="Regular Cab">Regular Cab</option>
                        <option value="Chassis Cab">Chassis Cab</option>
                        <option value="Flatbed">Flatbed</option>
                        <option value="Box">Box</option>
                        <option value="Van">Van</option>
                        <option value="Dump Body">Dump Body</option>
                        <option value="Platform">Platform</option>
                        <option value="Tanker">Tanker</option>
                        <option value="Refrigerated">Refrigerated</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('body_type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Fuel Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fuel Type</label>
                    <select wire:model="fuel_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Fuel Type</option>
                        <option value="Petrol">Petrol</option>
                        <option value="Diesel">Diesel</option>
                        <option value="Electric">Electric</option>
                        <option value="Hybrid">Hybrid</option>
                    </select>
                    @error('fuel_type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Transmission -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transmission</label>
                    <select wire:model="transmission" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Transmission</option>
                        <option value="Manual">Manual</option>
                        <option value="Automatic">Automatic</option>
                        <option value="CVT">CVT</option>
                        <option value="Semi-Automatic">Semi-Automatic</option>
                    </select>
                    @error('transmission') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Engine Capacity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Engine Capacity</label>
                    <input type="text" wire:model="engine_capacity" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., 2.0L">
                    @error('engine_capacity') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Engine CC -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Engine CC</label>
                    <input type="number" wire:model="engine_cc" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., 2000">
                    @error('engine_cc') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Drive Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Drive Type</label>
                    <select wire:model="drive_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Drive Type</option>
                        <option value="FWD">Front-Wheel Drive (FWD)</option>
                        <option value="RWD">Rear-Wheel Drive (RWD)</option>
                        <option value="AWD">All-Wheel Drive (AWD)</option>
                        <option value="4WD">Four-Wheel Drive (4WD)</option>
                    </select>
                    @error('drive_type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Exterior Color -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Exterior Color</label>
                    <input type="text" wire:model="color_exterior" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., Pearl White">
                    @error('color_exterior') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Interior Color -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Interior Color</label>
                    <input type="text" wire:model="color_interior" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., Black">
                    @error('color_interior') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Doors -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Doors</label>
                    <input type="number" wire:model="doors" min="2" max="6" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('doors') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Seats -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Seats</label>
                    <input type="number" wire:model="seats" min="1" max="50" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('seats') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Mileage -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mileage (KM)</label>
                    <input type="number" wire:model="mileage" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., 50000">
                    @error('mileage') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Truck Capacities -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Truck Capacities</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Cargo Capacity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cargo Capacity (KG)</label>
                    <input type="number" wire:model="cargo_capacity_kg" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., 2000">
                    @error('cargo_capacity_kg') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Towing Capacity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Towing Capacity (KG)</label>
                    <input type="number" wire:model="towing_capacity_kg" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., 3500">
                    @error('towing_capacity_kg') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Payload Capacity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payload Capacity (KG)</label>
                    <input type="number" wire:model="payload_capacity_kg" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., 1500">
                    @error('payload_capacity_kg') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Bed Length -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bed Length (Meters)</label>
                    <input type="number" wire:model="bed_length_m" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., 2.5">
                    @error('bed_length_m') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Bed Width -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bed Width (Meters)</label>
                    <input type="number" wire:model="bed_width_m" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., 1.8">
                    @error('bed_width_m') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Axle Configuration -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Axle Configuration</label>
                    <input type="text" wire:model="axle_configuration" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., 4x2, 4x4, 6x4">
                    @error('axle_configuration') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Pricing -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price <span class="text-red-500">*</span></label>
                    <input type="number" wire:model="price" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., 45000000">
                    @error('price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Currency -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Currency <span class="text-red-500">*</span></label>
                    <select wire:model="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="TZS">TZS - Tanzanian Shilling</option>
                        <option value="USD">USD - US Dollar</option>
                        <option value="EUR">EUR - Euro</option>
                        <option value="GBP">GBP - British Pound</option>
                    </select>
                    @error('currency') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Original Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Original Price (Optional)</label>
                    <input type="number" wire:model="original_price" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="For showing discounts">
                    @error('original_price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Negotiable -->
                <div class="flex items-center pt-7">
                    <input type="checkbox" wire:model="negotiable" id="negotiable" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <label for="negotiable" class="ml-2 text-sm font-medium text-gray-700">Price is negotiable</label>
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Truck Images</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Front Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Front Image</label>
                    <input type="file" wire:model="image_front" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('image_front') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    @if ($tempImageFront || $image_front)
                        <img src="{{ $image_front ? $image_front->temporaryUrl() : $tempImageFront }}" class="mt-2 w-full h-40 object-cover rounded-lg">
                    @endif
                </div>

                <!-- Side Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Side Image</label>
                    <input type="file" wire:model="image_side" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('image_side') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    @if ($tempImageSide || $image_side)
                        <img src="{{ $image_side ? $image_side->temporaryUrl() : $tempImageSide }}" class="mt-2 w-full h-40 object-cover rounded-lg">
                    @endif
                </div>

                <!-- Back Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Back Image</label>
                    <input type="file" wire:model="image_back" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('image_back') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    @if ($tempImageBack || $image_back)
                        <img src="{{ $image_back ? $image_back->temporaryUrl() : $tempImageBack }}" class="mt-2 w-full h-40 object-cover rounded-lg">
                    @endif
                </div>
            </div>

            <!-- Other Images -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Images (Multiple)</label>
                
                <!-- Hidden file input -->
                <input 
                    type="file" 
                    id="other_images_input_truck"
                    wire:model.live="new_other_images" 
                    accept="image/*" 
                    multiple 
                    class="hidden"
                >
                
                <!-- Add Images Button -->
                <button 
                    type="button"
                    onclick="document.getElementById('other_images_input_truck').click()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors shadow-sm"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Multiple Images
                </button>
                
                <p class="text-sm text-gray-500 mt-2">Click the button above to select multiple images at once</p>
                
                @error('other_images.*') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                
                @if ($other_images || !empty($tempOtherImages))
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-700 mb-3">Selected Images ({{ count($other_images) + count($tempOtherImages) }})</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @if ($other_images)
                                @foreach ($other_images as $index => $image)
                                    <div class="relative group">
                                        <img src="{{ $image->temporaryUrl() }}" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                        <button 
                                            type="button"
                                            wire:click="removeOtherImage({{ $index }})"
                                            class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                            title="Remove image"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($tempOtherImages))
                                @foreach ($tempOtherImages as $image)
                                    <img src="{{ $image }}" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Ownership & Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ownership & Status</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dealer/Entity -->
                @if($userIsAdmin)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dealer (Optional)</label>
                    <select wire:model="entity_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Dealer</option>
                        @foreach($dealers as $dealer)
                            <option value="{{ $dealer->id }}">{{ $dealer->name }}</option>
                        @endforeach
                    </select>
                    @error('entity_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">Leave empty if not associated with any dealer</p>
                </div>
                @else
                <!-- Non-admin users: Show entity info (read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dealer/Entity</label>
                    @if($userEntityName)
                        <input type="text" value="{{ $userEntityName }}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                        <input type="hidden" wire:model="entity_id" value="{{ auth()->user()->entity_id }}">
                        <p class="text-xs text-gray-500 mt-1">Truck will be registered under your entity.</p>
                    @else
                        <div class="w-full px-4 py-2 border border-red-300 rounded-lg bg-red-50 text-red-600">
                            <p class="text-sm font-medium">No Entity Assigned</p>
                            <p class="text-xs mt-1">You cannot register a truck without an associated entity. Please contact an administrator.</p>
                        </div>
                    @endif
                    @error('entity_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                @endif

                <!-- Status -->
<div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select wire:model="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="pending">Pending</option>
                        <option value="awaiting_approval">Awaiting Approval</option>
                        <option value="approved">Approved</option>
                        <option value="hold">On Hold</option>
                        <option value="sold">Sold</option>
                        <option value="removed">Removed</option>
                    </select>
                    @error('status') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Internal Notes</label>
                    <textarea wire:model="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Internal notes (not visible to customers)..."></textarea>
                    @error('notes') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('admin.trucks.index') }}" 
               wire:loading.attr="disabled"
               class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                Cancel
            </a>
            <button type="submit" 
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[140px]">
                <span wire:loading.remove wire:target="save">
                    {{ $editMode ? 'Update Truck' : 'Register Truck' }}
                </span>
                <span wire:loading wire:target="save" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </span>
            </button>
        </div>
    </form>

    <!-- Error Modal -->
    @if($showErrorModal)
    <div wire:key="error-modal-wrapper" 
         class="fixed inset-0 z-[9999] overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true"
         style="display: block !important;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black/50 transition-opacity" 
                 wire:click="closeErrorModal"
                 style="cursor: pointer; z-index: 9998;"></div>

            <!-- Center modal -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-[10000]">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ $errorTitle }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 whitespace-pre-line">
                                    {{ $errorMessage }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            wire:click="closeErrorModal" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @script
    <script>
        // Auto-scroll to top when modal shows
        document.addEventListener('livewire:init', () => {
            Livewire.on('error-modal-shown', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });

        // Reset file input after images are added
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', ({ el, component }) => {
                const input = document.getElementById('other_images_input_truck');
                if (input && input.value) {
                    input.value = '';
                }
            });
        });
    </script>
    @endscript

    <!-- Validation Errors Summary (if any) -->
    @if($errors->any())
    <div class="fixed bottom-4 right-4 z-40 max-w-md">
        <div class="bg-black/50 border-l-4 border-red-400 p-4 rounded-lg shadow-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-white">Please fix the following errors:</h3>
                    <div class="mt-2 text-sm text-gray-200">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="ml-auto pl-3">
                    <button type="button" onclick="this.parentElement.parentElement.parentElement.remove()" class="inline-flex text-red-400 hover:text-red-300">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
