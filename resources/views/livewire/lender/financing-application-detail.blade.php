<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Financing Application #{{ $order->order_number }}</h1>
                <p class="mt-2 text-gray-600">Review application and vehicle details</p>
            </div>
            <a href="{{ route('lender.applications.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
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
                <div class="flex items-center gap-4">
                    @php
                        $statusClasses = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'approved' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                        ];
                        $statusClass = $statusClasses[$lenderApprovalStatus] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-4 py-2 text-lg font-semibold rounded-lg {{ $statusClass }}">
                        {{ ucfirst($lenderApprovalStatus) }}
                    </span>
                    <span class="text-sm text-gray-500">Submitted {{ $order->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <!-- Vehicle Images -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Vehicle Images</h2>
                @php
                    $allImages = [];
                    if($order->vehicle->image_front) $allImages[] = $order->vehicle->image_front;
                    if($order->vehicle->other_images && is_array($order->vehicle->other_images)) {
                        $allImages = array_merge($allImages, $order->vehicle->other_images);
                    }
                @endphp
                
                @if(count($allImages) > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($allImages as $image)
                    <div class="aspect-[4/3] bg-gray-100 rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $image) }}" alt="Vehicle image" class="w-full h-full object-cover hover:scale-105 transition-transform duration-200 cursor-pointer">
                    </div>
                    @endforeach
                </div>
                @else
                <div class="aspect-video bg-gray-100 rounded-lg flex items-center justify-center">
                    <div class="text-center text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p>No images available</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Vehicle Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Vehicle Information</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Vehicle</p>
                        <p class="font-medium text-gray-900">{{ $order->vehicle->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Year</p>
                        <p class="font-medium text-gray-900">{{ $order->vehicle->year }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Price</p>
                        <p class="font-medium text-gray-900">£{{ number_format($order->vehicle->price, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Mileage</p>
                        <p class="font-medium text-gray-900">{{ number_format($order->vehicle->mileage) }} km</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Condition</p>
                        <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->vehicle->condition)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Fuel Type</p>
                        <p class="font-medium text-gray-900">{{ ucfirst($order->vehicle->fuel_type) }}</p>
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
                    @if($order->user->customer && $order->user->customer->phone_number)
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="font-medium text-gray-900">{{ $order->user->customer->phone_number }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Loan Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Loan Details</h2>
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
                        <p class="text-lg font-bold text-green-600">{{ number_format($order->order_data['interest_rate'] ?? 0, 2) }}%</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Monthly Payment</p>
                        <p class="text-lg font-bold text-green-600">${{ number_format($order->order_data['monthly_payment'] ?? 0, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Monthly Income</p>
                        <p class="font-medium text-gray-900">${{ number_format($order->order_data['monthly_income'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Lender Notes -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Lender Notes</h2>
                <textarea 
                    wire:model="lenderNotes" 
                    rows="4" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    placeholder="Add your notes about this application..."></textarea>
                <button wire:click="saveNotes" class="mt-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    Save Notes
                </button>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions -->
            @if($lenderApprovalStatus === 'pending')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    <button wire:click="openApproveModal" class="w-full px-4 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                        Approve Financing
                    </button>
                    <button wire:click="openRejectModal" class="w-full px-4 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                        Reject Application
                    </button>
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h3>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-blue-600"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Application Submitted</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @if(isset($order->order_data['dealer_approved_at']))
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-green-600"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Dealer Approved</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($order->order_data['dealer_approved_at'])->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @endif
                    @if($lenderApprovalStatus === 'approved' && isset($order->order_data['lender_approved_at']))
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-green-600"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Lender Approved</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($order->order_data['lender_approved_at'])->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @endif
                    @if($lenderApprovalStatus === 'rejected' && isset($order->order_data['lender_rejected_at']))
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-red-600"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Lender Rejected</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($order->order_data['lender_rejected_at'])->format('M d, Y h:i A') }}</p>
                            @if(isset($order->order_data['lender_rejection_reason']))
                            <p class="text-xs text-gray-600 mt-1">{{ $order->order_data['lender_rejection_reason'] }}</p>
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
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Approve Financing Application</h3>
            <p class="text-gray-600 mb-4">Are you sure you want to approve this financing application? The vehicle will be marked as sold.</p>
            <div class="flex gap-3 justify-end">
                <button wire:click="closeApproveModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button wire:click="approveApplication" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Approve
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Rejection Modal -->
    @if($showRejectModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Reject Application</h3>
            <p class="text-gray-600 mb-4">Please provide a reason for rejecting this application:</p>
            <textarea wire:model="rejectionReason" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent mb-4" placeholder="Enter rejection reason..."></textarea>
            @error('rejectionReason') <p class="text-red-500 text-sm mb-4">{{ $message }}</p> @enderror
            <div class="flex gap-3 justify-end">
                <button wire:click="closeRejectModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button wire:click="rejectApplication" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Reject
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
