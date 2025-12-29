<div>
    @if ($show)
        <div 
            x-data="{ show: @entangle('show') }"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true"
            style="display: none;"
        >
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div 
                    @click="$wire.closeModal()"
                    x-show="show"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                ></div>

                <!-- Center modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div 
                    x-show="show"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle 
                    {{ $size === 'sm' ? 'sm:max-w-sm' : '' }}
                    {{ $size === 'md' ? 'sm:max-w-md' : '' }}
                    {{ $size === 'lg' ? 'sm:max-w-lg' : '' }}
                    {{ $size === 'xl' ? 'sm:max-w-xl' : '' }}
                    {{ $size === '2xl' ? 'sm:max-w-2xl' : '' }}
                    {{ $size === 'full' ? 'sm:max-w-4xl' : '' }}
                    sm:w-full"
                >
                    <!-- Modal Header -->
                    @if ($title)
                        <div class="px-6 py-4 bg-white border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                                <button 
                                    @click="$wire.closeModal()"
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none"
                                >
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Modal Content -->
                    <div class="bg-white">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
