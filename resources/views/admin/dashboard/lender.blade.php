@php
    use App\Models\Order;
    use App\Enums\OrderStatus;
    use App\Enums\OrderType;
    
    // Ensure variables are defined
    $loanRequests = $loanRequests ?? 0;
    $loanRequestsPending = $loanRequestsPending ?? 0;
    $loanRequestsApproved = $loanRequestsApproved ?? 0;
    $loanRequestsRejected = $loanRequestsRejected ?? 0;
    $totalCriteria = $totalCriteria ?? 0;
    $activeCriteria = $activeCriteria ?? 0;
    $mostUsedCriteria = $mostUsedCriteria ?? null;
    $mostUsedCriteriaCount = $mostUsedCriteriaCount ?? 0;
    $recentRequests = $recentRequests ?? collect();
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
                <p class="text-sm font-medium text-gray-600">Rejected Requests</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($loanRequestsRejected) }}</p>
                <p class="text-sm text-red-600 mt-1">Not approved</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Loan Request Overview -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Loan Request Overview</h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-semibold text-gray-900">Total Loan Requests</p>
                    <p class="text-sm text-gray-600">All financing applications assigned to you</p>
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
            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                <div>
                    <p class="font-semibold text-gray-900">Rejected Requests</p>
                    <p class="text-sm text-gray-600">Not approved</p>
                </div>
                <span class="text-2xl font-bold text-red-700">{{ number_format($loanRequestsRejected) }}</span>
            </div>
        </div>
    </div>

    <!-- Lending Criteria Summary -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Lending Criteria</h2>
        <div class="space-y-4">
            <div class="p-4 bg-blue-50 rounded-lg">
                <p class="text-sm font-medium text-gray-600">Total Criteria</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalCriteria) }}</p>
            </div>
            <div class="p-4 bg-green-50 rounded-lg">
                <p class="text-sm font-medium text-gray-600">Active Criteria</p>
                <p class="text-2xl font-bold text-green-700 mt-1">{{ number_format($activeCriteria) }}</p>
            </div>
            @if($mostUsedCriteria)
            <div class="p-4 bg-purple-50 rounded-lg">
                <p class="text-sm font-medium text-gray-600">Most Used Criteria</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $mostUsedCriteria->name }}</p>
                <p class="text-xs text-gray-500 mt-1">Used {{ $mostUsedCriteriaCount }} time(s)</p>
            </div>
            @endif
            <a href="{{ route('admin.lending-criteria.index') }}" class="block w-full text-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors text-sm">
                Manage Criteria
            </a>
        </div>
    </div>
</div>

<!-- Recent Requests -->
@if($recentRequests && $recentRequests->count() > 0)
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Recent Financing Requests</h2>
        <a href="{{ route('lender.requests.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">View All →</a>
    </div>
    <div class="space-y-3">
        @foreach($recentRequests as $request)
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
            <div class="flex items-center space-x-4 flex-1">
                @if($request->vehicle)
                <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                    @if($request->vehicle->image_front)
                        <img src="{{ asset('storage/' . $request->vehicle->image_front) }}" alt="Vehicle" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-medium text-gray-900 truncate">
                        {{ $request->vehicle->year ?? '' }} {{ $request->vehicle->make->name ?? '' }} {{ $request->vehicle->model->name ?? '' }}
                    </h3>
                    <p class="text-sm text-gray-500">Requested by {{ $request->user->name ?? 'Unknown' }}</p>
                    <p class="text-xs text-gray-400">{{ $request->created_at->diffForHumans() }}</p>
                </div>
                @else
                <div class="flex-1 min-w-0">
                    <h3 class="font-medium text-gray-900">Vehicle Not Available</h3>
                    <p class="text-sm text-gray-500">Requested by {{ $request->user->name ?? 'Unknown' }}</p>
                    <p class="text-xs text-gray-400">{{ $request->created_at->diffForHumans() }}</p>
                </div>
                @endif
            </div>
            <div class="text-right ml-4">
                @if(isset($request->order_data['loan_amount']))
                <p class="text-lg font-bold text-gray-900">TZS {{ number_format($request->order_data['loan_amount']) }}</p>
                @endif
                @if(isset($request->order_data['loan_term_months']))
                <p class="text-sm text-gray-500">{{ $request->order_data['loan_term_months'] }} months</p>
                @endif
                @php
                    $approvalStatus = $request->order_data['lender_approval'] ?? 'pending';
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'approved' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800'
                    ];
                    $statusColor = $statusColors[$approvalStatus] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }} mt-1">
                    {{ ucfirst($approvalStatus) }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

