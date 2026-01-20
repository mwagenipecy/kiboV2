<a href="{{ route('garage.index') }}" class="text-sm font-medium {{ request()->routeIs('garage.index') ? 'text-gray-900 font-semibold border-b-2 border-green-600 pb-1' : 'text-gray-700' }} hover:text-green-700 transition-colors">Find a Garage</a>
<a href="{{ route('garage.services') }}" class="text-sm font-medium {{ request()->routeIs('garage.services') ? 'text-gray-900 font-semibold border-b-2 border-green-600 pb-1' : 'text-gray-700' }} hover:text-green-700 transition-colors">Services</a>

