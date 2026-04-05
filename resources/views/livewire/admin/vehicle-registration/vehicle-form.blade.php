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
                    <textarea wire:model="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Detailed description of the vehicle..."></textarea>
                    @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Origin -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Origin <span class="text-red-500">*</span></label>
                    <select wire:model.live="origin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
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

                @if($origin === 'local')
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">City <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="location_city" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g. Dar es Salaam, Arusha">
                    <p class="text-xs text-gray-500 mt-1">Local listings use Tanzania as the country; enter the city where the vehicle is located.</p>
                    @error('location_city') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                @else
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Country <span class="text-red-500">*</span></label>
                        @if($country_id)
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="inline-flex items-center px-3 py-2 rounded-lg bg-gray-100 text-gray-900 text-sm font-medium">{{ $countrySearch }}</span>
                                <button type="button" wire:click="clearCountry" class="text-sm text-green-600 hover:text-green-800 font-medium">Change</button>
                            </div>
                        @else
                            <input
                                type="text"
                                wire:model.live.debounce.150ms="countryQuery"
                                wire:key="vehicle-form-country-query"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="Search by country name or code (e.g. JP, Japan)"
                                autocomplete="off"
                            >
                            @if(count($countryMatchResults) > 0)
                                <ul class="absolute z-30 mt-1 w-full max-h-56 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg" wire:key="country-results-{{ md5($countryQuery) }}">
                                    @foreach($countryMatchResults as $row)
                                        <li wire:key="country-row-{{ $row['id'] }}">
                                            <button type="button" wire:click.prevent="selectCountry({{ $row['id'] }})" class="w-full text-left px-4 py-2.5 text-sm text-gray-900 hover:bg-green-50 border-b border-gray-100 last:border-0">
                                                {{ $row['name'] }} <span class="text-gray-500">({{ $row['code'] }})</span>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            @elseif(strlen(trim($countryQuery ?? '')) > 0)
                                <p class="absolute z-30 mt-1 w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-500 shadow-lg">No countries match “{{ $countryQuery }}”. Try another spelling or code.</p>
                            @endif
                        @endif
                        @error('country_id') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">City <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="location_city" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Enter city name">
                        @error('location_city') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif
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

        @include('partials.vehicle-specification-checkboxes')

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
                    @if($editMode && $existingImageFront && !$image_front)
                        <div class="mb-3 rounded-lg border-2 border-dashed border-green-200 bg-green-50/50 p-2">
                            <p class="text-xs font-medium text-green-800 mb-2">Current</p>
                            <img src="{{ asset('storage/'.$existingImageFront) }}" alt="Current front" class="w-full h-40 object-cover rounded-lg border border-gray-200">
                        </div>
                    @endif
                    <input type="file" wire:model="image_front" accept="image/*" wire:loading.attr="disabled" wire:target="image_front" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent disabled:opacity-60 disabled:cursor-wait">
                    <div wire:loading wire:target="image_front" class="mt-2 inline-flex items-center gap-2 text-xs font-medium text-green-700">
                        <svg class="animate-spin h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Uploading…
                    </div>
                    <p class="text-xs text-gray-500 mt-1">@if($editMode) Upload a file to replace the current image. @endif</p>
                    @error('image_front') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    @if ($image_front)
                        <p class="text-xs font-medium text-gray-600 mt-2">New preview</p>
                        <img src="{{ $image_front->temporaryUrl() }}" class="mt-1 w-full h-40 object-cover rounded-lg border border-green-300">
                    @endif
                </div>

                <!-- Side Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Side Image</label>
                    @if($editMode && $existingImageSide && !$image_side)
                        <div class="mb-3 rounded-lg border-2 border-dashed border-green-200 bg-green-50/50 p-2">
                            <p class="text-xs font-medium text-green-800 mb-2">Current</p>
                            <img src="{{ asset('storage/'.$existingImageSide) }}" alt="Current side" class="w-full h-40 object-cover rounded-lg border border-gray-200">
                        </div>
                    @endif
                    <input type="file" wire:model="image_side" accept="image/*" wire:loading.attr="disabled" wire:target="image_side" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent disabled:opacity-60 disabled:cursor-wait">
                    <div wire:loading wire:target="image_side" class="mt-2 inline-flex items-center gap-2 text-xs font-medium text-green-700">
                        <svg class="animate-spin h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Uploading…
                    </div>
                    <p class="text-xs text-gray-500 mt-1">@if($editMode) Upload a file to replace the current image. @endif</p>
                    @error('image_side') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    @if ($image_side)
                        <p class="text-xs font-medium text-gray-600 mt-2">New preview</p>
                        <img src="{{ $image_side->temporaryUrl() }}" class="mt-1 w-full h-40 object-cover rounded-lg border border-green-300">
                    @endif
                </div>

                <!-- Back Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Back Image</label>
                    @if($editMode && $existingImageBack && !$image_back)
                        <div class="mb-3 rounded-lg border-2 border-dashed border-green-200 bg-green-50/50 p-2">
                            <p class="text-xs font-medium text-green-800 mb-2">Current</p>
                            <img src="{{ asset('storage/'.$existingImageBack) }}" alt="Current back" class="w-full h-40 object-cover rounded-lg border border-gray-200">
                        </div>
                    @endif
                    <input type="file" wire:model="image_back" accept="image/*" wire:loading.attr="disabled" wire:target="image_back" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent disabled:opacity-60 disabled:cursor-wait">
                    <div wire:loading wire:target="image_back" class="mt-2 inline-flex items-center gap-2 text-xs font-medium text-green-700">
                        <svg class="animate-spin h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Uploading…
                    </div>
                    <p class="text-xs text-gray-500 mt-1">@if($editMode) Upload a file to replace the current image. @endif</p>
                    @error('image_back') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    @if ($image_back)
                        <p class="text-xs font-medium text-gray-600 mt-2">New preview</p>
                        <img src="{{ $image_back->temporaryUrl() }}" class="mt-1 w-full h-40 object-cover rounded-lg border border-green-300">
                    @endif
                </div>
            </div>

            <!-- Other Images -->
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Images (Multiple)</label>

                <div
                    wire:loading.flex
                    wire:target="new_other_images"
                    class="absolute inset-0 z-20 items-center justify-center rounded-xl bg-white/85 backdrop-blur-[1px] border border-green-100 shadow-inner"
                    role="status"
                    aria-live="polite"
                >
                    <div class="flex flex-col items-center gap-2 px-4 py-3 text-center">
                        <svg class="animate-spin h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-sm font-semibold text-gray-900">Uploading images</p>
                        <p class="text-xs text-gray-600 max-w-xs">Please wait while your photos are sent to the server.</p>
                    </div>
                </div>
                
                <!-- Hidden file input (do not clear this via global Livewire hooks — it breaks uploads) -->
                <input
                    type="file"
                    id="other_images_input"
                    wire:key="other-gallery-input-{{ $otherImagesInputKey }}"
                    wire:model="new_other_images"
                    accept="image/*"
                    multiple
                    class="hidden"
                >

                <!-- Add Images Button -->
                <button
                    type="button"
                    wire:loading.attr="disabled"
                    wire:target="new_other_images"
                    onclick="document.getElementById('other_images_input')?.click()"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors shadow-sm disabled:opacity-70 disabled:cursor-wait disabled:hover:bg-green-600"
                >
                    <span wire:loading.remove wire:target="new_other_images" class="inline-flex items-center gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Multiple Images
                    </span>
                    <span wire:loading wire:target="new_other_images" class="inline-flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Uploading…
                    </span>
                </button>
                
                <p wire:loading.remove wire:target="new_other_images" class="text-sm text-gray-500 mt-2">Click the button above to select multiple images at once</p>
                <p wire:loading wire:target="new_other_images" class="text-sm font-medium text-green-700 mt-2 inline-flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing upload — thumbnails will appear below when ready.
                </p>
                
                @error('other_images.*') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                @error('new_other_images.*') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror

                @if($editMode && count($existingOtherImages) > 0)
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-700 mb-3">Current gallery ({{ count($existingOtherImages) }})</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach ($existingOtherImages as $index => $path)
                                <div class="relative group" wire:key="existing-other-{{ $index }}-{{ $path }}">
                                    <img src="{{ asset('storage/'.$path) }}" alt="" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                    <button
                                        type="button"
                                        wire:click="removeExistingOtherImage({{ $index }})"
                                        class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                        title="Remove from listing"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if ($other_images && count($other_images) > 0)
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-700 mb-3">Selected Images ({{ count($other_images) }})</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
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
                        </div>
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
            <a
                href="{{ route('admin.vehicles.registration.index') }}"
                wire:loading.class="pointer-events-none opacity-50"
                wire:target="save"
                class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
                Cancel
            </a>
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="save"
                class="min-w-[11rem] inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:from-green-500 disabled:hover:to-green-600"
            >
                <span wire:loading.remove wire:target="save">{{ $editMode ? 'Update Vehicle' : 'Register Vehicle' }}</span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ $editMode ? 'Saving...' : 'Registering...' }}
                </span>
            </button>
        </div>
    </form>

    @if($showErrorModal)
        <div
            class="fixed inset-0 z-[200] flex items-center justify-center p-4"
            role="alertdialog"
            aria-modal="true"
            aria-labelledby="vehicle-form-error-modal-title"
        >
            <div class="absolute inset-0 bg-black/50" wire:click="closeErrorModal"></div>
            <div class="relative bg-white rounded-xl shadow-2xl border border-gray-200 max-w-lg w-full max-h-[min(80vh,32rem)] flex flex-col overflow-hidden">
                <div class="px-6 py-4 border-b border-red-100 bg-red-50 flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3 min-w-0">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </span>
                        <div class="min-w-0">
                            <h3 id="vehicle-form-error-modal-title" class="text-lg font-semibold text-red-900">{{ $errorModalTitle }}</h3>
                            <p class="text-sm text-red-800/90 mt-1">Review the messages below, then correct the form and try again.</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="closeErrorModal"
                        class="shrink-0 rounded-lg p-2 text-red-700 hover:bg-red-100 transition-colors"
                        aria-label="Close"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-4 overflow-y-auto">
                    <ul class="list-disc pl-5 space-y-2 text-sm text-gray-800">
                        @foreach($errorModalMessages as $msg)
                            <li>{{ $msg }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
                    <button
                        type="button"
                        wire:click="closeErrorModal"
                        class="px-5 py-2.5 rounded-lg bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800 transition-colors"
                    >
                        OK
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
