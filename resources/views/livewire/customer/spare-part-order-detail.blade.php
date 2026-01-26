<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('spare-parts.orders') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $order->order_number }}</h1>
                        <p class="text-gray-600">Ordered on {{ $order->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $this->getStatusColor($order->status) }}">
                        {{ $order->status_label ?? ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
                
                {{-- Quotations Section --}}
                @if($order->quotations && $order->quotations->count() > 0 && in_array($order->status, ['pending', 'quoted']))
                <div class="mt-4 p-4 rounded-lg border border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900">
                            {{ $order->quotations->count() }} Quotation(s) Received
                        </h3>
                        <span class="text-xs text-gray-500">Select one to proceed</span>
                    </div>
                    <div class="space-y-3">
                        @foreach($order->quotations->where('status', 'pending') as $quotation)
                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-lg font-bold" style="color: #009866;">{{ $quotation->currency }} {{ number_format($quotation->quoted_price, 2) }}</span>
                                        @if($quotation->estimated_days)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $quotation->estimated_days }} days delivery
                                        </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">Supplier: {{ $quotation->agent->name ?? 'Verified Supplier' }}</p>
                                    @if($quotation->quotation_notes)
                                    <p class="text-sm text-gray-500 mt-2">{{ $quotation->quotation_notes }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-2">Quoted {{ $quotation->created_at->diffForHumans() }}</p>
                                </div>
                                <button 
                                    wire:click="openQuoteResponseModal({{ $quotation->id }})"
                                    class="ml-4 inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors"
                                    style="background-color: #009866;"
                                    onmouseover="this.style.backgroundColor='#007a52'"
                                    onmouseout="this.style.backgroundColor='#009866'"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Accept
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Accepted Quotation --}}
                @if($order->acceptedQuotation)
                <div class="mt-4 p-4 rounded-lg border-2" style="border-color: #009866; background-color: rgba(0, 152, 102, 0.05);">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #009866;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-semibold" style="color: #009866;">Accepted Quotation</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $order->acceptedQuotation->currency }} {{ number_format($order->acceptedQuotation->quoted_price, 2) }}</p>
                            <p class="text-sm text-gray-600">Supplier: {{ $order->acceptedQuotation->agent->name ?? 'Verified Supplier' }}</p>
                        </div>
                        @if($order->acceptedQuotation->estimated_days)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $order->acceptedQuotation->estimated_days }} days delivery
                        </span>
                        @endif
                    </div>
                </div>
                @endif

                @if($order->status === 'awaiting_payment')
                <div class="mt-4 p-4 bg-orange-50 rounded-lg border border-orange-200">
                    <p class="text-sm font-medium text-orange-800 mb-3">Your order is awaiting payment. Please submit proof of payment:</p>
                    <button wire:click="openPaymentModal" class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Submit Payment Proof
                    </button>
                </div>
                @endif

                @if($order->status === 'payment_submitted')
                <div class="mt-4 p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                    <p class="text-sm font-medium text-indigo-800">Your payment proof has been submitted and is pending verification. We'll notify you once verified.</p>
                </div>
                @endif

                @if($order->status === 'shipped')
                <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm font-medium text-blue-800">
                        📦 Your order has been shipped!
                        @if($order->tracking_number)
                        <br>Tracking Number: <span class="font-mono">{{ $order->tracking_number }}</span>
                        @endif
                    </p>
                </div>
                @endif
            </div>

            <!-- Order Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Order Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Order Number</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $order->order_number }}</p>
                    </div>
                    @if($order->quoted_price)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Quoted Price</label>
                            <p class="text-lg font-semibold text-gray-900 mt-1">
                                {{ number_format($order->quoted_price, 2) }} {{ $order->currency ?? 'TZS' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Vehicle Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Vehicle Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Make</label>
                        <p class="text-gray-900 font-medium mt-1">{{ $order->vehicleMake->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Model</label>
                        <p class="text-gray-900 font-medium mt-1">{{ $order->vehicleModel->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Condition</label>
                        <p class="text-gray-900 font-medium mt-1 capitalize">{{ $order->condition }}</p>
                    </div>
                </div>
            </div>

            <!-- Part Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Part Information</h2>
                <div>
                    <label class="text-sm font-medium text-gray-500">Part Name</label>
                    <p class="text-gray-900 font-medium mt-1">{{ $order->part_name ?? 'N/A' }}</p>
                </div>
                @if($order->description)
                    <div class="mt-4">
                        <label class="text-sm font-medium text-gray-500">Description</label>
                        <p class="text-gray-900 mt-1">{{ $order->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Delivery Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Delivery Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Address</label>
                        <p class="text-gray-900 mt-1">{{ $order->delivery_address }}</p>
                        @if($order->delivery_city || $order->delivery_region)
                            <p class="text-gray-600 mt-1">
                                {{ $order->delivery_city }}{{ $order->delivery_city && $order->delivery_region ? ', ' : '' }}{{ $order->delivery_region }}
                            </p>
                        @endif
                    </div>
                    @if($order->estimated_delivery_date)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Estimated Delivery</label>
                        <p class="text-gray-900 font-semibold mt-1">{{ $order->estimated_delivery_date->format('M d, Y') }}</p>
                    </div>
                    @endif
                    @if($order->tracking_number)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tracking Number</label>
                        <p class="text-gray-900 font-mono mt-1">{{ $order->tracking_number }}</p>
                    </div>
                    @endif
                </div>
                @if($order->delivery_notes)
                <div class="mt-4">
                    <label class="text-sm font-medium text-gray-500">Delivery Notes</label>
                    <p class="text-gray-900 mt-1">{{ $order->delivery_notes }}</p>
                </div>
                @endif
            </div>

            {{-- Payment Information --}}
            @if($order->payment_method)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Payment Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Payment Method</label>
                        <p class="text-gray-900 font-semibold mt-1 capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Payment Status</label>
                        <p class="mt-1">
                            @if($order->payment_verified)
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-sm font-medium rounded">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Verified
                            </span>
                            @elseif($order->payment_submitted_at)
                            <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded">Pending Verification</span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 bg-orange-100 text-orange-800 text-sm font-medium rounded">Awaiting Payment</span>
                            @endif
                        </p>
                    </div>
                </div>
                @if($order->payment_account_details)
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Payment Details</label>
                    @if(isset($order->payment_account_details['bank_name']))
                    <p class="text-gray-900"><strong>Bank:</strong> {{ $order->payment_account_details['bank_name'] }}</p>
                    <p class="text-gray-900"><strong>Account Name:</strong> {{ $order->payment_account_details['account_name'] }}</p>
                    <p class="text-gray-900"><strong>Account Number:</strong> {{ $order->payment_account_details['account_number'] }}</p>
                    @endif
                    @if(isset($order->payment_account_details['mobile_provider']))
                    <p class="text-gray-900"><strong>Provider:</strong> {{ $order->payment_account_details['mobile_provider'] }}</p>
                    <p class="text-gray-900"><strong>Mobile Number:</strong> {{ $order->payment_account_details['mobile_number'] }}</p>
                    <p class="text-gray-900"><strong>Account Name:</strong> {{ $order->payment_account_details['account_name'] }}</p>
                    @endif
                </div>
                @endif
            </div>
            @endif

            @if($order->admin_notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Admin Notes</h2>
                    <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $order->admin_notes }}</p>
                </div>
            @endif
        </div>

        <!-- Chat Section (Only for accepted orders) -->
        @if(in_array($order->status, ['accepted', 'quoted', 'processing']))
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-4">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Chat with Supplier
                    </h2>

                    <!-- Messages -->
                    <div class="h-96 overflow-y-auto mb-4 space-y-3" id="chat-messages">
                        @if(count($messages) > 0)
                            @foreach($messages as $message)
                                <div class="flex items-start gap-2 {{ $message['user_id'] == auth()->id() ? 'flex-row-reverse' : '' }}">
                                    <div class="w-8 h-8 {{ $message['user_id'] == auth()->id() ? 'bg-green-600' : 'bg-gray-400' }} rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-xs font-semibold">
                                            {{ strtoupper(substr($message['user_name'] ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 {{ $message['user_id'] == auth()->id() ? 'flex flex-col items-end' : '' }}">
                                        <div class="{{ $message['user_id'] == auth()->id() ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-900' }} rounded-lg p-3 max-w-[80%]">
                                            <p class="text-sm">{{ $message['message'] }}</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1 {{ $message['user_id'] == auth()->id() ? 'mr-1' : 'ml-1' }}">
                                            {{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-gray-500 py-8">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <p class="text-sm">No messages yet. Start the conversation!</p>
                            </div>
                        @endif
                    </div>

                    <!-- Message Input -->
                    <form wire:submit.prevent="sendMessage" class="flex gap-2">
                        <input
                            type="text"
                            wire:model="newMessage"
                            placeholder="Type your message..."
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none"
                        >
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span wire:loading.remove>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </span>
                            <span wire:loading>
                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

    {{-- Quote Response Modal --}}
    @if($showQuoteResponseModal && $selectedQuotationId)
    @php
        $selectedQuotation = $order->quotations->firstWhere('id', $selectedQuotationId);
    @endphp
    @if($selectedQuotation)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" style="display: block !important;">
        <div class="fixed inset-0 bg-black/50 z-[9998]" wire:click="closeQuoteResponseModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-[9999]" wire:click.stop>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Confirm Quotation Acceptance</h3>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-gray-600">Part Name:</span>
                        <span class="font-medium text-gray-900">{{ $order->part_name }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-gray-600">Vehicle:</span>
                        <span class="font-medium text-gray-900">{{ $order->vehicleMake->name ?? 'N/A' }} {{ $order->vehicleModel->name ?? '' }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-gray-600">Supplier:</span>
                        <span class="font-medium text-gray-900">{{ $selectedQuotation->agent->name ?? 'Verified Supplier' }}</span>
                    </div>
                    @if($selectedQuotation->estimated_days)
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-gray-600">Est. Delivery:</span>
                        <span class="font-medium text-gray-900">{{ $selectedQuotation->estimated_days }} days</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                        <span class="text-gray-600">Quoted Price:</span>
                        <span class="text-2xl font-bold" style="color: #009866;">{{ $selectedQuotation->currency }} {{ number_format($selectedQuotation->quoted_price, 2) }}</span>
                    </div>
                </div>
                
                @if($selectedQuotation->quotation_notes)
                <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                    <p class="text-sm text-yellow-800"><strong>Supplier Notes:</strong> {{ $selectedQuotation->quotation_notes }}</p>
                </div>
                @endif

                @if($order->quotations->where('status', 'pending')->count() > 1)
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Note: You have {{ $order->quotations->where('status', 'pending')->count() }} quotation(s). Accepting this one will automatically decline the others.
                    </p>
                </div>
                @endif
                
                <p class="text-gray-600 text-sm mb-6">By accepting this quotation, you agree to proceed with the order at the quoted price.</p>
                
                <div class="flex gap-3">
                    <button wire:click="acceptQuotation({{ $selectedQuotationId }})" wire:loading.attr="disabled" class="flex-1 inline-flex justify-center items-center px-4 py-2 text-white font-medium rounded-lg transition-colors disabled:opacity-50" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                        <span wire:loading.remove wire:target="acceptQuotation">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Accept This Quotation
                        </span>
                        <span wire:loading wire:target="acceptQuotation" class="flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
                <button wire:click="closeQuoteResponseModal" class="w-full mt-3 px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    @endif
    @endif

    {{-- Payment Modal --}}
    @if($showPaymentModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" style="display: block !important;">
        <div class="fixed inset-0 bg-black/50 z-[9998]" wire:click="closePaymentModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-[9999]" wire:click.stop>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Submit Payment Proof</h3>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-700 mb-2"><strong>Amount to Pay:</strong></p>
                    <p class="text-2xl font-bold" style="color: #009866;">{{ $order->currency ?? 'TZS' }} {{ number_format($order->quoted_price, 2) }}</p>
                </div>

                @if($order->payment_account_details)
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm font-medium text-blue-800 mb-2">Pay to:</p>
                    @if(isset($order->payment_account_details['bank_name']))
                    <p class="text-blue-900"><strong>Bank:</strong> {{ $order->payment_account_details['bank_name'] }}</p>
                    <p class="text-blue-900"><strong>Account Name:</strong> {{ $order->payment_account_details['account_name'] }}</p>
                    <p class="text-blue-900 font-mono"><strong>Account Number:</strong> {{ $order->payment_account_details['account_number'] }}</p>
                    @endif
                    @if(isset($order->payment_account_details['mobile_provider']))
                    <p class="text-blue-900"><strong>Provider:</strong> {{ $order->payment_account_details['mobile_provider'] }}</p>
                    <p class="text-blue-900"><strong>Name:</strong> {{ $order->payment_account_details['account_name'] }}</p>
                    <p class="text-blue-900 font-mono"><strong>Number:</strong> {{ $order->payment_account_details['mobile_number'] }}</p>
                    @endif
                </div>
                @endif
                
                <form wire:submit.prevent="submitPayment" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Proof (Receipt/Screenshot) *</label>
                        <input type="file" wire:model="paymentProof" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                        @error('paymentProof') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, PNG, PDF (Max 5MB)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Notes (Optional)</label>
                        <textarea wire:model="paymentNotes" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent resize-none" placeholder="Transaction ID, reference number, etc."></textarea>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="submit" wire:loading.attr="disabled" wire:target="submitPayment,paymentProof" class="flex-1 inline-flex justify-center items-center px-4 py-2 text-white font-medium rounded-lg transition-colors disabled:opacity-50" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                            <span wire:loading.remove wire:target="submitPayment">Submit Payment</span>
                            <span wire:loading wire:target="submitPayment,paymentProof" class="flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Uploading...
                            </span>
                        </button>
                        <button type="button" wire:click="closePaymentModal" class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
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

@script
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('message-sent', () => {
            const chatMessages = document.getElementById('chat-messages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        });
    });

    // Auto-scroll to bottom when new messages arrive
    document.addEventListener('livewire:update', () => {
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
</script>
@endscript

