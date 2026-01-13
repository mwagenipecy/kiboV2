<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('admin.auctions') }}" class="hover:text-green-600">Auctions</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span>{{ $auction->auction_number }}</span>
        </div>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $auction->title }}</h1>
                <p class="text-gray-600">{{ $auction->year }} {{ $auction->make->name ?? '' }} {{ $auction->model->name ?? '' }}</p>
            </div>
            <div class="flex items-center gap-2">
                @if($auction->status === 'active')
                    <button wire:click="openOfferForm" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition-colors">
                        Make Offer
                    </button>
                @endif
                <a href="{{ route('admin.auctions') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg transition-colors">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Vehicle Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Images -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Photos</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($auction->image_front)
                        <img src="{{ Storage::url($auction->image_front) }}" alt="{{ $auction->title }}" class="w-full h-64 object-cover rounded-lg">
                    @else
                        <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    @if($auction->other_images && count($auction->other_images) > 0)
                        @foreach($auction->other_images as $image)
                            <img src="{{ Storage::url($image) }}" alt="{{ $auction->title }}" class="w-full h-64 object-cover rounded-lg">
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Vehicle Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Information</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Make</p>
                        <p class="font-medium text-gray-900">{{ $auction->make->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Model</p>
                        <p class="font-medium text-gray-900">{{ $auction->model->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Year</p>
                        <p class="font-medium text-gray-900">{{ $auction->year }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Condition</p>
                        <p class="font-medium text-gray-900">{{ ucfirst($auction->condition) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Body Type</p>
                        <p class="font-medium text-gray-900">{{ $auction->body_type ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Transmission</p>
                        <p class="font-medium text-gray-900">{{ $auction->transmission ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Fuel Type</p>
                        <p class="font-medium text-gray-900">{{ $auction->fuel_type ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Engine</p>
                        <p class="font-medium text-gray-900">{{ $auction->engine_capacity ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Mileage</p>
                        <p class="font-medium text-gray-900">{{ $auction->mileage ? number_format($auction->mileage) . ' km' : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Color</p>
                        <p class="font-medium text-gray-900">{{ $auction->color_exterior ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Registration</p>
                        <p class="font-medium text-gray-900">{{ $auction->registration_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">VIN</p>
                        <p class="font-medium text-gray-900">{{ $auction->vin ?? 'N/A' }}</p>
                    </div>
                </div>

                @if($auction->description)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-500 mb-2">Description</p>
                        <p class="text-gray-700">{{ $auction->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Offers Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">All Offers ({{ $auction->offers->count() }})</h3>
                    @if($auction->highest_offer)
                        <div class="text-right">
                            <span class="text-sm text-gray-500">Highest:</span>
                            <span class="text-xl font-bold text-green-600">{{ $auction->currency }} {{ number_format($auction->highest_offer, 0) }}</span>
                        </div>
                    @endif
                </div>

                @if($auction->offers->count() > 0)
                    <div class="space-y-3">
                        @foreach($auction->offers as $index => $offer)
                            <div class="flex items-center justify-between p-4 rounded-lg {{ $index === 0 ? 'bg-green-50 border-2 border-green-300' : 'bg-gray-50 border border-gray-200' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-8 h-8 rounded-full {{ $index === 0 ? 'bg-green-600' : 'bg-gray-400' }} text-white text-sm flex items-center justify-center font-bold">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <span class="text-lg font-bold {{ $index === 0 ? 'text-green-700' : 'text-gray-900' }}">
                                            {{ $offer->currency }} {{ number_format($offer->offer_amount, 0) }}
                                        </span>
                                        <p class="text-sm text-gray-600">
                                            {{ $offer->company_name ?? $offer->dealer_name ?? $offer->dealer->name ?? 'Anonymous' }}
                                        </p>
                                        @if($offer->message)
                                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($offer->message, 100) }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if($offer->status === 'accepted') bg-green-100 text-green-800
                                        @elseif($offer->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($offer->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($offer->status) }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">{{ $offer->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>No offers yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column - Status & Actions -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Auction Status</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Status</span>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full 
                            @if($auction->status === 'active') bg-green-100 text-green-800
                            @elseif($auction->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($auction->status === 'sold') bg-purple-100 text-purple-800
                            @elseif($auction->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($auction->status) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Approval</span>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $auction->admin_approved ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                            {{ $auction->admin_approved ? 'Approved' : 'Pending' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Visibility</span>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $auction->is_visible ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $auction->is_visible ? 'Visible' : 'Hidden' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Total Offers</span>
                        <span class="font-semibold text-gray-900">{{ $auction->offer_count }}</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Change Status</label>
                    <select wire:change="updateStatus($event.target.value)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="pending" {{ $auction->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ $auction->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="closed" {{ $auction->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="sold" {{ $auction->status === 'sold' ? 'selected' : '' }}>Sold</option>
                        <option value="cancelled" {{ $auction->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                @if(!$auction->admin_approved && $auction->status === 'pending')
                    <div class="mt-4 flex gap-2">
                        <button wire:click="approve" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition-colors">
                            Approve
                        </button>
                        <button wire:click="reject" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition-colors">
                            Reject
                        </button>
                    </div>
                @endif

                @if($auction->status === 'active')
                    <button wire:click="toggleVisibility" class="mt-4 w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 rounded-lg transition-colors">
                        {{ $auction->is_visible ? 'Hide from Dealers' : 'Show to Dealers' }}
                    </button>
                @endif
            </div>

            <!-- Pricing Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Asking Price</p>
                        <p class="text-xl font-bold text-gray-900">
                            {{ $auction->asking_price ? $auction->currency . ' ' . number_format($auction->asking_price, 0) : 'Open' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Minimum Price</p>
                        <p class="text-lg font-semibold text-gray-700">
                            {{ $auction->minimum_price ? $auction->currency . ' ' . number_format($auction->minimum_price, 0) : 'Not set' }}
                        </p>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-500">Highest Offer</p>
                        <p class="text-2xl font-bold text-green-600">
                            {{ $auction->highest_offer ? $auction->currency . ' ' . number_format($auction->highest_offer, 0) : '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Owner Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Seller Information</h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="font-medium text-gray-900">{{ $auction->contact_name ?? $auction->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900">{{ $auction->contact_email ?? $auction->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="font-medium text-gray-900">{{ $auction->contact_phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Location</p>
                        <p class="font-medium text-gray-900">{{ $auction->location ?? $auction->city ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Admin Notes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Admin Notes</h3>
                <textarea wire:model="adminNotes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Add internal notes..."></textarea>
                <button wire:click="saveNotes" class="mt-2 w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 rounded-lg transition-colors">
                    Save Notes
                </button>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Created</span>
                        <span class="text-gray-900">{{ $auction->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    @if($auction->approved_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Approved</span>
                            <span class="text-gray-900">{{ $auction->approved_at->format('M d, Y H:i') }}</span>
                        </div>
                    @endif
                    @if($auction->auction_start)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Auction Started</span>
                            <span class="text-gray-900">{{ $auction->auction_start->format('M d, Y H:i') }}</span>
                        </div>
                    @endif
                    @if($auction->deal_closed_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Deal Closed</span>
                            <span class="text-gray-900">{{ $auction->deal_closed_at->format('M d, Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Offer Form Modal -->
    @if($showOfferFormModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 9999;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-black/50 bg-opacity-75 transition-opacity" wire:click="closeOfferFormModal"></div>

                <div class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Submit Your Offer</h3>
                            <button wire:click="closeOfferFormModal" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        @if($auction->highest_offer)
                            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-800">
                                    <span class="font-semibold">💡 To beat the best offer, submit more than:</span>
                                    <span class="text-lg font-bold ml-2">{{ $auction->currency }} {{ number_format($auction->highest_offer, 0) }}</span>
                                </p>
                            </div>
                        @endif

                        <form wire:submit.prevent="submitOffer">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Offer Amount ({{ $auction->currency }}) <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model="offerAmount" placeholder="Enter your offer amount" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    @error('offerAmount') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                                    <input type="text" wire:model="companyName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                                    <textarea wire:model="offerMessage" rows="2" placeholder="Add a message (optional)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Terms</label>
                                    <textarea wire:model="offerTerms" rows="2" placeholder="Payment terms, conditions, etc." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" wire:click="closeOfferFormModal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                                    Cancel
                                </button>
                                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                                    Submit Offer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

