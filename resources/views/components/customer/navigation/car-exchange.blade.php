<a href="{{ route('car-exchange.index') }}" class="text-sm font-medium {{ request()->routeIs('car-exchange.*') ? 'text-gray-900 font-semibold border-b-2 border-green-600 pb-1' : 'text-gray-700' }} hover:text-green-700 transition-colors">Request Exchange</a>
<a href="{{ route('car-exchange.my-requests') }}" class="text-sm font-medium {{ request()->routeIs('car-exchange.my-requests') ? 'text-gray-900 font-semibold border-b-2 border-green-600 pb-1' : 'text-gray-700' }} hover:text-green-700 transition-colors">My Requests</a>

