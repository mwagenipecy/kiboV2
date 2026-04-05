@php
    use App\Support\VehicleSpecificationCatalog;
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-8">
    <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-1">Equipment &amp; convenience</h3>
        <p class="text-sm text-gray-600 mb-4">Tick everything that applies. Buyers see the full checklist; items you select show with a tick.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-72 overflow-y-auto pr-1 border border-gray-100 rounded-lg p-3">
            @foreach (VehicleSpecificationCatalog::comfort() as $label)
                <label class="flex items-start gap-2 cursor-pointer rounded-md p-2 hover:bg-gray-50" wire:key="comfort-{{ $loop->index }}">
                    <input type="checkbox" wire:model.live="features" value="{{ $label }}" class="mt-0.5 rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <span class="text-sm text-gray-800 leading-snug">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        @error('features')
            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-1">Safety &amp; driver assist</h3>
        <p class="text-sm text-gray-600 mb-4">Same checklist style on the public listing.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-72 overflow-y-auto pr-1 border border-gray-100 rounded-lg p-3">
            @foreach (VehicleSpecificationCatalog::safety() as $label)
                <label class="flex items-start gap-2 cursor-pointer rounded-md p-2 hover:bg-gray-50" wire:key="safety-{{ $loop->index }}">
                    <input type="checkbox" wire:model.live="safety_features" value="{{ $label }}" class="mt-0.5 rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <span class="text-sm text-gray-800 leading-snug">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        @error('safety_features')
            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
        @enderror
    </div>
</div>
