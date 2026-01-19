<div class="p-6 space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Car Requests</h1>
        <p class="text-gray-600">Submit offers to customers looking for specific vehicles.</p>
    </div>

    @if (session()->has('dealer_offer_success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('dealer_offer_success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="divide-y divide-gray-200">
            @forelse($requests as $r)
                @php
                    $myOffer = $myOffers->get($r->id);
                @endphp
                <div class="p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <div class="font-semibold text-gray-900">
                                {{ $r->make?->name ?? 'Any make' }} {{ $r->model?->name ?? '' }}
                            </div>
                            <div class="text-sm text-gray-600 mt-1">
                                @if($r->min_year || $r->max_year) Year: {{ $r->min_year ?? 'Any' }} - {{ $r->max_year ?? 'Any' }} · @endif
                                @if($r->min_budget || $r->max_budget) Budget: {{ $r->min_budget ?? 'Any' }} - {{ $r->max_budget ?? 'Any' }} · @endif
                                {{ $r->location ?? 'No location' }}
                            </div>
                            @if($r->notes)
                                <div class="text-sm text-gray-700 mt-2">{{ $r->notes }}</div>
                            @endif
                        </div>

                        <div class="text-sm">
                            @if($myOffer)
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800 font-medium">
                                    Offer submitted
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <form wire:submit.prevent="submitOffer({{ $r->id }})" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Price</label>
                                <input type="number" wire:model.defer="price" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="e.g. 25000000">
                                @error('price') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Message</label>
                                <input type="text" wire:model.defer="message" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="Short offer message">
                                @error('message') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Image (optional)</label>
                                <input type="file" wire:model="image" class="w-full text-sm">
                                @error('image') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="md:col-span-4 flex justify-end">
                                <button type="submit" class="px-5 py-2 rounded-full bg-green-600 text-white font-semibold hover:bg-green-700 transition-colors">
                                    Submit offer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-600">No open requests right now.</div>
            @endforelse
        </div>
    </div>
</div>


