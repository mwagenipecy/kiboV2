{{-- Filter Section Component --}}
@props(['section', 'title', 'subtitle' => null, 'expanded' => false])

<div class="border-b border-gray-200 pb-4 mb-4">
    <button wire:click="toggleSection('{{ $section }}')" class="w-full flex items-center justify-between py-3">
        <div class="flex items-center gap-3">
            @if(isset($icon))
                {{ $icon }}
            @else
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            @endif
            <div class="text-left">
                <div class="font-semibold text-gray-900">{{ $title }}</div>
                @if($subtitle)
                    <div class="text-sm text-gray-600">{{ $subtitle }}</div>
                @endif
            </div>
        </div>
        <svg class="w-5 h-5 text-gray-600 transition-transform {{ $expanded ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    @if($expanded)
        {{ $slot }}
    @endif
</div>

