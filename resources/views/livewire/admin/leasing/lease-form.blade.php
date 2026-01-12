<div>
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.leasing.index') }}" class="hover:text-green-600">Leasing</a>
            <span>/</span>
            <span class="text-gray-900">{{ $leaseId ? 'Edit Lease' : 'Create New Lease' }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $leaseId ? 'Edit Lease Offering' : 'Register New Lease Vehicle' }}</h1>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="space-y-6">
            <!-- Vehicle Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Vehicle Title -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Title *</label>
                        <input type="text" wire:model="vehicle_title" placeholder="e.g., 2024 Toyota Camry XLE" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @error('vehicle_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Year -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Year *</label>
                        <input type="number" wire:model="vehicle_year" placeholder="2024" min="1900" max="{{ date('Y') + 2 }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @error('vehicle_year') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Make -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Make *</label>
                        <select wire:model.live="vehicle_make_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">-- Select Make --</option>
                            @foreach($makes as $make)
                                <option value="{{ $make->id }}">{{ $make->name }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_make_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        @error('vehicle_make') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Model -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Model *</label>
                        <select wire:model="vehicle_model_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" {{ !$vehicle_make_id ? 'disabled' : '' }}>
                            <option value="">-- Select Model --</option>
                            @if($vehicle_make_id && $models->count() > 0)
                                @foreach($models as $model)
                                    <option value="{{ $model->id }}">{{ $model->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @if(!$vehicle_make_id)
                            <p class="text-xs text-gray-500 mt-1">Please select a make first</p>
                        @endif
                        @error('vehicle_model_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        @error('vehicle_model') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Variant -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Variant</label>
                        <input type="text" wire:model="vehicle_variant" placeholder="e.g., XLE, Premium" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @error('vehicle_variant') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Condition -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Condition *</label>
                        <select wire:model="condition" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="new">New</option>
                            <option value="used">Used</option>
                            <option value="certified_pre_owned">Certified Pre-Owned</option>
                        </select>
                        @error('condition') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Body Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Body Type</label>
                        <select wire:model="body_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">-- Select Body Type --</option>
                            <option value="Sedan">Sedan</option>
                            <option value="SUV">SUV</option>
                            <option value="Hatchback">Hatchback</option>
                            <option value="Coupe">Coupe</option>
                            <option value="Wagon">Wagon</option>
                            <option value="Van">Van</option>
                            <option value="Truck">Truck</option>
                            <option value="Convertible">Convertible</option>
                            <option value="Pickup">Pickup</option>
                        </select>
                        @error('body_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Fuel Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fuel Type *</label>
                        <select wire:model="fuel_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="petrol">Petrol</option>
                            <option value="diesel">Diesel</option>
                            <option value="electric">Electric</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                        @error('fuel_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Transmission -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Transmission *</label>
                        <select wire:model="transmission" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="automatic">Automatic</option>
                            <option value="manual">Manual</option>
                        </select>
                        @error('transmission') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Engine Capacity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Engine Capacity</label>
                        <input type="text" wire:model="engine_capacity" placeholder="e.g., 2.5L" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @error('engine_capacity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Mileage -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mileage (km)</label>
                        <input type="number" wire:model="mileage" placeholder="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @error('mileage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Color -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Exterior Color</label>
                        <input type="text" wire:model="color_exterior" placeholder="e.g., Black, White" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @error('color_exterior') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Seats -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Number of Seats</label>
                        <input type="number" wire:model="seats" placeholder="5" min="2" max="9" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @error('seats') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Vehicle Description -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Description</label>
                        <textarea wire:model="vehicle_description" rows="3" placeholder="Describe the vehicle..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                        @error('vehicle_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Features -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Features</label>
                        <div class="flex gap-2 mb-2">
                            <input type="text" wire:model="new_feature" placeholder="e.g., Sunroof, Leather Seats" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <button type="button" wire:click="addFeature" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Add</button>
                        </div>
                            @if(count($features) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($features as $index => $feature)
                                    @if(!empty($feature))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-800">
                                        {{ $feature }}
                                        <button type="button" wire:click="removeFeature({{ $index }})" class="ml-2 text-gray-600 hover:text-gray-900">×</button>
                                    </span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Vehicle Images -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Images</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Front Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Front Image</label>
                        <input type="file" wire:model="image_front" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                        @error('image_front') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        @if ($image_front)
                            <img src="{{ $image_front->temporaryUrl() }}" class="mt-2 w-full h-40 object-cover rounded-lg border border-gray-200">
                        @elseif($tempImageFront)
                            <img src="{{ asset('storage/' . $tempImageFront) }}" class="mt-2 w-full h-40 object-cover rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-500 mt-1">Current image</p>
                        @endif
                    </div>

                    <!-- Side Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Side Image</label>
                        <input type="file" wire:model="image_side" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                        @error('image_side') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        @if ($image_side)
                            <img src="{{ $image_side->temporaryUrl() }}" class="mt-2 w-full h-40 object-cover rounded-lg border border-gray-200">
                        @elseif($tempImageSide)
                            <img src="{{ asset('storage/' . $tempImageSide) }}" class="mt-2 w-full h-40 object-cover rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-500 mt-1">Current image</p>
                        @endif
                    </div>

                    <!-- Back Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Back Image</label>
                        <input type="file" wire:model="image_back" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                        @error('image_back') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        @if ($image_back)
                            <img src="{{ $image_back->temporaryUrl() }}" class="mt-2 w-full h-40 object-cover rounded-lg border border-gray-200">
                        @elseif($tempImageBack)
                            <img src="{{ asset('storage/' . $tempImageBack) }}" class="mt-2 w-full h-40 object-cover rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-500 mt-1">Current image</p>
                        @endif
                    </div>
                </div>

                <!-- Other Images -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Images</label>
                    <input type="file" wire:model="other_images" multiple accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                    @error('other_images.*') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    
                    @if(count($tempOtherImages) > 0 || (is_array($other_images) && count(array_filter($other_images))))
                        <div class="grid grid-cols-4 gap-2 mt-4">
                            @foreach($tempOtherImages as $img)
                                <img src="{{ asset('storage/' . $img) }}" class="w-full h-24 object-cover rounded-lg border border-gray-200">
                            @endforeach
                            @if(is_array($other_images))
                                @foreach($other_images as $img)
                                    @if(is_object($img))
                                        <img src="{{ $img->temporaryUrl() }}" class="w-full h-24 object-cover rounded-lg border border-gray-200">
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    @endif
                </div>
                
                <p class="text-xs text-gray-500 mt-2">Images will be automatically optimized and resized for fast loading (max width: 1200px, quality: 85%)</p>
            </div>

            <!-- Basic Lease Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Lease Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Entity/Dealer -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dealer/Entity</label>
                        <select wire:model="entity_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">-- Select Entity (Optional) --</option>
                            @foreach($entities as $entity)
                                <option value="{{ $entity->id }}">{{ $entity->name }}</option>
                            @endforeach
                        </select>
                        @error('entity_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Lease Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lease Title *</label>
                        <input type="text" wire:model="lease_title" placeholder="e.g., 36-Month Premium Lease" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @error('lease_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Lease Description -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lease Description</label>
                        <textarea wire:model="lease_description" rows="3" placeholder="Describe the lease offering..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                        @error('lease_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Continue with the rest of the lease terms sections (unchanged from before) -->
            @include('livewire.admin.leasing.partials.lease-terms-form')

            <!-- Form Actions -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.leasing.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" wire:loading.attr="disabled" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove>{{ $leaseId ? 'Update Lease' : 'Create Lease' }}</span>
                    <span wire:loading>Saving...</span>
                </button>
            </div>
        </div>
    </form>
</div>
