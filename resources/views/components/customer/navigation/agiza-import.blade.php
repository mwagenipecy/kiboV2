<a href="{{ route('agiza-import.index') }}" class="text-sm font-medium {{ request()->routeIs('agiza-import.index') ? 'text-green-700 font-semibold' : 'text-gray-600' }} hover:text-green-700 transition-colors">Submit Import Request</a>
@auth
<a href="{{ route('agiza-import.requests') }}" class="text-sm font-medium {{ request()->routeIs('agiza-import.requests') ? 'text-green-700 font-semibold' : 'text-gray-600' }} hover:text-green-700 transition-colors">My Import Requests</a>
@endauth
