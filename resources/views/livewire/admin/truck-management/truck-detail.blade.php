<div class="max-w-7xl mx-auto">
    <!-- Success/Error Messages -->
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

    <!-- Truck Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900">{{ $truck->title }}</h1>
                <p class="text-lg text-gray-600 mt-2">{{ $truck->full_name }}</p>
                <div class="flex items-center gap-3 mt-4">
                    @php
                        $statusClasses = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'awaiting_approval' => 'bg-orange-100 text-orange-800',
                            'approved' => 'bg-green-100 text-green-800',
                            'hold' => 'bg-blue-100 text-blue-800',
                            'sold' => 'bg-purple-100 text-purple-800',
                            'removed' => 'bg-gray-100 text-gray-800',
                        ];
                        $statusClass = $statusClasses[$truck->status->value] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-3 py-1 text-sm font-semibold rounded {{ $statusClass }}">
                        {{ ucfirst(str_replace('_', ' ', $truck->status->value)) }}
                    </span>
                    <span class="px-3 py-1 text-sm font-semibold rounded {{ $truck->origin === 'local' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                        {{ ucfirst($truck->origin) }}
                    </span>
                    <span class="px-3 py-1 text-sm font-semibold rounded bg-gray-100 text-gray-800">
                        {{ ucfirst($truck->condition) }}
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <!-- Change Status Button -->
                <button wire:click="openStatusModal" class="px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Change Status
                </button>

                <!-- Quick Approve (if pending) -->
                @if(in_array($truck->status->value, ['pending', 'awaiting_approval']))
                    <button wire:click="$set('newStatus', 'approved'); updateStatus()" wire:loading.attr="disabled" class="px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Approve
                    </button>
                @endif

                <!-- Edit Button -->
                <a href="{{ route('admin.trucks.edit', $truck->id) }}" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>

                <!-- Delete Button -->
                <button wire:click="deleteTruck" wire:confirm="Are you sure you want to delete this truck?" class="p-3 border border-red-300 rounded-lg hover:bg-red-50 transition-colors text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Price -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-center gap-4">
                <div>
                    <p class="text-sm text-gray-600">Price</p>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($truck->price, 0) }} {{ $truck->currency }}</p>
                </div>
                @if($truck->original_price)
                    <div>
                        <p class="text-sm text-gray-600 line-through">Original</p>
                        <p class="text-xl text-gray-500 line-through">{{ number_format($truck->original_price, 0) }} {{ $truck->currency }}</p>
                    </div>
                @endif
                @if($truck->negotiable)
                    <span class="px-3 py-1 text-sm font-semibold bg-yellow-100 text-yellow-800 rounded-full">Negotiable</span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content - Left Side (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Images -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Truck Images</h3>
                
                <div class="grid grid-cols-3 gap-4">
                    @if($truck->image_front)
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Front View</p>
                            <img src="{{ asset('storage/' . $truck->image_front) }}" alt="Front" class="w-full h-48 object-cover rounded-lg">
                        </div>
                    @endif
                    
                    @if($truck->image_side)
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Side View</p>
                            <img src="{{ asset('storage/' . $truck->image_side) }}" alt="Side" class="w-full h-48 object-cover rounded-lg">
                        </div>
                    @endif
                    
                    @if($truck->image_back)
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Back View</p>
                            <img src="{{ asset('storage/' . $truck->image_back) }}" alt="Back" class="w-full h-48 object-cover rounded-lg">
                        </div>
                    @endif
                </div>

                @if($truck->other_images && count($truck->other_images) > 0)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-2">Additional Images</p>
                        <div class="grid grid-cols-4 gap-4">
                            @foreach($truck->other_images as $image)
                                <img src="{{ asset('storage/' . $image) }}" alt="Additional" class="w-full h-32 object-cover rounded-lg">
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(!$truck->image_front && !$truck->image_side && !$truck->image_back && (!$truck->other_images || count($truck->other_images) === 0))
                    <div class="text-center py-12 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p>No images available</p>
                    </div>
                @endif
            </div>

            <!-- Description -->
            @if($truck->description)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $truck->description }}</p>
                </div>
            @endif

            <!-- Specifications -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Specifications</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    @if($truck->truck_type)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Truck Type</span>
                            <span class="font-medium text-gray-900">{{ $truck->truck_type }}</span>
                        </div>
                    @endif

                    @if($truck->body_type)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Body Type</span>
                            <span class="font-medium text-gray-900">{{ $truck->body_type }}</span>
                        </div>
                    @endif

                    @if($truck->fuel_type)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Fuel Type</span>
                            <span class="font-medium text-gray-900">{{ ucfirst($truck->fuel_type) }}</span>
                        </div>
                    @endif

                    @if($truck->transmission)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Transmission</span>
                            <span class="font-medium text-gray-900">{{ ucfirst($truck->transmission) }}</span>
                        </div>
                    @endif

                    @if($truck->engine_capacity)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Engine</span>
                            <span class="font-medium text-gray-900">{{ $truck->engine_capacity }}</span>
                        </div>
                    @endif

                    @if($truck->engine_cc)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Engine CC</span>
                            <span class="font-medium text-gray-900">{{ number_format($truck->engine_cc) }} cc</span>
                        </div>
                    @endif

                    @if($truck->drive_type)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Drive Type</span>
                            <span class="font-medium text-gray-900">{{ $truck->drive_type }}</span>
                        </div>
                    @endif

                    @if($truck->axle_configuration)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Axle Configuration</span>
                            <span class="font-medium text-gray-900">{{ $truck->axle_configuration }}</span>
                        </div>
                    @endif

                    @if($truck->color_exterior)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Exterior Color</span>
                            <span class="font-medium text-gray-900">{{ $truck->color_exterior }}</span>
                        </div>
                    @endif

                    @if($truck->color_interior)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Interior Color</span>
                            <span class="font-medium text-gray-900">{{ $truck->color_interior }}</span>
                        </div>
                    @endif

                    @if($truck->doors)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Doors</span>
                            <span class="font-medium text-gray-900">{{ $truck->doors }}</span>
                        </div>
                    @endif

                    @if($truck->seats)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Seats</span>
                            <span class="font-medium text-gray-900">{{ $truck->seats }}</span>
                        </div>
                    @endif

                    @if($truck->mileage)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Mileage</span>
                            <span class="font-medium text-gray-900">{{ number_format($truck->mileage) }} km</span>
                        </div>
                    @endif

                    @if($truck->registration_number)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Registration</span>
                            <span class="font-medium text-gray-900">{{ $truck->registration_number }}</span>
                        </div>
                    @endif

                    @if($truck->vin)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">VIN</span>
                            <span class="font-medium text-gray-900 text-sm">{{ $truck->vin }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Truck Capacities -->
            @if($truck->cargo_capacity_kg || $truck->towing_capacity_kg || $truck->payload_capacity_kg || $truck->bed_length_m || $truck->bed_width_m)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Truck Capacities</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        @if($truck->cargo_capacity_kg)
                            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Cargo Capacity</span>
                                <span class="font-medium text-gray-900">{{ number_format($truck->cargo_capacity_kg, 0) }} kg</span>
                            </div>
                        @endif

                        @if($truck->towing_capacity_kg)
                            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Towing Capacity</span>
                                <span class="font-medium text-gray-900">{{ number_format($truck->towing_capacity_kg, 0) }} kg</span>
                            </div>
                        @endif

                        @if($truck->payload_capacity_kg)
                            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Payload Capacity</span>
                                <span class="font-medium text-gray-900">{{ number_format($truck->payload_capacity_kg, 0) }} kg</span>
                            </div>
                        @endif

                        @if($truck->bed_length_m)
                            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Bed Length</span>
                                <span class="font-medium text-gray-900">{{ number_format($truck->bed_length_m, 2) }} m</span>
                            </div>
                        @endif

                        @if($truck->bed_width_m)
                            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Bed Width</span>
                                <span class="font-medium text-gray-900">{{ number_format($truck->bed_width_m, 2) }} m</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Features -->
            @if($truck->features && count($truck->features) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Features</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($truck->features as $feature)
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $feature }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Safety Features -->
            @if($truck->safety_features && count($truck->safety_features) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Safety Features</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($truck->safety_features as $feature)
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">{{ $feature }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar - Right Side (1/3) -->
        <div class="space-y-6">
            <!-- Owner/Dealer Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Owner Information</h3>
                
                @if($truck->entity)
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Dealer/Owner</p>
                            <p class="font-medium text-gray-900">{{ $truck->entity->name }}</p>
                        </div>
                        @if($truck->entity->email)
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="text-gray-900">{{ $truck->entity->email }}</p>
                            </div>
                        @endif
                        @if($truck->entity->phone)
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="text-gray-900">{{ $truck->entity->phone }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500">No dealer assigned</p>
                @endif

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600">Registered By</p>
                    <p class="font-medium text-gray-900">{{ $truck->registeredBy->name }}</p>
                    <p class="text-sm text-gray-500">{{ $truck->created_at->format('M d, Y') }}</p>
                </div>

                @if($truck->approved_at)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">Approved By</p>
                        <p class="font-medium text-gray-900">{{ $truck->approvedBy?->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $truck->approved_at->format('M d, Y') }}</p>
                    </div>
                @endif
            </div>

            <!-- Internal Notes -->
            @if($truck->notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Internal Notes</h3>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $truck->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Status Change Modal -->
    @if($showStatusModal)
        <div class="fixed inset-0 bg-black/50 bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Truck Status</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                        <select wire:model="newStatus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="pending">Pending</option>
                            <option value="awaiting_approval">Awaiting Approval</option>
                            <option value="approved">Approved</option>
                            <option value="hold">On Hold</option>
                            <option value="sold">Sold</option>
                            <option value="removed">Removed</option>
                        </select>
                        @error('newStatus') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea wire:model="statusNotes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Add a note about this status change..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button wire:click="closeStatusModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button wire:click="updateStatus" class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700">
                        Update Status
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
