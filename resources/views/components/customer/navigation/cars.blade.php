<a href="{{ route('cars.used') }}" class="text-sm font-medium {{ request()->routeIs('cars.used') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">Used cars</a>
<a href="{{ route('cars.new') }}" class="text-sm font-medium {{ request()->routeIs('cars.new') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">New cars</a>
<a href="{{ route('cars.sell') }}" class="text-sm font-medium {{ request()->routeIs('cars.sell') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">Sell your car</a>
<a href="{{ route('cars.value') }}" class="text-sm font-medium {{ request()->routeIs('cars.value') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">Value your car</a>
<a href="{{ route('cars.reviews') }}" class="text-sm font-medium {{ request()->routeIs('cars.reviews') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">Car reviews</a>
<a href="{{ route('cars.leasing') }}" class="text-sm font-medium {{ request()->routeIs('cars.leasing') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">Car leasing</a>
<a href="{{ route('cars.electric') }}" class="text-sm font-medium {{ request()->routeIs('cars.electric') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">Electric cars</a>
<a href="{{ route('cars.buy-online') }}" class="text-sm font-medium {{ request()->routeIs('cars.buy-online') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">Buy a car online</a>

