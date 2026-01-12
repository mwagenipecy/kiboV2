<div>
    <!-- Header with Back Button -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('admin.orders.cash.index') }}" class="flex items-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Cash Orders
            </a>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Cash Purchase Order</h1>
                <p class="mt-2 text-gray-600">Order #{{ $order->order_number }}</p>
            </div>
            <div class="flex gap-3">
                @if($order->isPending())
                <button wire:click="openApproveModal" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Approve Order
                </button>
                <button wire:click="openRejectModal" class="px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reject Order
                </button>
                @elseif($order->isApproved())
                <button wire:click="openCompleteModal" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Mark as Completed
                </button>
                <button wire:click="openRejectModal" class="px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reject Order
                </button>
                @endif
            </div>
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
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Order Status</h2>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        @if($order->status->value === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status->value === 'approved') bg-green-100 text-green-800
                        @elseif($order->status->value === 'completed') bg-blue-100 text-blue-800
                        @elseif($order->status->value === 'rejected') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $order->status_label }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Order Number:</p>
                        <p class="font-medium text-gray-900">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Order Type:</p>
                        <p class="font-medium text-gray-900">{{ $order->order_type_label }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Submitted:</p>
                        <p class="font-medium text-gray-900">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @if($order->processed_at)
                    <div>
                        <p class="text-gray-600">Processed:</p>
                        <p class="font-medium text-gray-900">{{ $order->processed_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                    @if($order->completed_at)
                    <div>
                        <p class="text-gray-600">Completed:</p>
                        <p class="font-medium text-gray-900">{{ $order->completed_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                    @if($order->processedBy)
                    <div>
                        <p class="text-gray-600">Processed By:</p>
                        <p class="font-medium text-gray-900">{{ $order->processedBy->name }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Customer Information</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Name:</p>
                        <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Email:</p>
                        <p class="font-medium text-gray-900">{{ $order->user->email }}</p>
                    </div>
                    @if($order->user->customer && $order->user->customer->phone_number)
                    <div>
                        <p class="text-gray-600">Phone Number:</p>
                        <p class="font-medium text-gray-900">{{ $order->user->customer->phone_number }}</p>
                    </div>
                    @endif
                    @if($order->user->customer && $order->user->customer->nida_number)
                    <div>
                        <p class="text-gray-600">NIDA Number:</p>
                        <p class="font-medium text-gray-900">{{ $order->user->customer->nida_number }}</p>
                    </div>
                    @endif
                    @if($order->user->customer && $order->user->customer->address)
                    <div class="col-span-2">
                        <p class="text-gray-600">Address:</p>
                        <p class="font-medium text-gray-900">{{ $order->user->customer->address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Vehicle Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Vehicle Information</h2>
                <div class="flex gap-6 mb-6">
                    @if($order->vehicle->image_front)
                    <div class="w-48 h-48 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        <img src="{{ asset('storage/' . $order->vehicle->image_front) }}" alt="Vehicle" class="w-full h-full object-cover">
                    </div>
                    @endif
                    <div class="flex-1">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-4">
                            {{ $order->vehicle->year }} {{ $order->vehicle->make->name ?? '' }} {{ $order->vehicle->model->name ?? '' }}
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Price:</p>
                                <p class="font-medium text-gray-900 text-lg">£{{ number_format($order->vehicle->price, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Mileage:</p>
                                <p class="font-medium text-gray-900">{{ number_format($order->vehicle->mileage) }} miles</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Condition:</p>
                                <p class="font-medium text-gray-900 capitalize">{{ $order->vehicle->condition }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Registration:</p>
                                <p class="font-medium text-gray-900">{{ $order->vehicle->registration_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Vehicle Status:</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->vehicle->status_badge_class }}">
                                    {{ $order->vehicle->status_label }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($order->vehicle->entity)
                <div class="border-t border-gray-200 pt-4">
                    <p class="text-sm text-gray-600">Seller:</p>
                    <p class="font-medium text-gray-900">{{ $order->vehicle->entity->name }}</p>
                </div>
                @endif
            </div>

            <!-- Customer Notes -->
            @if($order->customer_notes)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="font-semibold text-blue-900 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    Customer Notes
                </h3>
                <p class="text-sm text-blue-800">{{ $order->customer_notes }}</p>
            </div>
            @endif

            <!-- Rejection Reason -->
            @if($order->rejection_reason)
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <h3 class="font-semibold text-red-900 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Rejection Reason
                </h3>
                <p class="text-sm text-red-800">{{ $order->rejection_reason }}</p>
            </div>
            @endif

            <!-- Completion Data -->
            @if($order->isCompleted() && $order->completion_data)
            <div class="bg-green-50 border-2 border-green-500 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-green-900 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Order Completed
                </h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-green-700">Completed By:</p>
                        <p class="font-medium text-green-900">{{ $order->completion_data['completed_by'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-green-700">Completed At:</p>
                        <p class="font-medium text-green-900">
                            {{ isset($order->completion_data['completed_at']) ? \Carbon\Carbon::parse($order->completion_data['completed_at'])->format('M d, Y h:i A') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-green-700">Final Price:</p>
                        <p class="font-medium text-green-900 text-lg">£{{ number_format($order->completion_data['final_price'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Admin Notes -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Admin Notes</h3>
                <form wire:submit="saveNotes">
                    <textarea 
                        wire:model="adminNotes" 
                        rows="6" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"
                        placeholder="Add internal notes about this order..."></textarea>
                    <button 
                        type="submit" 
                        class="mt-3 w-full px-4 py-2 bg-gray-800 text-white font-medium rounded-lg hover:bg-gray-900 transition-colors">
                        Save Notes
                    </button>
                </form>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Order Timeline</h3>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-green-500"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Order Submitted</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($order->processed_at)
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-green-500"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Order {{ $order->isApproved() || $order->isCompleted() ? 'Approved' : 'Processed' }}</p>
                            <p class="text-xs text-gray-500">{{ $order->processed_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($order->completed_at)
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-green-500"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Order Completed</p>
                            <p class="text-xs text-gray-500">{{ $order->completed_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    @if($showApproveModal)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" wire:click="closeApproveModal">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full" wire:click.stop>
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4 rounded-t-xl">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Approve Purchase Order
                </h3>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-4">Are you sure you want to approve this purchase order?</p>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">This will:</p>
                            <ul class="space-y-1 text-xs">
                                <li>• Mark the order as Approved</li>
                                <li>• Reserve the vehicle (status: On Hold)</li>
                                <li>• Notify the customer</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button wire:click="closeApproveModal" class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="approveOrder" class="flex-1 px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                        Yes, Approve
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Reject Modal -->
    @if($showRejectModal)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" wire:click="closeRejectModal">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full" wire:click.stop>
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4 rounded-t-xl">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reject Purchase Order
                </h3>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-4">Please provide a reason for rejecting this order:</p>
                <textarea 
                    wire:model="rejectionReason" 
                    rows="4" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none mb-2"
                    placeholder="Explain why this order is being rejected..."></textarea>
                @error('rejectionReason') 
                <span class="text-sm text-red-600 mb-4 block">{{ $message }}</span> 
                @enderror
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div class="text-sm text-yellow-800">
                            <p class="font-semibold mb-1">This will:</p>
                            <ul class="space-y-1 text-xs">
                                <li>• Mark the order as Rejected</li>
                                <li>• Return vehicle to available status</li>
                                <li>• Notify the customer with rejection reason</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button wire:click="closeRejectModal" class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="rejectOrder" class="flex-1 px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                        Reject Order
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Complete Modal -->
    @if($showCompleteModal)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" wire:click="closeCompleteModal">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full" wire:click.stop>
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 rounded-t-xl">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Complete Purchase Order
                </h3>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-4">Confirm that payment has been received and the vehicle has been delivered/handed over:</p>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-green-800">
                            <p class="font-semibold mb-1">This will:</p>
                            <ul class="space-y-1 text-xs">
                                <li>• Mark the order as Completed</li>
                                <li>• Change vehicle status to Sold</li>
                                <li>• Archive the order</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button wire:click="closeCompleteModal" class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="completeOrder" class="flex-1 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Yes, Complete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

