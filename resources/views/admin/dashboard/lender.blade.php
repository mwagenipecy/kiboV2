@php
    use App\Models\Order;
    use App\Enums\OrderStatus;
    use App\Enums\OrderType;
    
    // Ensure variables are defined
    $loanRequests = $loanRequests ?? 0;
    $loanRequestsPending = $loanRequestsPending ?? 0;
    $loanRequestsApproved = $loanRequestsApproved ?? 0;
@endphp

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Lender Dashboard</h1>
    <p class="mt-2 text-sm text-gray-600">Manage financing requests and track loan portfolio.</p>
    <p class="mt-1 text-xs text-gray-500">Focused on financing and loan management.</p>
</div>

<!-- Top Metrics -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Loan Requests</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($loanRequests) }}</p>
                <p class="text-sm text-gray-500 mt-1">All financing orders</p>
                <a href="{{ route('lender.requests.index') }}" class="text-xs text-green-700 hover:text-green-800 mt-1 inline-block">View requests →</a>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Pending Requests</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($loanRequestsPending) }}</p>
                <p class="text-sm text-yellow-600 mt-1">Awaiting review</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('lender.requests.pending') }}" class="text-sm text-green-600 hover:text-green-700 mt-3 inline-block">Review pending →</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Approved Loans</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($loanRequestsApproved) }}</p>
                <p class="text-sm text-green-600 mt-1">Active loans</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('lender.requests.approved') }}" class="text-sm text-green-600 hover:text-green-700 mt-3 inline-block">View approved →</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Orders</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalOrders) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ number_format($pendingOrders) }} pending</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="space-y-3">
            <a href="{{ route('lender.requests.pending') }}" class="w-full flex items-center px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Review Pending Requests
            </a>
            <a href="{{ route('lender.loans') }}" class="w-full flex items-center px-4 py-3 border-2 border-green-500 text-green-600 rounded-lg hover:bg-green-50 transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                View Active Loans
            </a>
            <a href="{{ route('lender.portfolio') }}" class="w-full flex items-center px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                View Portfolio
            </a>
        </div>
    </div>

    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Loan Request Overview</h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-semibold text-gray-900">Total Loan Requests</p>
                    <p class="text-sm text-gray-600">All financing applications</p>
                </div>
                <span class="text-2xl font-bold text-gray-900">{{ number_format($loanRequests) }}</span>
            </div>
            <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                <div>
                    <p class="font-semibold text-gray-900">Pending Review</p>
                    <p class="text-sm text-gray-600">Require your attention</p>
                </div>
                <span class="text-2xl font-bold text-yellow-700">{{ number_format($loanRequestsPending) }}</span>
            </div>
            <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                <div>
                    <p class="font-semibold text-gray-900">Approved Loans</p>
                    <p class="text-sm text-gray-600">Active financing</p>
                </div>
                <span class="text-2xl font-bold text-green-700">{{ number_format($loanRequestsApproved) }}</span>
            </div>
        </div>
    </div>
</div>

