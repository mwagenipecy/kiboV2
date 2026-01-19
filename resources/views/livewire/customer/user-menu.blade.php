<div class="relative user-menu-dropdown" x-data="{ open: false }" @click.away="open = false">
    @auth
        <!-- User Menu Button -->
        <button 
            @click="open = !open"
            type="button"
            class="flex items-center space-x-2 text-gray-700 hover:text-green-700 transition-colors focus:outline-none"
        >
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-700 font-semibold text-sm">
                {{ $this->userInitials }}
            </div>
            <div class="hidden md:flex flex-col items-start">
                <span class="text-sm font-medium">{{ $this->user->name }}</span>
                <span class="text-xs text-gray-500">{{ $this->user->email }}</span>
            </div>
            <svg 
                class="w-4 h-4 transition-transform duration-200"
                :class="open ? 'rotate-180' : ''"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div 
            x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
            @click.stop
        >
            <div class="py-1">
                <!-- User Info -->
                <div class="px-4 py-3 border-b border-gray-200">
                    <p class="text-sm font-semibold text-gray-900">{{ $this->user->name }}</p>
                    <p class="text-sm text-gray-500 truncate">{{ $this->user->email }}</p>
                </div>

                <!-- Menu Items -->
                <a 
                    href="{{ route('my-adverts') }}" 
                    @click="open = false"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors"
                >
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    My Adverts
                </a>

                <a 
                    href="{{ route('my-orders') }}" 
                    @click="open = false"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors"
                >
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    My Orders
                </a>

                <a 
                    href="{{ route('my-car-requests') }}" 
                    @click="open = false"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors"
                >
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a2 2 0 012-2h8m-6-4h6m-9 8h9a2 2 0 002-2V7a2 2 0 00-2-2h-9a2 2 0 00-2 2v10a2 2 0 002 2zM7 7H5a2 2 0 00-2 2v10a2 2 0 002 2h9" />
                    </svg>
                    My Car Requests
                </a>

                <a 
                    href="{{ route('my-auctions') }}" 
                    @click="open = false"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors"
                >
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    My Auctions
                </a>

                <a 
                    href="{{ route('profile.edit') }}" 
                    @click="open = false"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors"
                >
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile Settings
                </a>

                <a 
                    href="{{ route('user-password.edit') }}" 
                    @click="open = false"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors"
                >
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Change Password
                </a>

                <div class="border-t border-gray-200 my-1"></div>

                <!-- Logout Button -->
                <button 
                    wire:click="logout"
                    @click="open = false"
                    class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                >
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </div>
        </div>
    @else
        <!-- Sign In Button (when not authenticated) -->
        <button 
            id="openAuthModal" 
            class="flex flex-col items-center text-gray-700 hover:text-green-700 transition-colors"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-xs mt-1">{{ __('auth.sign_in') }}</span>
        </button>
    @endauth
</div>

