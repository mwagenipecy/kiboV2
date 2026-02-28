<div class="px-6 py-4">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('admin.spare-part-orders') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Orders
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Order: {{ $order->order_number }}</h1>
            <p class="text-sm text-gray-500 mt-1">Created {{ $order->created_at->format('M d, Y h:i A') }}</p>
            @if(($order->order_channel ?? 'portal') === 'whatsapp')
                <span class="inline-flex items-center mt-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">WhatsApp</span>
            @else
                <span class="inline-flex items-center mt-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Portal</span>
            @endif
        </div>
        
        {{-- Status Badge --}}
        <div class="text-right">
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                @elseif($order->status === 'quoted') bg-purple-100 text-purple-800
                @elseif($order->status === 'accepted') bg-green-100 text-green-800
                @elseif($order->status === 'awaiting_payment') bg-orange-100 text-orange-800
                @elseif($order->status === 'payment_submitted') bg-indigo-100 text-indigo-800
                @elseif($order->status === 'payment_verified') bg-teal-100 text-teal-800
                @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                @elseif($order->status === 'completed') bg-green-100 text-green-800
                @elseif($order->status === 'rejected') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800
                @endif">
                {{ $order->status_label }}
            </span>
        </div>
    </div>

    {{-- Action Buttons (Based on Order Status) --}}
    @if($canManage)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Actions</h3>
        <div class="flex flex-wrap gap-3">
            @if($order->canBeQuoted())
            <button 
                wire:click="openQuoteModal"
                class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors"
                style="background-color: #009866;"
                onmouseover="this.style.backgroundColor='#007a52'"
                onmouseout="this.style.backgroundColor='#009866'"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Send Quotation
            </button>
            @endif

            @if($order->status === 'accepted' || $order->status === 'quoted')
            <button 
                wire:click="openDeliveryModal"
                class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors"
                style="background-color: #009866;"
                onmouseover="this.style.backgroundColor='#007a52'"
                onmouseout="this.style.backgroundColor='#009866'"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Set Delivery & Payment Info
            </button>
            @endif

            @if($order->canVerifyPayment())
            <button 
                wire:click="openPaymentVerifyModal"
                class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors"
                style="background-color: #009866;"
                onmouseover="this.style.backgroundColor='#007a52'"
                onmouseout="this.style.backgroundColor='#009866'"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Verify Payment
            </button>
            @endif

            @if($order->status === 'payment_verified')
            <button 
                wire:click="openShippingModal"
                class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors"
                style="background-color: #009866;"
                onmouseover="this.style.backgroundColor='#007a52'"
                onmouseout="this.style.backgroundColor='#009866'"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
                Mark as Shipped
            </button>
            @endif

            @if($order->status === 'shipped')
            <button 
                wire:click="markAsDelivered"
                wire:loading.attr="disabled"
                class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-50"
                style="background-color: #009866;"
                onmouseover="this.style.backgroundColor='#007a52'"
                onmouseout="this.style.backgroundColor='#009866'"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Mark as Delivered
            </button>
            @endif

            @if($order->status === 'delivered')
            <button 
                wire:click="markAsCompleted"
                wire:loading.attr="disabled"
                class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-50"
                style="background-color: #009866;"
                onmouseover="this.style.backgroundColor='#007a52'"
                onmouseout="this.style.backgroundColor='#009866'"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Complete Order
            </button>
            @endif

            @if(in_array($order->status, ['pending', 'processing', 'quoted']))
            <button 
                wire:click="rejectOrder"
                wire:loading.attr="disabled"
                wire:confirm="Are you sure you want to reject this order?"
                class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Reject Order
            </button>
            @endif
        </div>
    </div>
    @endif

    {{-- Order Progress Timeline --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Progress</h3>
        <div class="flex items-center justify-between">
            @php
                $steps = [
                    ['key' => 'pending', 'label' => 'Submitted'],
                    ['key' => 'quoted', 'label' => 'Quoted'],
                    ['key' => 'accepted', 'label' => 'Accepted'],
                    ['key' => 'awaiting_payment', 'label' => 'Payment'],
                    ['key' => 'shipped', 'label' => 'Shipped'],
                    ['key' => 'completed', 'label' => 'Completed'],
                ];
                $statusOrder = ['pending', 'processing', 'quoted', 'accepted', 'awaiting_payment', 'payment_submitted', 'payment_verified', 'preparing', 'shipped', 'delivered', 'completed'];
                $currentIndex = array_search($order->status, $statusOrder);
            @endphp
            @foreach($steps as $index => $step)
                @php
                    $stepIndex = array_search($step['key'], $statusOrder);
                    $isCompleted = $currentIndex >= $stepIndex;
                    $isCurrent = $order->status === $step['key'] || ($step['key'] === 'awaiting_payment' && in_array($order->status, ['awaiting_payment', 'payment_submitted', 'payment_verified']));
                @endphp
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold
                        @if($order->status === 'rejected') bg-red-100 text-red-600
                        @elseif($isCompleted) text-white
                        @else bg-gray-100 text-gray-400
                        @endif"
                        @if($isCompleted && $order->status !== 'rejected') style="background-color: #009866;" @endif>
                        @if($isCompleted && $order->status !== 'rejected')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </div>
                    <span class="text-xs font-medium mt-2 @if($isCurrent) text-gray-900 @else text-gray-500 @endif">{{ $step['label'] }}</span>
                </div>
                @if($index < count($steps) - 1)
                <div class="flex-1 h-1 mx-2 rounded @if($currentIndex > $stepIndex) @else bg-gray-200 @endif"
                    @if($currentIndex > $stepIndex) style="background-color: #009866;" @endif></div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Customer Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Name</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Email</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $order->customer_email }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Phone</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $order->customer_phone }}</dd>
                </div>
            </dl>
        </div>

        {{-- Vehicle Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Information</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Make</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $order->vehicleMake->name ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Model</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $order->vehicleModel->name ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Condition</dt>
                    <dd class="text-sm font-medium text-gray-900 capitalize">{{ $order->condition }}</dd>
                </div>
            </dl>
        </div>

        {{-- Part Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Part Information</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Part Name</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $order->part_name ?? 'N/A' }}</dd>
                </div>
                @if($order->description)
                <div>
                    <dt class="text-sm text-gray-500 mb-1">Description</dt>
                    <dd class="text-sm text-gray-900">{{ $order->description }}</dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Delivery Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Delivery Information</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm text-gray-500 mb-1">Address</dt>
                    <dd class="text-sm text-gray-900">{{ $order->delivery_address }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">City</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $order->delivery_city ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Region</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $order->delivery_region ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Country</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $order->delivery_country }}</dd>
                </div>
                @if($order->estimated_delivery_date)
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Est. Delivery</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $order->estimated_delivery_date->format('M d, Y') }}</dd>
                </div>
                @endif
                @if($order->tracking_number)
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Tracking #</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $order->tracking_number }}</dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Quotation Details --}}
        @if($order->quoted_price)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quotation Details</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Price</dt>
                    <dd class="text-lg font-bold" style="color: #009866;">{{ $order->currency }} {{ number_format($order->quoted_price, 2) }}</dd>
                </div>
                @if($order->quotation_notes)
                <div>
                    <dt class="text-sm text-gray-500 mb-1">Notes</dt>
                    <dd class="text-sm text-gray-900">{{ $order->quotation_notes }}</dd>
                </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Quoted At</dt>
                    <dd class="text-sm text-gray-900">{{ $order->quoted_at?->format('M d, Y h:i A') ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>
        @endif

        {{-- Payment Information --}}
        @if($order->payment_method)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Method</dt>
                    <dd class="text-sm font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</dd>
                </div>
                @if($order->payment_account_details)
                    @if(isset($order->payment_account_details['bank_name']))
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Bank</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $order->payment_account_details['bank_name'] }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Account Name</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $order->payment_account_details['account_name'] }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Account Number</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $order->payment_account_details['account_number'] }}</dd>
                    </div>
                    @endif
                    @if(isset($order->payment_account_details['mobile_provider']))
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Provider</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $order->payment_account_details['mobile_provider'] }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Mobile Number</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $order->payment_account_details['mobile_number'] }}</dd>
                    </div>
                    @endif
                @endif
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Payment Verified</dt>
                    <dd class="text-sm font-medium @if($order->payment_verified) text-green-600 @else text-yellow-600 @endif">
                        {{ $order->payment_verified ? 'Yes' : 'No' }}
                    </dd>
                </div>
                @if($order->payment_proof)
                <div>
                    <dt class="text-sm text-gray-500 mb-2">Payment Proof</dt>
                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="inline-flex items-center text-sm font-medium" style="color: #009866;">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Payment Proof
                    </a>
                </div>
                @endif
            </dl>
        </div>
        @endif
    </div>

    {{-- Quote Modal --}}
    @if($showQuoteModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" style="display: block !important;">
        <div class="fixed inset-0 bg-black/50 z-[9998]" wire:click="closeQuoteModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-[9999]" wire:click.stop>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Send Quotation</h3>
                <form wire:submit.prevent="submitQuote" class="space-y-4">
                    @if($isAdmin && $sparePartAgents->isNotEmpty())
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Submit quote as (Agent) *</label>
                        <select wire:model="selectedAgentId" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                            <option value="">Select agent...</option>
                            @foreach($sparePartAgents as $a)
                                <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->company_name ?? $a->email }})</option>
                            @endforeach
                        </select>
                        @error('selectedAgentId') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    @endif
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                            <input type="number" step="0.01" wire:model="quotedPrice" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent" style="focus:ring-color: #009866;" placeholder="0.00">
                            @error('quotedPrice') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Currency *</label>
                            <select wire:model="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                                <option value="TZS">TZS</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="KES">KES</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quotation Notes</label>
                        <textarea wire:model="quotationNotes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent resize-none" placeholder="Add any notes about this quotation..."></textarea>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="submit" wire:loading.attr="disabled" wire:target="submitQuote" class="flex-1 inline-flex justify-center items-center px-4 py-2 text-white font-medium rounded-lg transition-colors disabled:opacity-50" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                            <span wire:loading.remove wire:target="submitQuote">Send Quotation</span>
                            <span wire:loading wire:target="submitQuote" class="flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending...
                            </span>
                        </button>
                        <button type="button" wire:click="closeQuoteModal" class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Delivery & Payment Modal --}}
    @if($showDeliveryModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" style="display: block !important;">
        <div class="fixed inset-0 bg-black/50 z-[9998]" wire:click="closeDeliveryModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 z-[9999]" wire:click.stop>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Set Delivery & Payment Information</h3>
                <form wire:submit.prevent="submitDeliveryInfo" class="space-y-6">
                    {{-- Delivery Section --}}
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Delivery Details</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Delivery Date *</label>
                                <input type="date" wire:model="estimatedDeliveryDate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                                @error('estimatedDeliveryDate') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Notes</label>
                                <textarea wire:model="deliveryNotes" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent resize-none" placeholder="Add any delivery notes..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Section --}}
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Payment Information</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                                <select wire:model.live="paymentMethod" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                                    <option value="">Select payment method</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="online">Online Payment</option>
                                    <option value="offline">Cash on Delivery</option>
                                </select>
                                @error('paymentMethod') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            @if(in_array($paymentMethod, ['bank_transfer', 'offline']))
                            <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name *</label>
                                    <input type="text" wire:model="bankName" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="e.g., CRDB Bank">
                                    @error('bankName') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Name *</label>
                                    <input type="text" wire:model="accountName" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Account holder name">
                                    @error('accountName') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Number *</label>
                                    <input type="text" wire:model="accountNumber" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Account number">
                                    @error('accountNumber') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            @endif

                            @if($paymentMethod === 'mobile_money')
                            <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Provider *</label>
                                    <select wire:model="mobileProvider" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                        <option value="">Select provider</option>
                                        <option value="M-Pesa">M-Pesa</option>
                                        <option value="Tigo Pesa">Tigo Pesa</option>
                                        <option value="Airtel Money">Airtel Money</option>
                                        <option value="Halotel">Halotel</option>
                                    </select>
                                    @error('mobileProvider') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number *</label>
                                    <input type="text" wire:model="mobileNumber" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="+255 xxx xxx xxx">
                                    @error('mobileNumber') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Name *</label>
                                    <input type="text" wire:model="accountName" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Registered name">
                                    @error('accountName') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <button type="submit" wire:loading.attr="disabled" wire:target="submitDeliveryInfo" class="flex-1 inline-flex justify-center items-center px-4 py-2 text-white font-medium rounded-lg transition-colors disabled:opacity-50" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                            <span wire:loading.remove wire:target="submitDeliveryInfo">Save & Notify Customer</span>
                            <span wire:loading wire:target="submitDeliveryInfo" class="flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Saving...
                            </span>
                        </button>
                        <button type="button" wire:click="closeDeliveryModal" class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Payment Verification Modal --}}
    @if($showPaymentVerifyModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" style="display: block !important;">
        <div class="fixed inset-0 bg-black/50 z-[9998]" wire:click="closePaymentVerifyModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-[9999]" wire:click.stop>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Verify Payment</h3>
                
                <div class="mb-6">
                    <p class="text-gray-600 mb-4">Review the payment proof submitted by the customer and confirm if the payment is valid.</p>
                    
                    @if($order->payment_proof)
                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Payment Proof
                    </a>
                    @else
                    <p class="text-yellow-600 text-sm">No payment proof has been submitted yet.</p>
                    @endif
                </div>

                <div class="flex gap-3">
                    <button wire:click="verifyPayment" wire:loading.attr="disabled" class="flex-1 inline-flex justify-center items-center px-4 py-2 text-white font-medium rounded-lg transition-colors disabled:opacity-50" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Verify Payment
                    </button>
                    <button wire:click="rejectPayment" wire:loading.attr="disabled" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reject
                    </button>
                    <button wire:click="closePaymentVerifyModal" class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Shipping Modal --}}
    @if($showShippingModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" style="display: block !important;">
        <div class="fixed inset-0 bg-black/50 z-[9998]" wire:click="closeShippingModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-[9999]" wire:click.stop>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Mark as Shipped</h3>
                <form wire:submit.prevent="markAsShipped" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tracking Number (Optional)</label>
                        <input type="text" wire:model="trackingNumber" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent" placeholder="Enter tracking number if available">
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="submit" wire:loading.attr="disabled" wire:target="markAsShipped" class="flex-1 inline-flex justify-center items-center px-4 py-2 text-white font-medium rounded-lg transition-colors disabled:opacity-50" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                            <span wire:loading.remove wire:target="markAsShipped">Confirm Shipment</span>
                            <span wire:loading wire:target="markAsShipped" class="flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                        <button type="button" wire:click="closeShippingModal" class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Success Modal --}}
    @if($showSuccessModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" style="display: block !important;">
        <div class="fixed inset-0 bg-black/50 z-[9998]" wire:click="closeSuccessModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 z-[9999]" wire:click.stop>
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full mb-4" style="background-color: rgba(0, 152, 102, 0.1);">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #009866;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Success!</h3>
                    <p class="text-gray-600 mb-6">{{ $successMessage }}</p>
                    <button wire:click="closeSuccessModal" class="px-6 py-2 text-white font-medium rounded-lg transition-colors" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Error Modal --}}
    @if($showErrorModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" style="display: block !important;">
        <div class="fixed inset-0 bg-black/50 z-[9998]" wire:click="closeErrorModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 z-[9999]" wire:click.stop>
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Error</h3>
                    <p class="text-gray-600 mb-6">{{ $errorMessage }}</p>
                    <button wire:click="closeErrorModal" class="px-6 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

