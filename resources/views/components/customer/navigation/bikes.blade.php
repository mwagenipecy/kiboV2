<a href="{{ route('bikes.used') }}" class="text-sm font-medium {{ request()->routeIs('bikes.used') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">Used bikes</a>
<a href="{{ route('bikes.new') }}" class="text-sm font-medium {{ request()->routeIs('bikes.new') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">New bikes</a>
<a href="{{ route('bikes.sell') }}" class="text-sm font-medium {{ request()->routeIs('bikes.sell') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">Sell your bike</a>
<a href="{{ route('bikes.value') }}" class="text-sm font-medium {{ request()->routeIs('bikes.value') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">Value your bike</a>
<a href="{{ route('bikes.reviews') }}" class="text-sm font-medium {{ request()->routeIs('bikes.reviews') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">Bike reviews</a>
<a href="{{ route('bikes.insurance') }}" class="text-sm font-medium {{ request()->routeIs('bikes.insurance') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">Bike insurance</a>
<a href="{{ route('bikes.guides') }}" class="text-sm font-medium {{ request()->routeIs('bikes.guides') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">Buying guides</a>
<a href="{{ route('bikes.electric') }}" class="text-sm font-medium {{ request()->routeIs('bikes.electric') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">Electric bikes</a>

