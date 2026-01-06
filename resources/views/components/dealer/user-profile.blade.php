<!-- User Profile Section -->
<div class="p-4 border-t border-gray-200">
    <div class="flex items-center space-x-3">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=10b981&color=fff" alt="User" class="w-10 h-10 rounded-full flex-shrink-0">
        <div class="flex-1 min-w-0 user-info">
            <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </button>
        </form>
    </div>
</div>

