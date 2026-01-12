<div>
    <form wire:submit="save">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <!-- Form Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $agentId ? 'Edit Agent' : 'New Agent Registration' }}
                </h3>
                <p class="text-sm text-gray-600 mt-1">Fill in the agent details below</p>
            </div>

            <!-- Form Fields -->
            <div class="px-6 py-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            wire:model="name" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Full name"
                        >
                        @error('name') 
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            wire:model="email" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="email@example.com"
                        >
                        @error('email') 
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phoneNumber" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="phoneNumber" 
                            wire:model="phoneNumber" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="+255 XXX XXX XXX"
                        >
                        @error('phoneNumber') 
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <div>
                        <label for="agentType" class="block text-sm font-medium text-gray-700 mb-2">
                            Agent Type <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="agentType" 
                            wire:model.live="agentType" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                            <option value="">Select agent type</option>
                            @foreach ($agentTypes as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('agentType') 
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                @if(in_array($agentType, ['garage_owner', 'spare_part']))
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Vehicle Makes <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-300 rounded-lg p-4 bg-gray-50">
                        @foreach($vehicleMakesList as $make)
                            <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-100 p-2 rounded">
                                <input 
                                    type="checkbox" 
                                    wire:model="vehicleMakes" 
                                    value="{{ $make->id }}"
                                    class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                >
                                <span class="text-sm text-gray-700">{{ $make->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('vehicleMakes') 
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                    @if(count($vehicleMakesList) === 0)
                        <p class="text-gray-500 text-sm mt-1">No vehicle makes available. Please add vehicle makes first.</p>
                    @endif
                </div>
                @endif

                @if($agentType === 'garage_owner')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Services Offered <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($availableServices as $key => $label)
                            <label class="flex items-center space-x-2 cursor-pointer p-3 border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-green-500 transition-colors">
                                <input 
                                    type="checkbox" 
                                    wire:model="services" 
                                    value="{{ $key }}"
                                    class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                >
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('services') 
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>
                @endif

                @if($agentType === 'spare_part')
                <div>
                    <label for="sparePartDetails" class="block text-sm font-medium text-gray-700 mb-2">
                        Spare Part Details
                    </label>
                    <textarea 
                        id="sparePartDetails" 
                        wire:model="sparePartDetails" 
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Additional details about spare parts (e.g., parts available, categories, etc.)"
                    ></textarea>
                    @error('sparePartDetails') 
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="licenseNumber" class="block text-sm font-medium text-gray-700 mb-2">
                            License Number
                        </label>
                        <input 
                            type="text" 
                            id="licenseNumber" 
                            wire:model="licenseNumber" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="License number"
                        >
                        @error('licenseNumber') 
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <div>
                        <label for="companyName" class="block text-sm font-medium text-gray-700 mb-2">
                            Company Name
                        </label>
                        <input 
                            type="text" 
                            id="companyName" 
                            wire:model="companyName" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Company name"
                        >
                        @error('companyName') 
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Address
                    </label>
                    <textarea 
                        id="address" 
                        wire:model="address" 
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Full address"
                    ></textarea>
                    @error('address') 
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Coordinates Section -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Location Coordinates
                        </label>
                        <button 
                            type="button" 
                            onclick="getCurrentLocation()"
                            class="text-sm px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Get Current Location</span>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                                Latitude
                            </label>
                            <input 
                                type="number" 
                                step="any"
                                id="latitude" 
                                wire:model="latitude" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="e.g., -6.7924"
                            >
                            @error('latitude') 
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                            @enderror
                        </div>
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                                Longitude
                            </label>
                            <input 
                                type="number" 
                                step="any"
                                id="longitude" 
                                wire:model="longitude" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="e.g., 39.2083"
                            >
                            @error('longitude') 
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                            @enderror
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">You can use the button above to automatically capture your current location or enter coordinates manually.</p>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="status" 
                        wire:model="status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status') 
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button 
                    type="button" 
                    wire:click="cancel"
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-medium"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center font-medium"
                >
                    <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="save">{{ $agentId ? 'Update Agent' : 'Create Agent' }}</span>
                    <span wire:loading wire:target="save">Processing...</span>
                </button>
            </div>
        </div>
    </form>

    <script>
        function showLocationError(title, message) {
            // Remove any existing notification
            const existingNotification = document.getElementById('location-notification');
            if (existingNotification) {
                existingNotification.remove();
            }

            // Create notification element
            const notification = document.createElement('div');
            notification.id = 'location-notification';
            notification.className = 'mt-3 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800';
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h4 class="text-sm font-medium">${title}</h4>
                        <p class="text-xs mt-1 text-red-700">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-3 flex-shrink-0 text-red-400 hover:text-red-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            `;

            // Insert notification after coordinates section
            const coordinatesContainer = document.querySelector('input#latitude').closest('.grid').parentElement;
            coordinatesContainer.appendChild(notification);

            // Auto-remove after 10 seconds
            setTimeout(() => {
                if (notification && notification.parentElement) {
                    notification.remove();
                }
            }, 10000);
        }

        function showLocationSuccess(message) {
            // Remove any existing notification
            const existingNotification = document.getElementById('location-notification');
            if (existingNotification) {
                existingNotification.remove();
            }

            // Create notification element
            const notification = document.createElement('div');
            notification.id = 'location-notification';
            notification.className = 'mt-3 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800';
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-3 flex-shrink-0 text-green-400 hover:text-green-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            `;

            // Insert notification after coordinates section
            const coordinatesContainer = document.querySelector('input#latitude').closest('.grid').parentElement;
            coordinatesContainer.appendChild(notification);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (notification && notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        function getCurrentLocation() {
            if (!navigator.geolocation) {
                showLocationError(
                    'Geolocation is not supported',
                    'Your browser does not support geolocation. Please enter coordinates manually below.'
                );
                return;
            }

            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            const button = event.target.closest('button');
            const originalText = button.innerHTML;

            // Show loading state
            button.disabled = true;
            button.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Getting Location...</span>';

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Update Livewire properties
                    @this.set('latitude', lat.toFixed(8));
                    @this.set('longitude', lng.toFixed(8));

                    // Reset button
                    button.disabled = false;
                    button.innerHTML = originalText;

                    // Show success message
                    showLocationSuccess(`Location captured successfully! Latitude: ${lat.toFixed(8)}, Longitude: ${lng.toFixed(8)}`);
                },
                function(error) {
                    button.disabled = false;
                    button.innerHTML = originalText;

                    let errorMessage = '';
                    let instructionMessage = '';
                    
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = 'Location permission was denied.';
                            instructionMessage = 'To enable location access: 1) Check your browser settings (lock icon in address bar), 2) Allow location permissions for this site, or 3) Enter coordinates manually below.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = 'Location information is unavailable.';
                            instructionMessage = 'Your device may not support GPS or location services may be disabled. Please enter coordinates manually below.';
                            break;
                        case error.TIMEOUT:
                            errorMessage = 'The request to get your location timed out.';
                            instructionMessage = 'Please try again or enter coordinates manually below.';
                            break;
                        default:
                            errorMessage = 'An unknown error occurred while getting your location.';
                            instructionMessage = 'Please enter coordinates manually below.';
                            break;
                    }
                    
                    // Create a better notification element
                    showLocationError(errorMessage, instructionMessage);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }
    </script>
</div>
