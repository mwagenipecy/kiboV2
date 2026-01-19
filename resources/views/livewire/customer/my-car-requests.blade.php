@php
    use Illuminate\Support\Str;
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">My car requests</h1>
            <p class="text-gray-600 mt-2">Review dealer offers and accept the best one.</p>
        </div>
        <a href="{{ route('cars.find') }}" class="px-5 py-2 rounded-full border-2 border-green-600 text-green-700 font-semibold hover:bg-green-50 transition-colors">
            Create new request
        </a>
    </div>

    @if (session()->has('my_car_requests_success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('my_car_requests_success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($requests as $r)
            <div class="bg-white border border-gray-200 rounded-2xl p-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="font-semibold text-gray-900 text-lg">
                            {{ $r->make?->name ?? 'Any make' }} {{ $r->model?->name ?? '' }}
                        </div>
                        <div class="text-sm text-gray-600 mt-1">
                            @if($r->min_year || $r->max_year) Year: {{ $r->min_year ?? 'Any' }} - {{ $r->max_year ?? 'Any' }} · @endif
                            @if($r->max_budget) Budget: Up to {{ number_format($r->max_budget) }} TZS · @endif
                            @if($r->location) 📍 {{ $r->location }} · @endif
                            Created: {{ $r->created_at->format('M d, Y') }}
                        </div>
                        @if($r->notes)
                            <div class="text-sm text-gray-700 mt-2 italic">"{{ Str::limit($r->notes, 100) }}"</div>
                        @endif
                    </div>
                    <div>
                        @if($r->status === 'closed')
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 text-gray-800 font-medium">Closed</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800 font-medium">Open</span>
                        @endif
                    </div>
                </div>

                <div class="mt-5">
                    <div class="text-sm font-semibold text-gray-900 mb-2">Offers ({{ $r->offers->count() }})</div>
                    @if($r->offers->count() === 0)
                        <div class="text-sm text-gray-600">No offers yet.</div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($r->offers as $offer)
                                <div class="border border-gray-200 rounded-xl p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="font-semibold text-gray-900">
                                                {{ $offer->entity?->name ?? 'Dealer' }}
                                            </div>
                                            <div class="text-sm text-gray-700 mt-1">
                                                @if(!is_null($offer->price)) Price: {{ number_format($offer->price) }} TZS @endif
                                            </div>
                                        </div>
                                        <div class="text-xs">
                                            @if($offer->status === 'accepted')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800 font-medium">Accepted</span>
                                            @elseif($offer->status === 'rejected')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 text-gray-800 font-medium">Rejected</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-800 font-medium">Submitted</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($offer->message)
                                        <div class="text-sm text-gray-700 mt-2">{{ $offer->message }}</div>
                                    @endif

                                    @if($offer->image_path)
                                        <div class="mt-3">
                                            <img src="{{ asset('storage/' . $offer->image_path) }}" class="w-full h-40 object-cover rounded-lg" alt="Offer image">
                                        </div>
                                    @endif

                                    <div class="mt-4 flex justify-end">
                                        @if($r->status === 'open' && $offer->status === 'submitted')
                                            <button wire:click="showAcceptConfirmation({{ $offer->id }})" class="px-5 py-2 rounded-full bg-green-600 text-white font-semibold hover:bg-green-700 transition-colors">
                                                Accept offer
                                            </button>
                                        @elseif($offer->status === 'accepted')
                                            <span class="text-sm text-green-700 font-semibold">You accepted this offer</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center text-gray-600">
                No requests yet.
            </div>
        @endforelse
    </div>

    <!-- Accept Offer Confirmation Modal -->
    @if($showConfirmModal && $selectedOffer)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:click="closeConfirmModal">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeConfirmModal"></div>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" wire:click.stop>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Confirm Accept Offer</h3>
                            <button wire:click="closeConfirmModal" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-4">
                                Are you sure you want to accept this offer? Once accepted, this request will be closed and all other offers will be rejected.
                            </p>

                            <!-- Offer Details -->
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Dealer:</span>
                                    <span class="text-sm text-gray-900">{{ $selectedOffer->entity?->name ?? 'Admin' }}</span>
                                </div>
                                @if($selectedOffer->price)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Price:</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ number_format($selectedOffer->price) }} TZS</span>
                                </div>
                                @endif
                                @if($selectedOffer->message)
                                <div class="pt-2 border-t border-gray-200">
                                    <span class="text-sm font-medium text-gray-700 block mb-1">Message:</span>
                                    <p class="text-sm text-gray-900">{{ $selectedOffer->message }}</p>
                                </div>
                                @endif
                                @if($selectedOffer->image_path)
                                <div class="pt-2 border-t border-gray-200">
                                    <img src="{{ asset('storage/' . $selectedOffer->image_path) }}" class="w-full h-32 object-cover rounded-lg" alt="Offer image">
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <button 
                                type="button" 
                                wire:click="closeConfirmModal" 
                                class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium"
                            >
                                Cancel
                            </button>
                            <button 
                                wire:click="confirmAcceptOffer" 
                                class="px-4 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700"
                            >
                                Confirm Accept
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>


