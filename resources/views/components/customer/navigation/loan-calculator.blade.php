<a href="{{ route('loan-calculator.index') }}" class="text-sm font-medium {{ request()->routeIs('loan-calculator.*') ? 'text-green-700 font-semibold' : 'text-gray-600' }} hover:text-green-700 transition-colors">Loan Calculator</a>
<a href="{{ route('cars.index') }}" class="text-sm font-medium text-gray-600 hover:text-green-700 transition-colors">Browse Cars</a>
<a href="{{ route('trucks.index') }}" class="text-sm font-medium text-gray-600 hover:text-green-700 transition-colors">Browse Trucks</a>

