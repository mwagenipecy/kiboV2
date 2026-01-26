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
                <a href="{{ url('/admin/orders/financing') }}" class="text-xs text-green-700 hover:text-green-800 mt-1 inline-block">View requests →</a>
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


    <div class="lg:col-span-3 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
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

