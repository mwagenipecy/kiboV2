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
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
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
                <div>
                    <label class="text-sm font-medium text-gray-500">Address</label>
                    <p class="text-gray-900 mt-1">{{ $order->delivery_address }}</p>
                    @if($order->delivery_city || $order->delivery_region)
                        <p class="text-gray-600 mt-1">
                            {{ $order->delivery_city }}{{ $order->delivery_city && $order->delivery_region ? ', ' : '' }}{{ $order->delivery_region }}
                        </p>
                    @endif
                </div>
            </div>

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

