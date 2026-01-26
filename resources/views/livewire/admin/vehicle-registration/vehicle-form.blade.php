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
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Title <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., 2024 Toyota Land Cruiser V8">
                    @error('title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea wire:model="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Detailed description of the vehicle..."></textarea>
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
                    <select wire:model.live="condition" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="new">New</option>
                        <option value="used">Used</option>
                        <option value="certified_pre_owned">Certified Pre-Owned</option>
                    </select>
                    @error('condition') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Registration Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Registration Number <span>(Optional)</span> </label>
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
                    <input type="number" wire:model="year" min="1900" max="{{ date('Y') + 2 }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('year') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Specifications -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Specifications</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Body Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Body Type</label>
                    <select wire:model="body_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Body Type</option>
                        <option value="Sedan">Sedan</option>
                        <option value="SUV">SUV</option>
                        <option value="Hatchback">Hatchback</option>
                        <option value="Coupe">Coupe</option>
                        <option value="Pickup">Pickup</option>
                        <option value="Van">Van</option>
                        <option value="Wagon">Wagon</option>
                        <option value="Convertible">Convertible</option>
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
                        <option value="Plug-in Hybrid">Plug-in Hybrid</option>
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
                    <input type="text" wire:model="color_interior" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., Black Leather">
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
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Images</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Front Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Front Image</label>
                    <input type="file" wire:model="image_front" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('image_front') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    @if ($image_front)
                        <img src="{{ $image_front->temporaryUrl() }}" class="mt-2 w-full h-40 object-cover rounded-lg">
                    @endif
                </div>

                <!-- Side Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Side Image</label>
                    <input type="file" wire:model="image_side" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('image_side') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    @if ($image_side)
                        <img src="{{ $image_side->temporaryUrl() }}" class="mt-2 w-full h-40 object-cover rounded-lg">
                    @endif
                </div>

                <!-- Back Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Back Image</label>
                    <input type="file" wire:model="image_back" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('image_back') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    @if ($image_back)
                        <img src="{{ $image_back->temporaryUrl() }}" class="mt-2 w-full h-40 object-cover rounded-lg">
                    @endif
                </div>
            </div>

            <!-- Other Images -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Images (Multiple)</label>
                <input type="file" wire:model="other_images" accept="image/*" multiple class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('other_images.*') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                @if ($other_images)
                    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach ($other_images as $image)
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-32 object-cover rounded-lg">
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Ownership & Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ownership & Status</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dealer/Entity (Only for Admin) -->
                @php
                    $user = Auth::user();
                    $userRole = $user->role ?? null;
                @endphp
                @if($userRole === 'admin')
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
                    @if($user->entity_id && $user->entity)
                        <input type="text" value="{{ $user->entity->name }}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                        <input type="hidden" wire:model="entity_id" value="{{ $user->entity_id }}">
                        <p class="text-xs text-gray-500 mt-1">Your associated entity (automatically assigned)</p>
                    @else
                        <div class="w-full px-4 py-2 border border-red-300 rounded-lg bg-red-50 text-red-600">
                            <p class="text-sm font-medium">No Entity Assigned</p>
                            <p class="text-xs mt-1">You cannot register a vehicle without an associated entity. Please contact an administrator.</p>
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
            <a href="{{ route('admin.vehicles.registration.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm">
                {{ $editMode ? 'Update Vehicle' : 'Register Vehicle' }}
            </button>
        </div>
    </form>
</div>
