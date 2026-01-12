<a href="{{ route('cars.used') }}" class="text-sm font-medium {{ request()->routeIs('cars.used') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">{{ __('vehicles.used_cars') }}</a>
<a href="{{ route('cars.new') }}" class="text-sm font-medium {{ request()->routeIs('cars.new') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">{{ __('vehicles.new_cars') }}</a>
<a href="{{ route('cars.sell') }}" class="text-sm font-medium {{ request()->routeIs('cars.sell') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">{{ __('vehicles.sell_your_car') }}</a>
<a href="{{ route('cars.value') }}" class="text-sm font-medium {{ request()->routeIs('cars.value') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">{{ __('vehicles.value_your_car') }}</a>
<a href="{{ route('cars.leasing') }}" class="text-sm font-medium {{ request()->routeIs('cars.leasing') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">{{ __('vehicles.car_leasing') }}</a>
<a href="{{ route('cars.electric') }}" class="text-sm font-medium {{ request()->routeIs('cars.electric') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">{{ __('vehicles.electric_cars') }}</a>
<a href="{{ route('cars.insurance') }}" class="text-sm font-medium {{ request()->routeIs('cars.insurance') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">{{ __('vehicles.car_insurance') }}</a>
{{-- Temporarily hidden --}}
{{-- <a href="{{ route('cars.buy-online') }}" class="text-sm font-medium {{ request()->routeIs('cars.buy-online') ? 'text-gray-900' : 'text-gray-700' }} hover:text-green-700">{{ __('vehicles.buy_car_online') }}</a> --}}

