<div class="p-6 space-y-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Car Request</h1>
            <p class="text-gray-600">View request details and submit offers.</p>
        </div>
        <a href="{{ route('admin.car-requests') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
            Back
        </a>
    </div>

    @if (session()->has('admin_offer_success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('admin_offer_success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-700">Customer:</span>
                <span class="text-gray-900">{{ $carRequest->customer_name }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Contact:</span>
                <span class="text-gray-900">Hidden</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Vehicle:</span>
                <span class="text-gray-900">{{ $carRequest->make?->name ?? 'Any' }} {{ $carRequest->model?->name ?? '' }}</span>
            </div>
            @if($carRequest->location)
                <div>
                    <span class="font-medium text-gray-700">Location:</span>
                    <span class="text-gray-900">{{ $carRequest->location }}</span>
                </div>
            @endif
            @if($carRequest->min_year || $carRequest->max_year)
                <div>
                    <span class="font-medium text-gray-700">Year:</span>
                    <span class="text-gray-900">{{ $carRequest->min_year ?? 'Any' }} - {{ $carRequest->max_year ?? 'Any' }}</span>
                </div>
            @endif
            @if($carRequest->max_budget)
                <div>
                    <span class="font-medium text-gray-700">Budget:</span>
                    <span class="text-gray-900">Up to {{ number_format($carRequest->max_budget) }}</span>
                </div>
            @endif
            @if($carRequest->fuel_type)
                <div>
                    <span class="font-medium text-gray-700">Fuel:</span>
                    <span class="text-gray-900">{{ $carRequest->fuel_type }}</span>
                </div>
            @endif
            @if($carRequest->transmission)
                <div>
                    <span class="font-medium text-gray-700">Transmission:</span>
                    <span class="text-gray-900">{{ $carRequest->transmission }}</span>
                </div>
            @endif
            <div>
                <span class="font-medium text-gray-700">Status:</span>
                @if($carRequest->status === 'closed')
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 text-gray-800 font-medium text-xs">Closed</span>
                @else
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800 font-medium text-xs">Open</span>
                @endif
            </div>
        </div>

        @if($carRequest->notes)
            <div class="mt-4 text-sm">
                <div class="font-medium text-gray-700">Notes</div>
                <div class="text-gray-900 mt-1">{{ $carRequest->notes }}</div>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Existing Offers ({{ $carRequest->offers->count() }})</h2>
            @if($carRequest->offers->count() === 0)
                <div class="text-sm text-gray-600">No offers yet.</div>
            @else
                <div class="space-y-3">
                    @foreach($carRequest->offers as $offer)
                        <div class="border border-gray-200 rounded-xl p-4 text-sm">
                            <div class="flex items-start justify-between gap-3">
                                <div class="font-semibold text-gray-900">{{ $offer->entity?->name ?? 'Admin' }}</div>
                                <span class="text-xs px-2 py-1 rounded-full 
                                    @if($offer->status === 'accepted') bg-green-100 text-green-800
                                    @elseif($offer->status === 'rejected') bg-gray-100 text-gray-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst($offer->status) }}
                                </span>
                            </div>
                            @if(!is_null($offer->price))
                                <div class="text-gray-700 mt-1">Price: {{ number_format($offer->price) }}</div>
                            @endif
                            @if($offer->message)
                                <div class="text-gray-700 mt-2">{{ $offer->message }}</div>
                            @endif
                            @if($offer->image_path)
                                <div class="mt-3">
                                    <img src="{{ asset('storage/' . $offer->image_path) }}" class="w-full h-40 object-cover rounded-lg" alt="Offer image">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Submit Offer</h2>

            @if($carRequest->status !== 'open')
                <div class="text-sm text-gray-600">This request is closed and no longer accepts offers.</div>
            @else
                <form wire:submit.prevent="submitOffer" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                        <input type="number" wire:model.defer="price" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g. 25000000">
                        @error('price') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Image (optional)</label>
                        <input type="file" wire:model="image" class="w-full text-sm" accept="image/*">
                        @error('image') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea wire:model.defer="message" rows="4" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Your offer message..."></textarea>
                        @error('message') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="submitOffer,image"
                            class="px-5 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 disabled:opacity-60 disabled:cursor-not-allowed"
                        >
                            <span wire:loading.remove wire:target="submitOffer,image">Submit Offer</span>
                            <span wire:loading wire:target="submitOffer,image">Submitting...</span>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>


