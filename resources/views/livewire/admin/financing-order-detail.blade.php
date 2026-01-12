<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Financing Application #{{ $order->order_number }}</h1>
                <p class="mt-2 text-gray-600">Review and manage financing application</p>
            </div>
            <a href="{{ route('admin.orders.financing.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Back to List
            </a>
        </div>
    </div>

    @if(session()->has('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session()->has('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Application Status</h2>
                <div class="flex items-center gap-4 mb-4">
                    <span class="px-4 py-2 text-lg font-semibold rounded-lg
                        {{ $order->status->value === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $order->status->value === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $order->status->value === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $order->status->value === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ $order->status->label() }}
                    </span>
                    <span class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</span>
                </div>

                <!-- Approval Workflow Status -->
                <div class="border-t pt-4 mt-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Approval Workflow</h3>
                    <div class="space-y-3">
                        <!-- Dealer Approval -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="font-medium text-gray-900">Dealer Review</span>
                            </div>
                            @php
                                $dealerStatus = $order->order_data['dealer_approval'] ?? 'pending';
                            @endphp
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                {{ $dealerStatus === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $dealerStatus === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $dealerStatus === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($dealerStatus) }}
                            </span>
                        </div>

                        <!-- Lender Approval -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-medium text-gray-900">Lender Review</span>
                            </div>
                            @php
                                $lenderStatus = $order->order_data['lender_approval'] ?? 'pending';
                            @endphp
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                {{ $lenderStatus === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $lenderStatus === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $lenderStatus === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($lenderStatus) }}
                            </span>
                        </div>

                        <!-- Vehicle Status -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                                <span class="font-medium text-gray-900">Vehicle Status</span>
                            </div>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                {{ $order->vehicle->status->value === 'approved' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->vehicle->status->value === 'hold' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $order->vehicle->status->value === 'sold' ? 'bg-purple-100 text-purple-800' : '' }}">
                                {{ $order->vehicle->status->label() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Customer Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium text-gray-900">{{ $order->user->email }}</p>
                    </div>
                    @if($order->user->customer)
                        @if($order->user->customer->phone_number)
                        <div>
                            <p class="text-sm text-gray-600">Phone</p>
                            <p class="font-medium text-gray-900">{{ $order->user->customer->phone_number }}</p>
                        </div>
                        @endif
                        @if($order->user->customer->nida_number)
                        <div>
                            <p class="text-sm text-gray-600">NIDA Number</p>
                            <p class="font-medium text-gray-900">{{ $order->user->customer->nida_number }}</p>
                        </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Vehicle Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Vehicle Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Vehicle</p>
                        <p class="font-medium text-gray-900">{{ $order->vehicle->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Price</p>
                        <p class="font-medium text-gray-900">£{{ number_format($order->vehicle->price, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Year</p>
                        <p class="font-medium text-gray-900">{{ $order->vehicle->year }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Mileage</p>
                        <p class="font-medium text-gray-900">{{ number_format($order->vehicle->mileage) }} km</p>
                    </div>
                </div>
            </div>

            <!-- Lender & Loan Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Financing Details</h2>
                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="font-semibold text-blue-900 mb-2">{{ $order->order_data['lender_name'] ?? 'N/A' }}</h3>
                        <p class="text-sm text-blue-700">{{ $order->order_data['criteria_name'] ?? '' }}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Loan Amount</p>
                            <p class="text-lg font-bold text-gray-900">${{ number_format($order->order_data['loan_amount'] ?? 0, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Down Payment</p>
                            <p class="text-lg font-bold text-gray-900">${{ number_format($order->order_data['down_payment'] ?? 0, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Loan Term</p>
                            <p class="text-lg font-bold text-gray-900">{{ $order->order_data['loan_term_months'] ?? 0 }} months</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Interest Rate</p>
                            <p class="text-lg font-bold text-blue-600">{{ number_format($order->order_data['interest_rate'] ?? 0, 2) }}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Monthly Payment</p>
                            <p class="text-lg font-bold text-green-600">${{ number_format($order->order_data['monthly_payment'] ?? 0, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Processing Fee</p>
                            <p class="text-lg font-bold text-gray-900">${{ number_format($order->order_data['processing_fee'] ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Applicant Financial Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Monthly Income</p>
                        <p class="font-medium text-gray-900">${{ number_format($order->order_data['monthly_income'] ?? 0, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Employment Duration</p>
                        <p class="font-medium text-gray-900">{{ $order->order_data['employment_months'] ?? 0 }} months</p>
                    </div>
                    @if(isset($order->order_data['credit_score']) && $order->order_data['credit_score'])
                    <div>
                        <p class="text-sm text-gray-600">Credit Score</p>
                        <p class="font-medium text-gray-900">{{ $order->order_data['credit_score'] }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Notes -->
            @if($order->customer_notes)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Customer Notes</h2>
                <p class="text-gray-700">{{ $order->customer_notes }}</p>
            </div>
            @endif

            <!-- Admin Notes -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Admin Notes</h2>
                <textarea 
                    wire:model="adminNotes" 
                    rows="4" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    placeholder="Add internal notes about this application..."></textarea>
                <button wire:click="saveNotes" class="mt-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    Save Notes
                </button>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions -->
            @if($order->status->value === 'pending')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Dealer Actions</h3>
                <p class="text-sm text-gray-600 mb-4">Review the application and decide whether to forward it to the lender.</p>
                <div class="space-y-3">
                    <button wire:click="openApproveModal" class="w-full px-4 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Approve & Forward to Lender
                    </button>
                    <button wire:click="openRejectModal" class="w-full px-4 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Reject Application
                    </button>
                </div>
                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs text-blue-800">
                        <strong>Note:</strong> Approving will put the vehicle on HOLD and forward the application to the lender for final review.
                    </p>
                </div>
            </div>
            @elseif($order->status->value === 'approved')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Status</h3>
                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg mb-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Awaiting Lender Review</p>
                            <p class="text-xs text-yellow-700 mt-1">The application has been forwarded to the lender. They will review the vehicle and customer details.</p>
                        </div>
                    </div>
                </div>
                <button wire:click="completeOrder" class="w-full px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Mark as Completed (Manual)
                </button>
            </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>
                <div class="space-y-4">
                    <!-- Application Submitted -->
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-blue-600"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Application Submitted</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                            <p class="text-xs text-gray-600">by {{ $order->user->name }}</p>
                        </div>
                    </div>

                    <!-- Dealer Approval/Rejection -->
                    @if(isset($order->order_data['dealer_approval']) && $order->order_data['dealer_approval'] !== 'pending')
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full {{ $order->order_data['dealer_approval'] === 'rejected' ? 'bg-red-600' : 'bg-green-600' }}"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    Dealer {{ $order->order_data['dealer_approval'] === 'approved' ? 'Approved' : 'Rejected' }}
                                </p>
                                @if(isset($order->order_data['dealer_approved_at']))
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($order->order_data['dealer_approved_at'])->format('M d, Y h:i A') }}</p>
                                @endif
                                @if(isset($order->order_data['dealer_approved_by_name']))
                                <p class="text-xs text-gray-600">by {{ $order->order_data['dealer_approved_by_name'] }}</p>
                                @endif
                                @if($order->order_data['dealer_approval'] === 'approved')
                                <p class="text-xs text-green-600 mt-1">✓ Vehicle placed on hold</p>
                                <p class="text-xs text-green-600">✓ Forwarded to lender</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Lender Approval/Rejection -->
                    @if(isset($order->order_data['lender_approval']) && $order->order_data['lender_approval'] !== 'pending')
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full {{ $order->order_data['lender_approval'] === 'rejected' ? 'bg-red-600' : 'bg-green-600' }}"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    Lender {{ $order->order_data['lender_approval'] === 'approved' ? 'Approved' : 'Rejected' }}
                                </p>
                                @if(isset($order->order_data['lender_approved_at']))
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($order->order_data['lender_approved_at'])->format('M d, Y h:i A') }}</p>
                                @endif
                                @if(isset($order->order_data['lender_approved_by_name']))
                                <p class="text-xs text-gray-600">by {{ $order->order_data['lender_approved_by_name'] }}</p>
                                @endif
                                @if($order->order_data['lender_approval'] === 'approved')
                                <p class="text-xs text-green-600 mt-1">✓ Financing approved</p>
                                <p class="text-xs text-green-600">✓ Vehicle marked as sold</p>
                                @elseif($order->order_data['lender_approval'] === 'rejected')
                                <p class="text-xs text-red-600 mt-1">✗ Vehicle returned to available</p>
                                @endif
                                @if(isset($order->order_data['lender_notes']))
                                <p class="text-xs text-gray-600 mt-1 italic">"{{ $order->order_data['lender_notes'] }}"</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Completed -->
                    @if($order->completed_at)
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-blue-600"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Financing Completed</p>
                            <p class="text-xs text-gray-500">{{ $order->completed_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Modal -->
    @if($showApproveModal)
    <div class="fixed inset-0 bg-black/50 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Approve Financing Application</h3>
            <p class="text-gray-600 mb-4">Are you sure you want to approve this financing application?</p>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-blue-900 font-medium mb-2">This action will:</p>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Put the vehicle on <strong>HOLD</strong></span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Forward application to <strong>{{ $order->order_data['lender_name'] ?? 'the lender' }}</strong> for final review</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Notify the customer of dealer approval</span>
                    </li>
                </ul>
            </div>
            <div class="flex gap-3 justify-end">
                <button wire:click="closeApproveModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button wire:click="approveOrder" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Approve & Forward
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Rejection Modal -->
    @if($showRejectModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Reject Financing Application</h3>
            <p class="text-gray-600 mb-3">Please provide a reason for rejecting this application:</p>
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                <p class="text-xs text-red-800">The vehicle status will remain <strong>unchanged</strong> and the customer will be notified of the rejection.</p>
            </div>
            <textarea wire:model="rejectionReason" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent mb-4" placeholder="e.g., Customer does not meet credit requirements, incomplete documentation, etc."></textarea>
            @error('rejectionReason') <p class="text-red-500 text-sm mb-4">{{ $message }}</p> @enderror
            <div class="flex gap-3 justify-end">
                <button wire:click="closeRejectModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button wire:click="rejectOrder" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Reject Application
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
