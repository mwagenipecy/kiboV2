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

    <!-- Vehicle Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900">{{ $vehicle->title }}</h1>
                <p class="text-lg text-gray-600 mt-2">{{ $vehicle->full_name }}</p>
                <div class="flex items-center gap-3 mt-4">
                    <span class="px-3 py-1 text-sm font-semibold rounded {{ $vehicle->status_badge_class }}">
                        {{ $vehicle->status_label }}
                    </span>
                    <span class="px-3 py-1 text-sm font-semibold rounded {{ $vehicle->origin === 'local' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                        {{ ucfirst($vehicle->origin) }}
                    </span>
                    <span class="px-3 py-1 text-sm font-semibold rounded bg-gray-100 text-gray-800">
                        {{ ucfirst($vehicle->condition) }}
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <!-- Like Button -->
                <button wire:click="toggleLike" class="p-3 rounded-lg border transition-colors {{ $isLiked ? 'bg-red-50 border-red-500 text-red-600 hover:bg-red-100' : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-6 h-6" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>

                <!-- Change Status Button -->
                <button wire:click="openStatusModal" class="px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Change Status
                </button>

                <!-- Quick Approve (if pending) -->
                @if(in_array($vehicle->status->value, ['pending', 'awaiting_approval']))
                    <button wire:click="approveVehicle" class="px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Approve
                    </button>
                @endif

                <!-- Edit Button -->
                <a href="{{ route('admin.vehicles.registration.edit', $vehicle->id) }}" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Price -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-center gap-4">
                <div>
                    <p class="text-sm text-gray-600">Price</p>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($vehicle->price, 2) }} {{ $vehicle->currency }}</p>
                </div>
                @if($vehicle->original_price)
                    <div>
                        <p class="text-sm text-gray-600 line-through">Original</p>
                        <p class="text-xl text-gray-500 line-through">{{ number_format($vehicle->original_price, 2) }} {{ $vehicle->currency }}</p>
                    </div>
                @endif
                @if($vehicle->negotiable)
                    <span class="px-3 py-1 text-sm font-semibold bg-yellow-100 text-yellow-800 rounded-full">Negotiable</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Views</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $vehicle->views->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Unique Viewers</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $vehicle->views->unique('user_id')->where('user_id', '!=', null)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Likes</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $vehicle->likes->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Days Listed</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $vehicle->created_at->diffInDays(now()) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content - Left Side (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Images -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Images</h3>
                
                <div class="grid grid-cols-3 gap-4">
                    @if($vehicle->image_front)
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Front View</p>
                            <img 
                                src="{{ Storage::url($vehicle->image_front) }}" 
                                alt="Front" 
                                class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                wire:click="$set('selectedImage', '{{ Storage::url($vehicle->image_front) }}')"
                            >
                        </div>
                    @endif
                    
                    @if($vehicle->image_side)
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Side View</p>
                            <img 
                                src="{{ Storage::url($vehicle->image_side) }}" 
                                alt="Side" 
                                class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                wire:click="$set('selectedImage', '{{ Storage::url($vehicle->image_side) }}')"
                            >
                        </div>
                    @endif
                    
                    @if($vehicle->image_back)
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Back View</p>
                            <img 
                                src="{{ Storage::url($vehicle->image_back) }}" 
                                alt="Back" 
                                class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                wire:click="$set('selectedImage', '{{ Storage::url($vehicle->image_back) }}')"
                            >
                        </div>
                    @endif
                </div>

                @if($vehicle->other_images && count($vehicle->other_images) > 0)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-2">Additional Images</p>
                        <div class="grid grid-cols-4 gap-4">
                            @foreach($vehicle->other_images as $image)
                                <img 
                                    src="{{ Storage::url($image) }}" 
                                    alt="Additional" 
                                    class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                    wire:click="$set('selectedImage', '{{ Storage::url($image) }}')"
                                >
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Description -->
            @if($vehicle->description)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $vehicle->description }}</p>
                </div>
            @endif

            <!-- Specifications -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Specifications</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    @if($vehicle->body_type)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Body Type</span>
                            <span class="font-medium text-gray-900">{{ $vehicle->body_type }}</span>
                        </div>
                    @endif

                    @if($vehicle->fuel_type)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Fuel Type</span>
                            <span class="font-medium text-gray-900">{{ $vehicle->fuel_type }}</span>
                        </div>
                    @endif

                    @if($vehicle->transmission)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Transmission</span>
                            <span class="font-medium text-gray-900">{{ $vehicle->transmission }}</span>
                        </div>
                    @endif

                    @if($vehicle->engine_capacity)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Engine</span>
                            <span class="font-medium text-gray-900">{{ $vehicle->engine_capacity }}</span>
                        </div>
                    @endif

                    @if($vehicle->drive_type)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Drive Type</span>
                            <span class="font-medium text-gray-900">{{ $vehicle->drive_type }}</span>
                        </div>
                    @endif

                    @if($vehicle->color_exterior)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Exterior Color</span>
                            <span class="font-medium text-gray-900">{{ $vehicle->color_exterior }}</span>
                        </div>
                    @endif

                    @if($vehicle->color_interior)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Interior Color</span>
                            <span class="font-medium text-gray-900">{{ $vehicle->color_interior }}</span>
                        </div>
                    @endif

                    @if($vehicle->doors)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Doors</span>
                            <span class="font-medium text-gray-900">{{ $vehicle->doors }}</span>
                        </div>
                    @endif

                    @if($vehicle->seats)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Seats</span>
                            <span class="font-medium text-gray-900">{{ $vehicle->seats }}</span>
                        </div>
                    @endif

                    @if($vehicle->mileage)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Mileage</span>
                            <span class="font-medium text-gray-900">{{ number_format($vehicle->mileage) }} km</span>
                        </div>
                    @endif

                    @if($vehicle->registration_number)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Registration</span>
                            <span class="font-medium text-gray-900">{{ $vehicle->registration_number }}</span>
                        </div>
                    @endif

                    @if($vehicle->vin)
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">VIN</span>
                            <span class="font-medium text-gray-900 text-sm">{{ $vehicle->vin }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Features -->
            @if($vehicle->features && count($vehicle->features) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Features</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($vehicle->features as $feature)
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $feature }}</span>
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
                
                @if($vehicle->entity)
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Dealer/Owner</p>
                            <p class="font-medium text-gray-900">{{ $vehicle->entity->name }}</p>
                        </div>
                        @if($vehicle->entity->email)
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="text-gray-900">{{ $vehicle->entity->email }}</p>
                            </div>
                        @endif
                        @if($vehicle->entity->phone)
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="text-gray-900">{{ $vehicle->entity->phone }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500">No dealer assigned</p>
                @endif

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600">Registered By</p>
                    <p class="font-medium text-gray-900">{{ $vehicle->registeredBy->name }}</p>
                    <p class="text-sm text-gray-500">{{ $vehicle->created_at->format('M d, Y') }}</p>
                </div>

                @if($vehicle->approved_at)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">Approved By</p>
                        <p class="font-medium text-gray-900">{{ $vehicle->approvedBy?->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $vehicle->approved_at->format('M d, Y') }}</p>
                    </div>
                @endif
            </div>

            <!-- Users Who Liked -->
            @if($vehicle->likes->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Liked By ({{ $vehicle->likes->count() }})</h3>
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @foreach($vehicle->likes as $like)
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-green-700 font-semibold text-sm">{{ $like->user->initials() }}</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 text-sm">{{ $like->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $like->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Recent Viewers -->
            @if($vehicle->views->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Viewers</h3>
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @foreach($vehicle->views->where('user_id', '!=', null)->sortByDesc('viewed_at')->take(10) as $view)
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-700 font-semibold text-sm">{{ $view->user->initials() }}</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 text-sm">{{ $view->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $view->viewed_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Internal Notes -->
            @if($vehicle->notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Internal Notes</h3>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $vehicle->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Image Preview Modal -->
    @if($selectedImage)
        <div 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80"
            wire:click="$set('selectedImage', null)"
        >
            <div class="relative max-w-7xl w-full max-h-[90vh]" wire:click.stop>
                <button 
                    wire:click="$set('selectedImage', null)"
                    class="absolute top-4 right-4 z-10 bg-white/90 hover:bg-white text-gray-900 rounded-full p-2 transition-colors"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <img 
                    src="{{ $selectedImage }}" 
                    alt="Preview" 
                    class="w-full h-full object-contain rounded-lg"
                >
            </div>
        </div>
    @endif

    <!-- Status Change Modal -->
    @if($showStatusModal)
        <div class="fixed inset-0 bg-black/50  bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Vehicle Status</h3>
                
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
