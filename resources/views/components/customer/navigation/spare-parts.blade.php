<a href="{{ route('spare-parts.sourcing') }}" class="text-sm font-medium {{ request()->routeIs('spare-parts.sourcing') ? 'text-gray-900 font-semibold border-b-2 border-green-600 pb-1' : 'text-gray-700' }} hover:text-green-700 transition-colors">Order Spare Parts</a>
<a href="{{ route('spare-parts.orders') }}" class="text-sm font-medium {{ request()->routeIs('spare-parts.orders') || request()->routeIs('spare-parts.order-detail') ? 'text-gray-900 font-semibold border-b-2 border-green-600 pb-1' : 'text-gray-700' }} hover:text-green-700 transition-colors">See Previous Orders</a>

