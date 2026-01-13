<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Auctions</h1>
            <p class="mt-2 text-gray-600">Manage your vehicles and view offers from dealers</p>
        </div>
        <a href="{{ route('cars.sell-to-dealer') }}" class="mt-4 sm:mt-0 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors">
            + List New Vehicle
        </a>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600">Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['all'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $statusCounts['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600">Active</p>
            <p class="text-2xl font-bold text-green-600">{{ $statusCounts['active'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600">Sold</p>
            <p class="text-2xl font-bold text-purple-600">{{ $statusCounts['sold'] }}</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
        <button wire:click="$set('filterStatus', 'all')" class="px-4 py-2 rounded-lg font-medium transition-colors whitespace-nowrap {{ $filterStatus === 'all' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            All
        </button>
        <button wire:click="$set('filterStatus', 'pending')" class="px-4 py-2 rounded-lg font-medium transition-colors whitespace-nowrap {{ $filterStatus === 'pending' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Pending
        </button>
        <button wire:click="$set('filterStatus', 'active')" class="px-4 py-2 rounded-lg font-medium transition-colors whitespace-nowrap {{ $filterStatus === 'active' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Active
        </button>
        <button wire:click="$set('filterStatus', 'sold')" class="px-4 py-2 rounded-lg font-medium transition-colors whitespace-nowrap {{ $filterStatus === 'sold' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Sold
        </button>
        <button wire:click="$set('filterStatus', 'cancelled')" class="px-4 py-2 rounded-lg font-medium transition-colors whitespace-nowrap {{ $filterStatus === 'cancelled' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Cancelled
        </button>
    </div>

    <!-- Auctions Table -->
    @if($auctions->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offers</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Highest</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($auctions as $auction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($auction->image_front)
                                            <img src="{{ asset('storage/' . $auction->image_front) }}" alt="{{ $auction->title }}" class="h-12 w-12 rounded object-cover mr-3">
                                        @else
                                            <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center mr-3">
                                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $auction->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $auction->year }} {{ $auction->make->name ?? '' }} {{ $auction->model->name ?? '' }}</div>
                                            <div class="text-xs text-gray-400">{{ $auction->auction_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($auction->asking_price)
                                        <div class="text-sm font-semibold text-gray-900">{{ $auction->currency }} {{ number_format($auction->asking_price, 0) }}</div>
                                        @if($auction->minimum_price)
                                            <div class="text-xs text-gray-500">Min: {{ number_format($auction->minimum_price, 0) }}</div>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-500">Open</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $auction->offer_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $auction->offer_count }} offers
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($auction->highest_offer)
                                        <div class="text-sm font-semibold text-green-600">{{ $auction->currency }} {{ number_format($auction->highest_offer, 0) }}</div>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($auction->status === 'active') bg-green-100 text-green-800
                                        @elseif($auction->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($auction->status === 'sold') bg-purple-100 text-purple-800
                                        @elseif($auction->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $auction->status_label }}
                                    </span>
                                    @if(!$auction->is_visible && $auction->status === 'active')
                                        <span class="ml-1 text-xs text-gray-500">(Hidden)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $auction->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $auction->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($auction->offer_count > 0)
                                            <button wire:click="viewOffers({{ $auction->id }})" class="text-green-600 hover:text-green-900 font-medium">
                                                View Offers
                                            </button>
                                        @endif
                                        
                                        @if($auction->status === 'active')
                                            <button wire:click="toggleVisibility({{ $auction->id }})" class="text-gray-600 hover:text-gray-900">
                                                {{ $auction->is_visible ? 'Hide' : 'Show' }}
                                            </button>
                                            <button wire:click="cancelAuction({{ $auction->id }})" wire:confirm="Are you sure you want to cancel this auction?" class="text-red-600 hover:text-red-900">
                                                Cancel
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
            <h3 class="text-lg font-medium text-gray-900 mb-2">No auctions found</h3>
            <p class="text-gray-600 mb-6">List your vehicle and let dealers compete to offer you the best price.</p>
            <a href="{{ route('cars.sell-to-dealer') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors">
                Sell Your Vehicle
            </a>
        </div>
    @endif

    <!-- Offers Modal -->
    @if($showOffersModal && $selectedAuction)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 9999;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="closeOffersModal"></div>

                <div class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Offers for {{ $selectedAuction->title }}</h3>
                                <p class="text-sm text-gray-500">{{ $selectedAuction->offer_count }} offers received</p>
                            </div>
                            <button wire:click="closeOffersModal" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        @if($selectedAuction->offers->count() > 0)
                            <div class="space-y-4 max-h-96 overflow-y-auto">
                                @foreach($selectedAuction->offers->sortByDesc('offer_amount') as $offer)
                                    <div class="border border-gray-200 rounded-lg p-4 {{ $offer->status === 'accepted' ? 'bg-green-50 border-green-300' : '' }}">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-lg font-bold text-gray-900">{{ $offer->currency }} {{ number_format($offer->offer_amount, 0) }}</span>
                                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full 
                                                        @if($offer->status === 'accepted') bg-green-100 text-green-800
                                                        @elseif($offer->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($offer->status === 'rejected') bg-red-100 text-red-800
                                                        @elseif($offer->status === 'countered') bg-blue-100 text-blue-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ $offer->status_label }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    <span class="font-medium">{{ $offer->dealer_name ?? $offer->dealer->name ?? 'Dealer' }}</span>
                                                    @if($offer->company_name)
                                                        <span class="text-gray-400">•</span> {{ $offer->company_name }}
                                                    @endif
                                                </p>
                                                @if($offer->message)
                                                    <p class="text-sm text-gray-500 mt-2">{{ $offer->message }}</p>
                                                @endif
                                                @if($offer->status === 'countered')
                                                    <div class="mt-2 p-2 bg-blue-50 rounded">
                                                        <p class="text-sm text-blue-800">
                                                            Counter: <span class="font-bold">{{ $offer->currency }} {{ number_format($offer->counter_amount, 0) }}</span>
                                                        </p>
                                                        @if($offer->counter_message)
                                                            <p class="text-xs text-blue-600">{{ $offer->counter_message }}</p>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-gray-500">{{ $offer->created_at->format('M d, Y H:i') }}</p>
                                                @if($offer->status === 'pending' && $selectedAuction->status === 'active')
                                                    <div class="mt-2 flex gap-2">
                                                        <button wire:click="viewOfferDetail({{ $offer->id }})" class="text-sm text-green-600 hover:text-green-800 font-medium">
                                                            Review
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No offers yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Offer Detail Modal -->
    @if($showOfferDetailModal && $selectedOffer)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 10000;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="closeOfferDetailModal"></div>

                <div class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Review Offer</h3>
                            <button wire:click="closeOfferDetailModal" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Offer Amount</p>
                                <p class="text-2xl font-bold text-green-600">{{ $selectedOffer->currency }} {{ number_format($selectedOffer->offer_amount, 0) }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-700">Dealer Information</p>
                                <p class="text-gray-900">{{ $selectedOffer->dealer_name ?? $selectedOffer->dealer->name ?? 'N/A' }}</p>
                                @if($selectedOffer->company_name)
                                    <p class="text-sm text-gray-500">{{ $selectedOffer->company_name }}</p>
                                @endif
                                @if($selectedOffer->dealer_phone)
                                    <p class="text-sm text-gray-500">{{ $selectedOffer->dealer_phone }}</p>
                                @endif
                                @if($selectedOffer->dealer_email)
                                    <p class="text-sm text-gray-500">{{ $selectedOffer->dealer_email }}</p>
                                @endif
                            </div>

                            @if($selectedOffer->message)
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Message</p>
                                    <p class="text-gray-600">{{ $selectedOffer->message }}</p>
                                </div>
                            @endif

                            @if($selectedOffer->terms)
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Terms</p>
                                    <p class="text-gray-600">{{ $selectedOffer->terms }}</p>
                                </div>
                            @endif

                            <!-- Counter Offer -->
                            <div class="border-t border-gray-200 pt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Counter Offer (Optional)</p>
                                <div class="grid grid-cols-1 gap-3">
                                    <input type="number" wire:model="counterAmount" placeholder="Your counter amount" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <textarea wire:model="counterMessage" rows="2" placeholder="Message to dealer (optional)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between gap-3">
                        <button wire:click="rejectOffer({{ $selectedOffer->id }})" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                            Reject
                        </button>
                        <div class="flex gap-2">
                            @if($counterAmount)
                                <button wire:click="counterOffer({{ $selectedOffer->id }})" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                                    Counter
                                </button>
                            @endif
                            <button wire:click="acceptOffer({{ $selectedOffer->id }})" wire:confirm="Accept this offer and close the auction?" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                                Accept Offer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

