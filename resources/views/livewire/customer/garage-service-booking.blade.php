<div>
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black/50 backdrop-blur-[1px] transition-opacity" wire:click="closeModal"></div>

            <!-- Modal panel -->
            <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <form wire:submit.prevent="save">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Schedule Service
                            </h3>
                            <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        @if($agentName || !empty($services) || $serviceType)
                        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                            @if($agentName)
                            <p class="text-sm text-gray-700"><span class="font-semibold">Garage:</span> {{ $agentName }}</p>
                            @endif
                            @if(!empty($services))
                            <p class="text-sm text-gray-700">
                                <span class="font-semibold">Services:</span>
                                @foreach($services as $idx => $svc)
                                    {{ ucwords(str_replace('_', ' ', $svc)) }}@if($idx < count($services) - 1), @endif
                                @endforeach
                            </p>
                            @elseif($serviceType)
                            <p class="text-sm text-gray-700">
                                <span class="font-semibold">Service:</span>
                                {{ ucwords(str_replace('_', ' ', $serviceType)) }}
                            </p>
                            @endif
                        </div>
                        @endif

                        {{-- Select Services (in modal) --}}
                        @if(!empty($availableServices) && is_array($availableServices) && count($availableServices) > 0)
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Select Service(s) *</h4>
                            <div class="max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-4 space-y-3 bg-gray-50">
                                @foreach($availableServices as $svc)
                                    <label class="flex items-center gap-3 cursor-pointer p-2 hover:bg-white rounded transition-colors">
                                        <input
                                            type="checkbox"
                                            value="{{ $svc }}"
                                            wire:model="services"
                                            class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                        >
                                        <span class="text-sm font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $svc)) }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @if(empty($services))
                            <p class="text-xs text-red-600 mt-1">Please select at least one service</p>
                            @endif
                            @error('services') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            @error('services.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        @elseif($agentId)
                        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800">No services listed for this garage. You can describe the service needed in the form below.</p>
                        </div>
                        @endif

                        <div class="space-y-4">
                            <!-- Customer Information -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Customer Information</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                        <input type="text" wire:model="customer_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        @error('customer_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                            <input type="email" wire:model="customer_email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                            @error('customer_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                                            <input type="tel" wire:model="customer_phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                            @error('customer_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Vehicle Information -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Vehicle Information</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Make</label>
                                        @if(!empty($availableMakes))
                                            <select wire:model.live="vehicle_make_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                <option value="">Select Make</option>
                                                @foreach($availableMakes as $m)
                                                    <option value="{{ $m['id'] }}">{{ $m['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" wire:model="vehicle_make" placeholder="Enter make" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        @endif
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                                        @if(!empty($availableMakes))
                                            <select wire:model.live="vehicle_model_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" @disabled(!$vehicle_make_id)>
                                                <option value="">{{ $vehicle_make_id ? 'Select Model' : 'Select make first' }}</option>
                                                @foreach($availableModels as $m)
                                                    <option value="{{ $m['id'] }}">{{ $m['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" wire:model="vehicle_model" placeholder="Enter model" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        @endif
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                                        <input type="text" wire:model="vehicle_year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    </div>
                                    {{-- Registration removed per request --}}
                                </div>
                            </div>

                            <!-- Booking Type -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Booking Type</h4>
                                <div class="flex gap-4">
                                    <label class="flex items-center">
                                        <input type="radio" wire:model="booking_type" value="immediate" class="mr-2">
                                        <span class="text-sm text-gray-700">Immediate</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" wire:model="booking_type" value="scheduled" class="mr-2">
                                        <span class="text-sm text-gray-700">Schedule for Later</span>
                                    </label>
                                </div>
                                @error('booking_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Scheduled Date/Time -->
                            @if($booking_type === 'scheduled')
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Schedule Details</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                                        <input type="date" wire:model="scheduled_date" min="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        @error('scheduled_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Time *</label>
                                        <input type="time" wire:model="scheduled_time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        @error('scheduled_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Service Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Service Description</label>
                                <textarea wire:model="service_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Describe the service needed..."></textarea>
                            </div>

                            <!-- Customer Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                                <textarea wire:model="customer_notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Any additional information..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            wire:target="save"
                            class="w-full inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            <span wire:loading.remove wire:target="save">Submit Booking</span>
                            <span wire:loading wire:target="save" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Submitting...
                            </span>
                        </button>
                        <button 
                            type="button" 
                            wire:click="closeModal"
                            wire:loading.attr="disabled"
                            wire:target="save"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
