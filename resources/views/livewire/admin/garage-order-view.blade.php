<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Garage Order Details</h1>
            <p class="mt-1 text-sm text-gray-600">Order #{{ $order->order_number }}</p>
        </div>
        <a href="{{ route('admin.garage-orders', ['filter' => 'all']) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Orders
        </a>
    </div>

    @php
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';
        $orderStatusPending = $order->status === 'pending';
        $showApproveButton = $orderStatusPending && ($canApprove || $isAdmin);
    @endphp

    @if($showApproveButton)
    <div class="mb-6 bg-white shadow-sm rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-900">Pending Approval</p>
                <p class="text-xs text-gray-500 mt-1">Choose how you want to proceed with this order</p>
            </div>
            <button 
                wire:click="openApproveModal" 
                wire:loading.attr="disabled"
                wire:target="openApproveModal"
                class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                style="background-color: #009866;"
                onmouseover="this.style.backgroundColor='#007a52'"
                onmouseout="this.style.backgroundColor='#009866'"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Approve Order
            </button>
        </div>
    </div>
    @endif

    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                    @elseif($order->status === 'rejected') bg-red-100 text-red-800
                    @elseif($order->status === 'quoted') bg-purple-100 text-purple-800
                    @elseif($order->status === 'in_progress') bg-indigo-100 text-indigo-800
                    @elseif($order->status === 'completed') bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>
            @if($order->quoted_price)
                <div class="text-right">
                    <p class="text-sm text-gray-500">Quoted Price</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $order->currency }} {{ number_format($order->quoted_price, 2) }}</p>
                </div>
            @endif
        </div>

        <div class="px-6 py-4 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-700">Customer</p>
                    <p class="text-sm text-gray-900">{{ $order->customer_name }}</p>
                    <p class="text-sm text-gray-600">{{ $order->customer_email }}</p>
                    <p class="text-sm text-gray-600">{{ $order->customer_phone }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Garage</p>
                    <p class="text-sm text-gray-900">{{ $order->agent->company_name ?? $order->agent->name ?? 'N/A' }}</p>
                    @if($order->agent->phone_number)
                        <p class="text-sm text-gray-600">{{ $order->agent->phone_number }}</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-700">Service Type</p>
                    <p class="text-sm text-gray-900">{{ ucwords(str_replace('_', ' ', $order->service_type)) }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Booking Type</p>
                    <p class="text-sm text-gray-900">{{ ucfirst($order->booking_type) }}</p>
                    @if($order->booking_type === 'scheduled' && $order->scheduled_date)
                        <p class="text-sm text-gray-600 mt-1">
                            Scheduled for {{ \Carbon\Carbon::parse($order->scheduled_date)->format('M d, Y') }}
                            @if($order->scheduled_time)
                                at {{ \Carbon\Carbon::parse($order->scheduled_time)->format('h:i A') }}
                            @endif
                        </p>
                    @endif
                </div>
            </div>

            @if($order->vehicle_make || $order->vehicle_model)
                <div>
                    <p class="text-sm font-medium text-gray-700">Vehicle Information</p>
                    <p class="text-sm text-gray-900">
                        @if($order->vehicle_year) {{ $order->vehicle_year }} @endif
                        {{ $order->vehicle_make }} {{ $order->vehicle_model }}
                        @if($order->vehicle_registration)
                            ({{ $order->vehicle_registration }})
                        @endif
                    </p>
                </div>
            @endif

            @if($order->service_description)
                <div>
                    <p class="text-sm font-medium text-gray-700">Service Description</p>
                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $order->service_description }}</p>
                </div>
            @endif

            @if($order->customer_notes)
                <div>
                    <p class="text-sm font-medium text-gray-700">Customer Notes</p>
                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $order->customer_notes }}</p>
                </div>
            @endif

            @if($order->rejection_reason)
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm font-medium text-red-900">Rejection Reason</p>
                    <p class="text-sm text-red-800 mt-1">{{ $order->rejection_reason }}</p>
                </div>
            @endif

            @if($order->status === 'completed' && $order->completed_at)
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm font-medium text-green-900">Completed</p>
                    <p class="text-sm text-green-800 mt-1">
                        Completed on {{ \Carbon\Carbon::parse($order->completed_at)->format('M d, Y h:i A') }}
                    </p>
                    @if($order->completion_notes)
                        <p class="text-sm text-green-700 mt-2">{{ $order->completion_notes }}</p>
                    @endif
                </div>
            @endif

            <div class="pt-4 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <p class="text-xs text-gray-500">
                    Created at {{ $order->created_at->format('M d, Y h:i A') }}
                </p>
                @if($order->processed_at && $order->processedBy)
                    <p class="text-xs text-gray-500">
                        Last processed by {{ $order->processedBy->name }} on {{ \Carbon\Carbon::parse($order->processed_at)->format('M d, Y h:i A') }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    @if(session()->has('success'))
    <div class="mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session()->has('error'))
    <div class="mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    <!-- Approve Modal -->
    @if($showApproveModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: block !important;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/50 bg-opacity-75 transition-opacity z-[9998]" wire:click="closeApproveModal"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-[9999]">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Approve Order
                        </h3>
                        <button type="button" wire:click="closeApproveModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <p class="text-sm text-gray-600">
                            How would you like to proceed with this order?
                        </p>
                        
                        <div class="grid grid-cols-1 gap-3">
                            <button 
                                type="button"
                                wire:click="handleSendQuotation"
                                wire:loading.attr="disabled"
                                wire:target="handleSendQuotation"
                                class="w-full text-left p-4 border-2 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                style="border-color: rgba(0, 152, 102, 0.3);"
                                onmouseover="this.style.borderColor='#009866'; this.style.backgroundColor='rgba(0, 152, 102, 0.1)'"
                                onmouseout="this.style.borderColor='rgba(0, 152, 102, 0.3)'; this.style.backgroundColor='transparent'"
                            >
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #009866;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900">Send Quotation</h4>
                                        <p class="text-xs text-gray-500 mt-1">Send a price quotation to the customer for approval before confirming the order.</p>
                                    </div>
                                </div>
                            </button>
                            
                            <button 
                                type="button"
                                wire:click="handleConfirmOrder"
                                wire:loading.attr="disabled"
                                wire:target="handleConfirmOrder"
                                class="w-full text-left p-4 border-2 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                style="border-color: rgba(0, 152, 102, 0.3);"
                                onmouseover="this.style.borderColor='#009866'; this.style.backgroundColor='rgba(0, 152, 102, 0.1)'"
                                onmouseout="this.style.borderColor='rgba(0, 152, 102, 0.3)'; this.style.backgroundColor='transparent'"
                            >
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #009866;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900">Confirm Order</h4>
                                        <p class="text-xs text-gray-500 mt-1">Confirm the order directly without sending a quotation. Use this for orders that don't require payment.</p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        type="button" 
                        wire:click="closeApproveModal"
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quote Modal -->
    @if($showQuoteModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: block !important;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/50 bg-opacity-75 transition-opacity z-[9998]" wire:click="closeQuoteModal"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-[9999]">
                <form wire:submit.prevent="submitQuote">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Send Quotation
                            </h3>
                            <button type="button" wire:click="closeQuoteModal" class="text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quoted Price ({{ $order->currency }}) *</label>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    wire:model="quotedPrice" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="0.00"
                                >
                                @error('quotedPrice') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quotation Notes</label>
                                <textarea 
                                    wire:model="quotationNotes" 
                                    rows="4" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                    placeholder="Additional notes about the quotation..."
                                ></textarea>
                                @error('quotationNotes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            wire:target="submitQuote"
                            class="w-full inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors sm:ml-3 sm:w-auto sm:text-sm"
                            style="background-color: #009866;"
                            onmouseover="this.style.backgroundColor='#007a52'"
                            onmouseout="this.style.backgroundColor='#009866'"
                        >
                            <span wire:loading.remove wire:target="submitQuote">Send Quotation</span>
                            <span wire:loading wire:target="submitQuote" class="flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending...
                            </span>
                        </button>
                        <button 
                            type="button" 
                            wire:click="closeQuoteModal"
                            wire:loading.attr="disabled"
                            wire:target="submitQuote"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>


