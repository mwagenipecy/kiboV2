<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Auction Management</h1>
        <p class="text-gray-600">Manage vehicle auctions and dealer offers</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600">Total Auctions</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600">Pending Approval</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_approval'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600">Active</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600">Sold</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['sold'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-600">Total Offers</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['total_offers'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search auctions..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div>
                <select wire:model.live="filterStatus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="active">Active</option>
                    <option value="closed">Closed</option>
                    <option value="sold">Sold</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <select wire:model.live="filterApproval" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">All Approvals</option>
                    <option value="pending">Pending Approval</option>
                    <option value="approved">Approved</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Auctions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('auction_number')">
                            Auction
                            @if($sortField === 'auction_number')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offers</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                            Created
                            @if($sortField === 'created_at')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($auctions as $auction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($auction->image_front)
                                        <img src="{{ Storage::url($auction->image_front) }}" alt="{{ $auction->title }}" class="h-10 w-10 rounded object-cover mr-3">
                                    @else
                                        <div class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center mr-3">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($auction->title, 30) }}</div>
                                        <div class="text-xs text-gray-500">{{ $auction->auction_number }}</div>
                                        <div class="text-xs text-gray-400">{{ $auction->year }} {{ $auction->make->name ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $auction->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $auction->user->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($auction->asking_price)
                                    <div class="text-sm font-medium text-gray-900">{{ $auction->currency }} {{ number_format($auction->asking_price, 0) }}</div>
                                    <div class="text-xs text-gray-500">Asking</div>
                                @else
                                    <span class="text-sm text-green-600 font-medium">Open</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $auction->offer_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $auction->offer_count }} {{ $auction->offer_count === 1 ? 'offer' : 'offers' }}
                                    </span>
                                </div>
                                @if($auction->highest_offer)
                                    <div class="mt-1">
                                        <span class="text-sm font-bold text-green-600">{{ $auction->currency }} {{ number_format($auction->highest_offer, 0) }}</span>
                                        <span class="text-xs text-gray-500">best</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full 
                                        @if($auction->status === 'active') bg-green-100 text-green-800
                                        @elseif($auction->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($auction->status === 'sold') bg-purple-100 text-purple-800
                                        @elseif($auction->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($auction->status) }}
                                    </span>
                                    @if(!$auction->admin_approved && $auction->status === 'pending')
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-orange-100 text-orange-800">
                                            Needs Approval
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $auction->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $auction->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.auctions.detail', $auction->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    
                                    @if($auction->status === 'active')
                                        <a href="{{ route('admin.auctions.detail', $auction->id) }}" class="text-purple-600 hover:text-purple-900 font-semibold">Make Offer</a>
                                    @endif
                                    
                                    @if(!$auction->admin_approved && $auction->status === 'pending')
                                        <button wire:click="approve({{ $auction->id }})" class="text-green-600 hover:text-green-900">Approve</button>
                                    @endif
                                    
                                    @if($auction->status === 'active')
                                        <button wire:click="toggleVisibility({{ $auction->id }})" class="text-gray-600 hover:text-gray-900">
                                            {{ $auction->is_visible ? 'Hide' : 'Show' }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                No auctions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $auctions->links() }}
        </div>
    </div>

    <!-- Detail Modal -->
    @if($showDetailModal && $selectedAuction)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 9999;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeDetailModal"></div>

                <div class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Auction Details</h3>
                            <button wire:click="closeDetailModal" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                @if($selectedAuction->image_front)
                                    <img src="{{ Storage::url($selectedAuction->image_front) }}" alt="{{ $selectedAuction->title }}" class="w-full h-48 object-cover rounded-lg">
                                @endif
                            </div>
                            <div class="space-y-2">
                                <p class="text-sm"><span class="font-medium">Auction #:</span> {{ $selectedAuction->auction_number }}</p>
                                <p class="text-sm"><span class="font-medium">Title:</span> {{ $selectedAuction->title }}</p>
                                <p class="text-sm"><span class="font-medium">Vehicle:</span> {{ $selectedAuction->year }} {{ $selectedAuction->make->name ?? '' }} {{ $selectedAuction->model->name ?? '' }}</p>
                                <p class="text-sm"><span class="font-medium">Condition:</span> {{ ucfirst($selectedAuction->condition) }}</p>
                                <p class="text-sm"><span class="font-medium">Mileage:</span> {{ $selectedAuction->mileage ? number_format($selectedAuction->mileage) . ' km' : 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Owner</p>
                                <p class="text-sm">{{ $selectedAuction->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $selectedAuction->user->email ?? '' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Contact</p>
                                <p class="text-sm">{{ $selectedAuction->contact_name }}</p>
                                <p class="text-xs text-gray-500">{{ $selectedAuction->contact_phone }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Asking Price</p>
                                <p class="text-sm">{{ $selectedAuction->asking_price ? $selectedAuction->currency . ' ' . number_format($selectedAuction->asking_price, 0) : 'Open' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Min Price</p>
                                <p class="text-sm">{{ $selectedAuction->minimum_price ? $selectedAuction->currency . ' ' . number_format($selectedAuction->minimum_price, 0) : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Highest Offer</p>
                                <p class="text-sm text-green-600 font-semibold">{{ $selectedAuction->highest_offer ? $selectedAuction->currency . ' ' . number_format($selectedAuction->highest_offer, 0) : '-' }}</p>
                            </div>
                        </div>

                        @if($selectedAuction->description)
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-700">Description</p>
                                <p class="text-sm text-gray-600">{{ $selectedAuction->description }}</p>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                            <textarea wire:model="adminNotes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                            <button wire:click="saveNotes" class="mt-2 text-sm text-green-600 hover:text-green-800">Save Notes</button>
                        </div>

                        <div class="flex items-center gap-4 mb-4">
                            <label class="text-sm font-medium text-gray-700">Status:</label>
                            <select wire:change="updateStatus({{ $selectedAuction->id }}, $event.target.value)" class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                                <option value="pending" {{ $selectedAuction->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="active" {{ $selectedAuction->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="closed" {{ $selectedAuction->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="sold" {{ $selectedAuction->status === 'sold' ? 'selected' : '' }}>Sold</option>
                                <option value="cancelled" {{ $selectedAuction->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between">
                        @if(!$selectedAuction->admin_approved && $selectedAuction->status === 'pending')
                            <div class="flex gap-2">
                                <button wire:click="approve({{ $selectedAuction->id }})" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                                    Approve
                                </button>
                                <button wire:click="reject({{ $selectedAuction->id }})" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                                    Reject
                                </button>
                            </div>
                        @else
                            <div></div>
                        @endif
                        <button wire:click="closeDetailModal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Offers Modal -->
    @if($showOffersModal && $selectedAuction)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 9999;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeOffersModal"></div>

                <div class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Offers for {{ $selectedAuction->title }}</h3>
                                <p class="text-sm text-gray-500">{{ $selectedAuction->offers->count() }} total offers</p>
                            </div>
                            <button wire:click="closeOffersModal" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-3 max-h-96 overflow-y-auto">
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
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($offer->status) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <span class="font-medium">{{ $offer->dealer->name ?? $offer->dealer_name ?? 'Dealer' }}</span>
                                                @if($offer->company_name)
                                                    <span class="text-gray-400">•</span> {{ $offer->company_name }}
                                                @endif
                                            </p>
                                            @if($offer->message)
                                                <p class="text-sm text-gray-500 mt-1">{{ $offer->message }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500">{{ $offer->created_at->format('M d, Y H:i') }}</p>
                                            <p class="text-xs text-gray-400">{{ $offer->offer_number }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end">
                        <button wire:click="closeOffersModal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Offer Form Modal -->
    @if($showOfferFormModal && $selectedAuction)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 9999;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeOfferFormModal"></div>

                <div class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Submit Offer</h3>
                            <button wire:click="closeOfferFormModal" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Vehicle Summary -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="flex items-center gap-4">
                                @if($selectedAuction->image_front)
                                    <img src="{{ Storage::url($selectedAuction->image_front) }}" alt="{{ $selectedAuction->title }}" class="h-20 w-20 rounded-lg object-cover">
                                @endif
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $selectedAuction->title }}</p>
                                    <p class="text-sm text-gray-600">{{ $selectedAuction->year }} {{ $selectedAuction->make->name ?? '' }} {{ $selectedAuction->model->name ?? '' }}</p>
                                    <div class="flex gap-4 mt-2 text-sm">
                                        @if($selectedAuction->asking_price)
                                            <span class="text-gray-600">Asking: <span class="font-semibold">{{ $selectedAuction->currency }} {{ number_format($selectedAuction->asking_price, 0) }}</span></span>
                                        @else
                                            <span class="text-green-600 font-medium">Open for offers</span>
                                        @endif
                                        @if($selectedAuction->minimum_price)
                                            <span class="text-gray-500">Min: {{ $selectedAuction->currency }} {{ number_format($selectedAuction->minimum_price, 0) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Current Top Offers -->
                        @if($selectedAuction->offers->count() > 0)
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    Current Best Offers (Top 5)
                                </h4>
                                <div class="space-y-2">
                                    @foreach($selectedAuction->offers->sortByDesc('offer_amount')->take(5) as $index => $offer)
                                        <div class="flex items-center justify-between p-3 rounded-lg {{ $index === 0 ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                                            <div class="flex items-center gap-3">
                                                <span class="w-6 h-6 rounded-full {{ $index === 0 ? 'bg-green-600' : 'bg-gray-400' }} text-white text-xs flex items-center justify-center font-bold">
                                                    {{ $index + 1 }}
                                                </span>
                                                <div>
                                                    <span class="font-bold {{ $index === 0 ? 'text-green-700' : 'text-gray-900' }}">
                                                        {{ $offer->currency }} {{ number_format($offer->offer_amount, 0) }}
                                                    </span>
                                                    <span class="text-sm text-gray-500 ml-2">
                                                        by {{ $offer->company_name ?? $offer->dealer_name ?? 'Anonymous' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $offer->created_at->diffForHumans() }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if($selectedAuction->highest_offer)
                                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <p class="text-sm text-yellow-800">
                                            <span class="font-semibold">💡 To beat the best offer, submit more than:</span>
                                            <span class="text-lg font-bold ml-2">{{ $selectedAuction->currency }} {{ number_format($selectedAuction->highest_offer, 0) }}</span>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm text-blue-800">
                                    <span class="font-semibold">🎉 No offers yet!</span> Be the first to make an offer on this vehicle.
                                </p>
                            </div>
                        @endif

                        <!-- Offer Form -->
                        <form wire:submit.prevent="submitOffer">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Offer Amount ({{ $selectedAuction->currency }}) <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model="offerAmount" placeholder="Enter your offer amount" class="w-full px-4 py-3 text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    @error('offerAmount') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Company/Dealer Name</label>
                                    <input type="text" wire:model="companyName" placeholder="e.g., Kibo Auto (Admin)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Message to Seller</label>
                                    <textarea wire:model="offerMessage" rows="2" placeholder="Add a message (optional)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                                    <textarea wire:model="offerTerms" rows="2" placeholder="Payment timeline, inspection terms, etc. (optional)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
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

