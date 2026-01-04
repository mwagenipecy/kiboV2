@props([
    'class' => '',
])

@php
    $currentLocale = app()->getLocale();
    $availableLocales = config('app.available_locales', ['en', 'sw']);
    $languageNames = [
        'en' => __('common.english'),
        'sw' => __('common.swahili'),
    ];
    $uniqueId = 'langSwitcher' . uniqid();
@endphp

<div class="relative inline-block {{ $class }}" id="{{ $uniqueId }}">
    <button 
        type="button"
        onclick="document.getElementById('{{ $uniqueId }}Menu').classList.toggle('hidden')"
        class="flex items-center gap-1 px-2 py-1 text-sm font-medium text-gray-700 hover:text-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 rounded transition-colors"
        aria-label="{{ __('common.select_language') }}"
        aria-expanded="false"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
        </svg>
        <span class="text-xs">{{ strtoupper($currentLocale) }}</span>
    </button>

    <div 
        id="{{ $uniqueId }}Menu"
        class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50 hidden"
    >
        <div class="py-1">
            @foreach($availableLocales as $locale)
                <a 
                    href="{{ route('language.switch', $locale) }}"
                    class="block px-4 py-2 text-sm {{ $locale === $currentLocale ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }} transition-colors"
                >
                    <div class="flex items-center justify-between">
                        <span>{{ $languageNames[$locale] ?? $locale }}</span>
                        @if($locale === $currentLocale)
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        // Close language menu when clicking outside
        document.addEventListener('click', function(event) {
            const switchers = document.querySelectorAll('[id^="langSwitcher"]');
            switchers.forEach(function(switcher) {
                const menuId = switcher.id + 'Menu';
                const menu = document.getElementById(menuId);
                
                if (menu && !switcher.contains(event.target)) {
                    menu.classList.add('hidden');
                }
            });
        });
    });
})();
</script>

