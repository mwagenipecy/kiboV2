{{--
    Full specification checklist: every catalog item is shown; tick = vehicle has it.
    Expects: $vehicle (App\Models\Vehicle)
    Optional: $variant — pass 'customer' to use kibo-text for ticks on the public site.
--}}
@php
    use App\Support\VehicleSpecificationCatalog;
    $tickClass = ($variant ?? '') === 'customer' ? 'kibo-text' : 'text-green-600';
    $comfortCatalog = VehicleSpecificationCatalog::comfort();
    $safetyCatalog = VehicleSpecificationCatalog::safety();
    $comfortExtras = VehicleSpecificationCatalog::extrasNotInCatalog($vehicle->features ?? [], $comfortCatalog);
    $safetyExtras = VehicleSpecificationCatalog::extrasNotInCatalog($vehicle->safety_features ?? [], $safetyCatalog);
@endphp

<div class="space-y-8">
    <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Equipment &amp; convenience</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            @foreach ($comfortCatalog as $label)
                @php $has = VehicleSpecificationCatalog::hasLabel($vehicle->features ?? [], $label); @endphp
                <div class="flex items-center gap-2 py-1.5 {{ $has ? '' : 'opacity-55' }}">
                    @if ($has)
                        <svg class="w-5 h-5 {{ $tickClass }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    @endif
                    <span class="text-sm {{ $has ? 'text-gray-900 font-medium' : 'text-gray-500' }}">{{ $label }}</span>
                </div>
            @endforeach
        </div>
        @if (count($comfortExtras) > 0)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Also equipped</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($comfortExtras as $extra)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-50 text-green-800 text-xs font-medium">
                            <svg class="w-3.5 h-3.5 {{ $tickClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ $extra }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Safety &amp; driver assist</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            @foreach ($safetyCatalog as $label)
                @php $has = VehicleSpecificationCatalog::hasLabel($vehicle->safety_features ?? [], $label); @endphp
                <div class="flex items-center gap-2 py-1.5 {{ $has ? '' : 'opacity-55' }}">
                    @if ($has)
                        <svg class="w-5 h-5 {{ $tickClass }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    @endif
                    <span class="text-sm {{ $has ? 'text-gray-900 font-medium' : 'text-gray-500' }}">{{ $label }}</span>
                </div>
            @endforeach
        </div>
        @if (count($safetyExtras) > 0)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Also listed</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($safetyExtras as $extra)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-50 text-green-800 text-xs font-medium">
                            <svg class="w-3.5 h-3.5 {{ $tickClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ $extra }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
