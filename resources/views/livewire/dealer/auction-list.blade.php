<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Vehicle Auctions</h1>
            <p class="text-gray-600">Browse vehicles and submit your best offers</p>
        </div>
        <button wire:click="viewMyOffers" class="mt-4 sm:mt-0 bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition-colors">
            My Offers ({{ $myOffers->where('status', 'pending')->count() }})
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search vehicles..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div>
                <select wire:model.live="filterMake" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">All Makes</option>
                    @foreach($makes as $make)
                        <option value="{{ $make->id }}">{{ $make->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select wire:model.live="filterCondition" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">All Conditions</option>
                    <option value="new">New</option>
                    <option value="used">Used</option>
                </select>
            </div>
            <div>
                <select wire:model.live="sortBy" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="price_high">Price: High to Low</option>
                    <option value="price_low">Price: Low to High</option>
                    <option value="offers">Most Offers</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Auctions Grid -->
    @if($auctions->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($auctions as $auction)
                @php
                    $myOffer = $auction->offers->where('dealer_id', Auth::id())->first();
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Image -->
                    <div class="relative h-48 bg-gray-200">
                        @if($auction->image_front)
                            <img src="{{ Storage::url($auction->image_front) }}" alt="{{ $auction->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Badges -->
                        <div class="absolute top-2 left-2 flex flex-col gap-1">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $auction->condition === 'new' ? 'bg-blue-600 text-white' : 'bg-gray-600 text-white' }}">
                                {{ ucfirst($auction->condition) }}
                            </span>
                            @if($auction->offer_count > 0)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-600 text-white">
                                    {{ $auction->offer_count }} offers
                                </span>
                            @endif
                        </div>

                        @if($myOffer)
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($myOffer->status === 'accepted') bg-green-600 text-white
                                    @elseif($myOffer->status === 'pending') bg-yellow-600 text-white
                                    @elseif($myOffer->status === 'rejected') bg-red-600 text-white
                                    @else bg-gray-600 text-white
                                    @endif">
                                    {{ $myOffer->status === 'pending' ? 'You offered' : ucfirst($myOffer->status) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-1">{{ $auction->title }}</h3>
                        <p class="text-sm text-gray-600 mb-2">
                            {{ $auction->year }} {{ $auction->make->name ?? '' }} {{ $auction->model->name ?? '' }}
                        </p>

                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-500 mb-3">
                            @if($auction->mileage)
                                <span>{{ number_format($auction->mileage) }} km</span>
                            @endif
                            @if($auction->transmission)
                                <span>{{ $auction->transmission }}</span>
                            @endif
                            @if($auction->fuel_type)
                                <span>{{ $auction->fuel_type }}</span>
                            @endif
                            @if($auction->location)
                                <span>{{ $auction->location }}</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between mb-4">
                            <div>
                                @if($auction->asking_price)
                                    <span class="text-lg font-bold text-gray-900">{{ $auction->currency }} {{ number_format($auction->asking_price, 0) }}</span>
                                    <span class="text-xs text-gray-500 block">Asking price</span>
                                @else
                                    <span class="text-lg font-bold text-green-600">Open for offers</span>
                                @endif
                            </div>
                            @if($auction->highest_offer && $auction->offer_count > 0)
                                <div class="text-right">
                                    <span class="text-sm font-semibold text-green-600">{{ $auction->currency }} {{ number_format($auction->highest_offer, 0) }}</span>
                                    <span class="text-xs text-gray-500 block">Highest</span>
                                </div>
                            @endif
                        </div>

                        @if($myOffer && $myOffer->status === 'pending')
                            <div class="text-center">
                                <p class="text-sm text-gray-600 mb-2">Your offer: <span class="font-bold">{{ $myOffer->currency }} {{ number_format($myOffer->offer_amount, 0) }}</span></p>
                                <button wire:click="openOfferModal({{ $auction->id }})" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                    Update Offer
                                </button>
                            </div>
                        @elseif($myOffer && $myOffer->status === 'accepted')
                            <div class="text-center bg-green-50 rounded-lg py-3">
                                <p class="text-green-700 font-semibold">🎉 Your offer was accepted!</p>
                                <p class="text-sm text-green-600">Contact seller to finalize</p>
                            </div>
                        @else
                            <button wire:click="openOfferModal({{ $auction->id }})" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                Make Offer
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $auctions->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No auctions available</h3>
            <p class="text-gray-600">Check back later for new vehicle listings.</p>
        </div>
    @endif

    <!-- Offer Modal -->
    @if($showOfferModal && $selectedAuction)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeOfferModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Make an Offer</h3>
                            <button wire:click="closeOfferModal" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Vehicle Info -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="flex items-center gap-3">
                                @if($selectedAuction->image_front)
                                    <img src="{{ Storage::url($selectedAuction->image_front) }}" alt="{{ $selectedAuction->title }}" class="h-16 w-16 rounded object-cover">
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">{{ $selectedAuction->title }}</p>
                                    <p class="text-sm text-gray-600">{{ $selectedAuction->year }} {{ $selectedAuction->make->name ?? '' }} {{ $selectedAuction->model->name ?? '' }}</p>
                                    @if($selectedAuction->asking_price)
                                        <p class="text-sm text-gray-500">Asking: {{ $selectedAuction->currency }} {{ number_format($selectedAuction->asking_price, 0) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <form wire:submit.prevent="submitOffer">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Offer Amount ({{ $selectedAuction->currency }}) <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model="offerAmount" placeholder="Enter your offer amount" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    @error('offerAmount') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Message to Seller</label>
                                    <textarea wire:model="offerMessage" rows="3" placeholder="Add a message to the seller (optional)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                                    <textarea wire:model="offerTerms" rows="2" placeholder="Any specific terms (payment timeline, inspection, etc.)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" wire:click="closeOfferModal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                                    Submit Offer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- My Offers Modal -->
    @if($showMyOffersModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeMyOffersModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">My Offers</h3>
                                <p class="text-sm text-gray-500">{{ $myOffers->count() }} total offers</p>
                            </div>
                            <button wire:click="closeMyOffersModal" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        @if($myOffers->count() > 0)
                            <div class="space-y-4 max-h-96 overflow-y-auto">
                                @foreach($myOffers as $offer)
                                    <div class="border border-gray-200 rounded-lg p-4 {{ $offer->status === 'accepted' ? 'bg-green-50 border-green-300' : '' }}">
                                        <div class="flex justify-between items-start">
                                            <div class="flex items-center gap-3">
                                                @if($offer->auctionVehicle->image_front)
                                                    <img src="{{ Storage::url($offer->auctionVehicle->image_front) }}" alt="" class="h-12 w-12 rounded object-cover">
                                                @endif
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $offer->auctionVehicle->title }}</p>
                                                    <p class="text-sm text-gray-600">{{ $offer->auctionVehicle->year }} {{ $offer->auctionVehicle->make->name ?? '' }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="px-2 py-0.5 text-xs font-medium rounded-full 
                                                    @if($offer->status === 'accepted') bg-green-100 text-green-800
                                                    @elseif($offer->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($offer->status === 'rejected') bg-red-100 text-red-800
                                                    @elseif($offer->status === 'countered') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($offer->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex justify-between items-center">
                                            <div>
                                                <p class="text-lg font-bold text-gray-900">{{ $offer->currency }} {{ number_format($offer->offer_amount, 0) }}</p>
                                                <p class="text-xs text-gray-500">{{ $offer->created_at->format('M d, Y') }}</p>
                                            </div>
                                            @if($offer->status === 'pending')
                                                <button wire:click="withdrawOffer({{ $offer->id }})" wire:confirm="Withdraw this offer?" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                    Withdraw
                                                </button>
                                            @endif
                                            @if($offer->status === 'countered')
                                                <div class="text-right">
                                                    <p class="text-sm text-blue-600">Counter: {{ $offer->currency }} {{ number_format($offer->counter_amount, 0) }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">You haven't made any offers yet.</p>
                        @endif
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end">
                        <button wire:click="closeMyOffersModal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

