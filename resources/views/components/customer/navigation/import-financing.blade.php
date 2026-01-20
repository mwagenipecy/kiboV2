<a href="{{ route('import-financing.index') }}" class="text-sm font-medium {{ request()->routeIs('import-financing.index') ? 'text-green-700 font-semibold' : 'text-gray-600' }} hover:text-green-700 transition-colors">Apply for Financing</a>
@auth
<a href="{{ route('import-financing.requests') }}" class="text-sm font-medium {{ request()->routeIs('import-financing.requests') ? 'text-green-700 font-semibold' : 'text-gray-600' }} hover:text-green-700 transition-colors">My Requests</a>
@endauth
<a href="{{ route('cars.index') }}" class="text-sm font-medium text-gray-600 hover:text-green-700 transition-colors">Browse Cars</a>
<a href="{{ route('loan-calculator.index') }}" class="text-sm font-medium text-gray-600 hover:text-green-700 transition-colors">Loan Calculator</a>

