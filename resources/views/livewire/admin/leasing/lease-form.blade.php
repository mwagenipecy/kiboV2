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
                    @php
                        $user = auth()->user();
                        $userRole = $user->role ?? null;
                    @endphp
                    @if($userRole === 'admin')
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
                                <p class="text-xs mt-1">You cannot create a lease without an associated entity. Please contact an administrator.</p>
                            </div>
                        @endif
                        @error('entity_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    @endif

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
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
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
                    <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="ml-auto pl-3">
                    <button type="button" onclick="this.parentElement.parentElement.parentElement.remove()" class="inline-flex text-red-400 hover:text-red-600">
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
