<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Leasing Application #{{ $order->order_number }}</h1>
                <p class="mt-2 text-gray-600">Review and manage leasing application</p>
            </div>
            <a href="{{ route('admin.orders.leasing.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
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

    @php
        $orderData = $order->order_data ?? [];
        $approvalStatus = $orderData['approval_status'] ?? 'pending';
        $quotationSent = $orderData['quotation_sent'] ?? false;
        $paymentReceived = $orderData['payment_received'] ?? false;
        $contractIssued = $orderData['contract_issued'] ?? false;
        $leaseStarted = $orderData['lease_started'] ?? false;
        $leaseTerminated = $orderData['lease_terminated'] ?? false;
        $returnRequested = $orderData['return_requested'] ?? false;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Application Status</h2>
                <div class="flex items-center gap-4 mb-4">
                    @php
                        if ($leaseTerminated) {
                            $statusClass = 'bg-gray-100 text-gray-800';
                            $statusText = 'Terminated';
                        } elseif ($leaseStarted) {
                            $statusClass = 'bg-green-100 text-green-800';
                            $statusText = 'Active Lease';
                        } elseif ($contractIssued) {
                            $statusClass = 'bg-blue-100 text-blue-800';
                            $statusText = 'Contract Issued';
                        } elseif ($paymentReceived) {
                            $statusClass = 'bg-purple-100 text-purple-800';
                            $statusText = 'Payment Received';
                        } elseif ($quotationSent) {
                            $statusClass = 'bg-yellow-100 text-yellow-800';
                            $statusText = 'Quotation Sent';
                        } elseif ($approvalStatus === 'approved') {
                            $statusClass = 'bg-blue-100 text-blue-800';
                            $statusText = 'Approved';
                        } elseif ($order->status->value === 'rejected') {
                            $statusClass = 'bg-red-100 text-red-800';
                            $statusText = 'Rejected';
                        } else {
                            $statusClass = 'bg-yellow-100 text-yellow-800';
                            $statusText = 'Pending';
                        }
                    @endphp
                    <span class="px-4 py-2 text-lg font-semibold rounded-lg {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                    <span class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</span>
                </div>

                <!-- Workflow Progress -->
                <div class="border-t pt-4 mt-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Workflow Progress</h3>
                    <div class="space-y-3">
                        <!-- Approval -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 {{ $approvalStatus === 'approved' ? 'text-green-600' : ($approvalStatus === 'rejected' ? 'text-red-600' : 'text-gray-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-medium text-gray-900">Application Approval</span>
                            </div>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                {{ $approvalStatus === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $approvalStatus === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $approvalStatus === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($approvalStatus) }}
                            </span>
                        </div>

                        <!-- Quotation -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 {{ $quotationSent ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="font-medium text-gray-900">Quotation Sent</span>
                            </div>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $quotationSent ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $quotationSent ? 'Yes' : 'Pending' }}
                            </span>
                        </div>

                        <!-- Payment -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 {{ $paymentReceived ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="font-medium text-gray-900">Payment Received</span>
                            </div>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $paymentReceived ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $paymentReceived ? 'Yes' : 'Pending' }}
                            </span>
                        </div>

                        <!-- Contract -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 {{ $contractIssued ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="font-medium text-gray-900">Contract Issued</span>
                            </div>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $contractIssued ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $contractIssued ? 'Yes' : 'Pending' }}
                            </span>
                        </div>

                        <!-- Lease Status -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 {{ $leaseStarted ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-medium text-gray-900">Lease Started</span>
                            </div>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $leaseStarted ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $leaseStarted ? 'Active' : 'Not Started' }}
                            </span>
                        </div>

                        <!-- Lease Car Status -->
                        @if(isset($lease) && $lease)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="font-medium text-gray-900">Lease Vehicle Status</span>
                            </div>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                {{ $lease->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $lease->status === 'reserved' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $lease->status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst($lease->status) }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Applicant Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Applicant Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Full Name</p>
                        <p class="font-medium text-gray-900">{{ $orderData['full_name'] ?? $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium text-gray-900">{{ $orderData['email'] ?? $order->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="font-medium text-gray-900">{{ $orderData['phone'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Date of Birth</p>
                        <p class="font-medium text-gray-900">{{ $orderData['date_of_birth'] ?? 'N/A' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">Address</p>
                        <p class="font-medium text-gray-900">{{ $orderData['address'] ?? 'N/A' }}, {{ $orderData['city'] ?? '' }} {{ $orderData['postal_code'] ?? '' }}</p>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Financial Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Monthly Income</p>
                        <p class="font-medium text-gray-900">${{ number_format($orderData['monthly_income'] ?? 0, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Employment Status</p>
                        <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $orderData['employment_status'] ?? 'N/A')) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Employer Name</p>
                        <p class="font-medium text-gray-900">{{ $orderData['employer_name'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Employment Duration</p>
                        <p class="font-medium text-gray-900">{{ $orderData['employment_months'] ?? 0 }} months</p>
                    </div>
                    @if(isset($orderData['credit_score']) && $orderData['credit_score'])
                    <div>
                        <p class="text-sm text-gray-600">Credit Score</p>
                        <p class="font-medium text-gray-900">{{ $orderData['credit_score'] }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Lease Vehicle Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Lease Vehicle Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Vehicle</p>
                        <p class="font-medium text-gray-900">{{ $orderData['vehicle_title'] ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $orderData['vehicle_make'] ?? '' }} {{ $orderData['vehicle_model'] ?? '' }} {{ $orderData['vehicle_year'] ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Lease Title</p>
                        <p class="font-medium text-gray-900">{{ $orderData['lease_title'] ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Lease Terms -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Lease Terms</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Monthly Payment</p>
                        <p class="text-lg font-bold text-gray-900">${{ number_format($orderData['monthly_payment'] ?? 0, 0) }}/mo</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Lease Term</p>
                        <p class="text-lg font-bold text-gray-900">{{ $orderData['lease_term_months'] ?? 0 }} months</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Down Payment</p>
                        <p class="text-lg font-bold text-gray-900">${{ number_format($orderData['down_payment'] ?? 0, 0) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Security Deposit</p>
                        <p class="text-lg font-bold text-gray-900">${{ number_format($orderData['security_deposit'] ?? 0, 0) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Acquisition Fee</p>
                        <p class="text-lg font-bold text-gray-900">${{ number_format($orderData['acquisition_fee'] ?? 0, 0) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Upfront Cost</p>
                        <p class="text-lg font-bold text-green-600">${{ number_format($orderData['total_upfront_cost'] ?? 0, 0) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Mileage Limit/Year</p>
                        <p class="text-lg font-bold text-gray-900">{{ number_format($orderData['mileage_limit_per_year'] ?? 0, 0) }} km</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Excess Mileage Charge</p>
                        <p class="text-lg font-bold text-gray-900">${{ number_format($orderData['excess_mileage_charge'] ?? 0, 2) }}/km</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Lease Cost</p>
                        <p class="text-lg font-bold text-gray-900">${{ number_format($orderData['total_lease_cost'] ?? 0, 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            @if(isset($orderData['documents']) && is_array($orderData['documents']) && count($orderData['documents']) > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Application Documents</h2>
                <div class="space-y-3">
                    @if(isset($orderData['documents']['id_document']))
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="font-medium text-gray-900">ID Document</span>
                        </div>
                        <button wire:click="downloadDocument('id_document')" class="px-3 py-1 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Download
                        </button>
                    </div>
                    @endif
                    @if(isset($orderData['documents']['proof_of_income']))
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="font-medium text-gray-900">Proof of Income</span>
                        </div>
                        <button wire:click="downloadDocument('proof_of_income')" class="px-3 py-1 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Download
                        </button>
                    </div>
                    @endif
                    @if(isset($orderData['documents']['proof_of_address']))
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="font-medium text-gray-900">Proof of Address</span>
                        </div>
                        <button wire:click="downloadDocument('proof_of_address')" class="px-3 py-1 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Download
                        </button>
                    </div>
                    @endif
                    @if(isset($orderData['documents']['driving_license']))
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="font-medium text-gray-900">Driving License</span>
                        </div>
                        <button wire:click="downloadDocument('driving_license')" class="px-3 py-1 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Download
                        </button>
                    </div>
                    @endif
                    @if(isset($orderData['documents']['additional_documents']) && is_array($orderData['documents']['additional_documents']))
                        @foreach($orderData['documents']['additional_documents'] as $index => $doc)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="font-medium text-gray-900">Additional Document {{ $index + 1 }}</span>
                            </div>
                            <a href="{{ asset('storage/' . $doc) }}" target="_blank" class="px-3 py-1 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                                View
                            </a>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @endif

            <!-- Payment Tracking (if lease started) -->
            @if($leaseStarted)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment & Mileage Tracking</h2>
                
                <!-- Monthly Payments -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Monthly Payments</h3>
                    @php
                        $paymentsMade = $orderData['payments_made'] ?? [];
                        $totalPayments = $orderData['total_payments_made'] ?? 0;
                        $monthlyPayment = $orderData['monthly_payment'] ?? 0;
                        $leaseTermMonths = $orderData['lease_term_months'] ?? 36;
                        $expectedTotal = $monthlyPayment * $leaseTermMonths;
                        $paymentsRemaining = $orderData['payments_remaining'] ?? $expectedTotal;
                    @endphp
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <p class="text-sm text-gray-600">Total Paid</p>
                                <p class="text-2xl font-bold text-green-600">${{ number_format($totalPayments, 0) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Expected Total</p>
                                <p class="text-2xl font-bold text-gray-900">${{ number_format($expectedTotal, 0) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Remaining</p>
                                <p class="text-2xl font-bold {{ $paymentsRemaining > 0 ? 'text-red-600' : 'text-green-600' }}">${{ number_format($paymentsRemaining, 0) }}</p>
                            </div>
                        </div>
                    </div>
                    @if(count($paymentsMade) > 0)
                    <div class="space-y-2">
                        @foreach($paymentsMade as $payment)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">${{ number_format($payment['amount'], 2) }}</p>
                                <p class="text-xs text-gray-500">{{ $payment['date'] ?? 'N/A' }} • {{ ucfirst($payment['method'] ?? 'N/A') }}</p>
                            </div>
                            @if(isset($payment['reference']))
                            <p class="text-xs text-gray-500">Ref: {{ $payment['reference'] }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-4">No payments recorded yet.</p>
                    @endif
                    <button wire:click="openPaymentModal" class="mt-4 w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Record Monthly Payment
                    </button>
                </div>

                <!-- Mileage Tracking -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Mileage Tracking</h3>
                    @php
                        $currentMileage = $orderData['current_mileage'] ?? 0;
                        $mileageLimitPerYear = $orderData['mileage_limit_per_year'] ?? 15000;
                        $leaseTermMonths = $orderData['lease_term_months'] ?? 36;
                        $allowedMileage = ($mileageLimitPerYear * $leaseTermMonths) / 12;
                        $excessMileage = $orderData['excess_mileage'] ?? 0;
                        $excessMileageFee = $orderData['excess_mileage_fee'] ?? 0;
                    @endphp
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Current Mileage</p>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($currentMileage, 0) }} km</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Allowed Mileage</p>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($allowedMileage, 0) }} km</p>
                            </div>
                            @if($excessMileage > 0)
                            <div class="col-span-2">
                                <p class="text-sm text-red-600">Excess Mileage</p>
                                <p class="text-xl font-bold text-red-600">{{ number_format($excessMileage, 0) }} km</p>
                                <p class="text-sm text-red-600">Fee: ${{ number_format($excessMileageFee, 2) }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <input type="number" wire:model="currentMileage" placeholder="Enter current mileage" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <button wire:click="updateMileage" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Update Mileage
                        </button>
                    </div>
                </div>
            </div>
            @endif

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

        <!-- Sidebar - Actions -->
        <div class="space-y-6">
            <!-- Pending: Approve/Reject -->
            @if($order->status->value === 'pending')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Actions</h3>
                <p class="text-sm text-gray-600 mb-4">Review the application and documents before making a decision.</p>
                <div class="space-y-3">
                    <button wire:click="openApproveModal" class="w-full px-4 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Approve Application
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
                        <strong>Note:</strong> Approving will mark the lease vehicle as reserved and allow you to proceed with quotation.
                    </p>
                </div>
            </div>

            <!-- Approved: Send Quotation -->
            @elseif($approvalStatus === 'approved' && !$quotationSent)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Next Steps</h3>
                <button wire:click="openQuotationModal" class="w-full px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Send Quotation
                </button>
            </div>

            <!-- Quotation Sent: Record Payment -->
            @elseif($quotationSent && !$paymentReceived)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Status</h3>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-yellow-800">Quotation sent. Waiting for payment.</p>
                    <p class="text-xs text-yellow-700 mt-1">Quotation Amount: <strong>${{ number_format($orderData['quotation_amount'] ?? $orderData['total_upfront_cost'] ?? 0, 2) }}</strong></p>
                </div>
                <button wire:click="openPaymentModal" class="w-full px-4 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Record Payment
                </button>
            </div>

            <!-- Payment Received: Issue Contract -->
            @elseif($paymentReceived && !$contractIssued)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Received</h3>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-green-800">Payment confirmed. Total paid: <strong>${{ number_format($orderData['total_paid'] ?? 0, 2) }}</strong></p>
                </div>
                <button wire:click="openContractModal" class="w-full px-4 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Issue Contract
                </button>
            </div>

            <!-- Contract Issued: Start Lease -->
            @elseif($contractIssued && !$leaseStarted)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Contract Issued</h3>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-purple-800">Contract issued on <strong>{{ $orderData['contract_issued_at'] ?? 'N/A' }}</strong></p>
                </div>
                <button wire:click="openStartLeaseModal" class="w-full px-4 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Start Lease
                </button>
            </div>

            <!-- Active Lease: Monitoring & Return -->
            @elseif($leaseStarted && !$leaseTerminated)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Active Lease</h3>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-green-800">Lease started on <strong>{{ $orderData['lease_started_at'] ?? 'N/A' }}</strong></p>
                    <p class="text-xs text-green-700 mt-1">End date: <strong>{{ $orderData['lease_end_date'] ?? 'N/A' }}</strong></p>
                </div>
                <div class="space-y-3">
                    <button wire:click="openPaymentModal" class="w-full px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700">
                        Record Monthly Payment
                    </button>
                    <button wire:click="openReturnModal" class="w-full px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Process Return Request
                    </button>
                </div>
            </div>

            <!-- Terminated/Completed -->
            @elseif($leaseTerminated || $order->status->value === 'completed')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Lease Terminated</h3>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <p class="text-sm text-gray-800">Lease has been terminated on <strong>{{ $orderData['lease_terminated_at'] ?? 'N/A' }}</strong></p>
                    @if(isset($orderData['return_reason']))
                    <p class="text-xs text-gray-600 mt-2">Reason: {{ $orderData['return_reason'] }}</p>
                    @endif
                    @php
                        $completedExcessFee = $orderData['excess_mileage_fee'] ?? 0;
                    @endphp
                    @if($completedExcessFee > 0)
                    <p class="text-sm text-red-600 mt-2">Excess Mileage Fee: <strong>${{ number_format($completedExcessFee, 2) }}</strong></p>
                    @endif
                </div>
            </div>
            @endif

            <!-- Order Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Order Number</span>
                        <span class="font-medium text-gray-900">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Submitted</span>
                        <span class="font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($order->processed_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Processed</span>
                        <span class="font-medium text-gray-900">{{ $order->processed_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if(isset($orderData['entity_name']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dealer/Entity</span>
                        <span class="font-medium text-gray-900">{{ $orderData['entity_name'] }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>
                <div class="space-y-4">
                    <!-- Application Submitted -->
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-green-600"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Application Submitted</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                            <p class="text-xs text-gray-600">by {{ $orderData['full_name'] ?? $order->user->name }}</p>
                        </div>
                    </div>

                    <!-- Approval -->
                    @if(isset($orderData['approved_at']))
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-green-600"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Application Approved</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($orderData['approved_at'])->format('M d, Y h:i A') }}</p>
                            @if(isset($orderData['approved_by_name']))
                            <p class="text-xs text-gray-600">by {{ $orderData['approved_by_name'] }}</p>
                            @endif
                            @if(isset($lease))
                            <p class="text-xs text-green-600 mt-1">✓ Vehicle marked as reserved</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Quotation -->
                    @if(isset($orderData['quotation_sent_at']))
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-blue-600"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Quotation Sent</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($orderData['quotation_sent_at'])->format('M d, Y h:i A') }}</p>
                            <p class="text-xs text-gray-600">Amount: ${{ number_format($orderData['quotation_amount'] ?? 0, 2) }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Payment -->
                    @if(isset($orderData['payment_completed_at']))
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-purple-600"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Payment Received</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($orderData['payment_completed_at'])->format('M d, Y h:i A') }}</p>
                            <p class="text-xs text-gray-600">Total: ${{ number_format($orderData['total_paid'] ?? 0, 2) }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Contract -->
                    @if(isset($orderData['contract_issued_at']))
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-purple-600"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Contract Issued</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($orderData['contract_issued_at'])->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Lease Started -->
                    @if(isset($orderData['lease_started_at']))
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-green-600"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Lease Started</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($orderData['lease_started_at'])->format('M d, Y') }}</p>
                            @if(isset($orderData['lease_end_date']))
                            <p class="text-xs text-gray-600">End date: {{ \Carbon\Carbon::parse($orderData['lease_end_date'])->format('M d, Y') }}</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Return/Termination -->
                    @if(isset($orderData['lease_terminated_at']))
                    @php
                        $terminatedExcessFee = $orderData['excess_mileage_fee'] ?? 0;
                    @endphp
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-red-600"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Lease Terminated</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($orderData['lease_terminated_at'])->format('M d, Y') }}</p>
                            @if($terminatedExcessFee > 0)
                            <p class="text-xs text-red-600 mt-1">Excess mileage fee: ${{ number_format($terminatedExcessFee, 2) }}</p>
                            @endif
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
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Approve Leasing Application</h3>
            <p class="text-gray-600 mb-3">Are you sure you want to approve this leasing application?</p>
            <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                <p class="text-xs text-green-800">
                    <strong>Note:</strong> Approving will mark the lease vehicle as <strong>reserved</strong> and allow you to send a quotation to the customer.
                </p>
            </div>
            <div class="flex gap-3 justify-end">
                <button wire:click="closeApproveModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button wire:click="approveOrder" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Approve Application
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Rejection Modal -->
    @if($showRejectModal)
    <div class="fixed inset-0 bg-black/50 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Reject Leasing Application</h3>
            <p class="text-gray-600 mb-3">Please provide a reason for rejecting this application:</p>
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                <p class="text-xs text-red-800">The customer will be notified and the lease vehicle status will remain unchanged.</p>
            </div>
            <textarea wire:model="rejectionReason" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent mb-4" placeholder="e.g., Customer does not meet eligibility requirements, incomplete documentation, etc."></textarea>
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

    <!-- Quotation Modal -->
    @if($showQuotationModal)
    <div class="fixed inset-0 bg-black/50 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Send Quotation</h3>
            <p class="text-gray-600 mb-4">Enter the quotation amount to be sent to the customer:</p>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Quotation Amount *</label>
                <input type="number" step="0.01" wire:model="quotationAmount" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('quotationAmount') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">Recommended: ${{ number_format($orderData['total_upfront_cost'] ?? 0, 2) }}</p>
            </div>
            <div class="flex gap-3 justify-end">
                <button wire:click="closeQuotationModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button wire:click="sendQuotation" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Send Quotation
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Payment Modal -->
    @if($showPaymentModal)
    <div class="fixed inset-0 bg-black/50 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $leaseStarted ? 'Record Monthly Payment' : 'Record Payment' }}</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Amount *</label>
                    <input type="number" step="0.01" wire:model="paymentAmount" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('paymentAmount') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Date *</label>
                    <input type="date" wire:model="paymentDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('paymentDate') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                    <select wire:model="paymentMethod" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="check">Check</option>
                        <option value="card">Card</option>
                        <option value="other">Other</option>
                    </select>
                    @error('paymentMethod') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reference Number (Optional)</label>
                    <input type="text" wire:model="paymentReference" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Transaction/Check number">
                    @error('paymentReference') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex gap-3 justify-end mt-6">
                <button wire:click="closePaymentModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button wire:click="{{ $leaseStarted ? 'recordMonthlyPayment' : 'recordPayment' }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Record Payment
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Contract Modal -->
    @if($showContractModal)
    <div class="fixed inset-0 bg-black/50 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Issue Contract</h3>
            <p class="text-gray-600 mb-4">Confirm the contract issue date:</p>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Contract Issue Date *</label>
                <input type="date" wire:model="contractIssuedAt" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                @error('contractIssuedAt') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-3 justify-end">
                <button wire:click="closeContractModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button wire:click="issueContract" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    Issue Contract
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Start Lease Modal -->
    @if($showStartLeaseModal)
    <div class="fixed inset-0 bg-black/50 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Start Lease</h3>
            <p class="text-gray-600 mb-4">Confirm the lease start date to begin monitoring:</p>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Lease Start Date *</label>
                <input type="date" wire:model="leaseStartDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                @error('leaseStartDate') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                <p class="text-xs text-green-800">
                    <strong>Note:</strong> Starting the lease will begin tracking monthly payments and mileage. The lease will end on {{ $orderData['lease_term_months'] ?? 36 }} months from the start date.
                </p>
            </div>
            <div class="flex gap-3 justify-end">
                <button wire:click="closeStartLeaseModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button wire:click="startLease" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Start Lease
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Return Modal -->
    @if($showReturnModal)
    <div class="fixed inset-0 bg-black/50 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Process Lease Return</h3>
            <p class="text-gray-600 mb-4">Enter the return date and reason (if any):</p>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Return Date *</label>
                    <input type="date" wire:model="returnDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    @error('returnDate') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Return Reason (Optional)</label>
                    <textarea wire:model="returnReason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Reason for early return, damages, etc."></textarea>
                    @error('returnReason') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                @php
                    $currentMileage = $orderData['current_mileage'] ?? 0;
                    $mileageLimitPerYear = $orderData['mileage_limit_per_year'] ?? 15000;
                    $leaseTermMonths = $orderData['lease_term_months'] ?? 36;
                    $allowedMileage = ($mileageLimitPerYear * $leaseTermMonths) / 12;
                    $excessMileage = max(0, $currentMileage - $allowedMileage);
                    $excessMileageCharge = $orderData['excess_mileage_charge'] ?? 0.25;
                    $excessFee = $excessMileage * $excessMileageCharge;
                @endphp
                @if($excessMileage > 0)
                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                    <p class="text-sm text-red-800 font-semibold">Excess Mileage Detected</p>
                    <p class="text-xs text-red-700 mt-1">Current: {{ number_format($currentMileage, 0) }} km | Allowed: {{ number_format($allowedMileage, 0) }} km</p>
                    <p class="text-xs text-red-700 mt-1">Excess: {{ number_format($excessMileage, 0) }} km</p>
                    <p class="text-sm font-bold text-red-800 mt-2">Additional Fee: ${{ number_format($excessFee, 2) }}</p>
                </div>
                @endif
                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                    <p class="text-xs text-red-800">
                        <strong>Warning:</strong> Processing return will terminate the lease contract and release the vehicle for new applications. Final charges will be calculated based on mileage and damages.
                    </p>
                </div>
            </div>
            <div class="flex gap-3 justify-end mt-6">
                <button wire:click="closeReturnModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button wire:click="processReturn" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Terminate Lease
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

