<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Spare Parts</h1>
        <p class="text-gray-600">Submit your order and we'll match you with the best suppliers</p>
    </div>
    
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
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Order Submitted Successfully!</h3>
                    <p class="text-gray-600 mb-4">{{ $successMessage }}</p>
                    
                    @if(count($createdOrderNumbers) > 0)
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Your Order Number(s):</p>
                        @foreach($createdOrderNumbers as $orderNumber)
                        <p class="text-lg font-bold" style="color: #009866;">{{ $orderNumber }}</p>
                        @endforeach
                    </div>
                    <p class="text-sm text-gray-500 mb-4">You can track your order status in the "My Orders" section.</p>
                    @endif
                    
                    <div class="flex gap-3 justify-center">
                        <a href="{{ route('spare-parts.orders') }}" class="px-4 py-2 text-white rounded-lg font-medium transition-colors" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                            View My Orders
                        </a>
                        <button wire:click="closeSuccessModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                            Close
                        </button>
                    </div>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Error</h3>
                    <p class="text-gray-600 mb-6">{{ $errorMessage }}</p>
                    
                    <div class="flex gap-3 justify-center">
                        @guest
                        <a href="{{ route('login') }}" class="px-4 py-2 text-white rounded-lg font-medium transition-colors" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                            Sign In
                        </a>
                        @endguest
                        <button wire:click="closeErrorModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Login Notice -->
    @guest
        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 px-4 py-3 rounded">
            <p class="text-sm text-blue-800">
                Please <a href="{{ route('login') }}" class="font-semibold underline">sign in</a> to submit your spare part order request.
            </p>
        </div>
    @endguest

    <!-- Main Form -->
    <form wire:submit.prevent="submitOrders" class="bg-white rounded-2xl shadow-lg p-8 space-y-8">
        <!-- Order Type Selector -->
        <div class="space-y-5">
            <h2 class="text-lg font-semibold text-gray-900 pb-2 border-b">Order Type</h2>
            
            <div class="grid grid-cols-2 gap-4">
                <button
                    type="button"
                    wire:click="$set('orderType', 'single'); $dispatch('reset-order-items')"
                    class="px-6 py-4 rounded-lg font-medium text-lg transition-all {{ $orderType === 'single' ? 'text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    style="{{ $orderType === 'single' ? 'background-color: #009866;' : '' }}"
                >
                    Single Order
                </button>
                <button
                    type="button"
                    wire:click="$set('orderType', 'bulk')"
                    class="px-6 py-4 rounded-lg font-medium text-lg transition-all {{ $orderType === 'bulk' ? 'text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    style="{{ $orderType === 'bulk' ? 'background-color: #009866;' : '' }}"
                >
                    Bulk Order
                </button>
            </div>
        </div>
        <!-- Customer Information -->
        <div class="space-y-5">
            <h2 class="text-lg font-semibold text-gray-900 pb-2 border-b">Customer Information</h2>
            
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input
                        type="text"
                        wire:model="customerName"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:border-transparent"
                        style="focus:ring-color: #009866;"
                        placeholder="John Doe"
                        @auth readonly @endauth
                    >
                    @error('customerName')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input
                        type="email"
                        wire:model="customerEmail"
                        required
                        class="kibo-input w-full px-4 py-3 rounded-lg border border-gray-300"
                        placeholder="john@company.com"
                        @auth readonly @endauth
                    >
                    @error('customerEmail')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                    <input
                        type="tel"
                        wire:model="customerPhone"
                        required
                        class="kibo-input w-full px-4 py-3 rounded-lg border border-gray-300"
                        placeholder="+255 123 456 789"
                    >
                    @error('customerPhone')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                    <input
                        type="text"
                        wire:model="company"
                        class="kibo-input w-full px-4 py-3 rounded-lg border border-gray-300"
                        placeholder="Optional"
                    >
                </div>
            </div>
        </div>

        <!-- Vehicle Information (Only for Single Orders) -->
        @if($orderType === 'single')
        <div class="space-y-5">
            <h2 class="text-lg font-semibold text-gray-900 pb-2 border-b">Vehicle Information</h2>
            
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Make *</label>
                    <select
                        wire:model.live="vehicleMakeId"
                        required
                        class="kibo-input w-full px-4 py-3 rounded-lg border border-gray-300"
                    >
                        <option value="">Select Make</option>
                        @foreach($vehicleMakes as $make)
                            <option value="{{ $make->id }}">{{ $make->name }}</option>
                        @endforeach
                    </select>
                    @error('vehicleMakeId')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Model *</label>
                    <select
                        wire:model="vehicleModelId"
                        required
                        @if(!$vehicleMakeId) disabled @endif
                        class="kibo-input w-full px-4 py-3 rounded-lg border border-gray-300"
                    >
                        <option value="">@if($vehicleMakeId) Select Model @else Select Make First @endif</option>
                        @foreach($vehicleModels as $model)
                            <option value="{{ $model->id }}">{{ $model->name }}</option>
                        @endforeach
                    </select>
                    @error('vehicleModelId')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <input
                        type="number"
                        wire:model="vehicleYear"
                        min="1900"
                        max="{{ date('Y') + 1 }}"
                        class="kibo-input w-full px-4 py-3 rounded-lg border border-gray-300"
                        placeholder="e.g., 2020"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">VIN (Optional)</label>
                    <input
                        type="text"
                        wire:model="vehicleVin"
                        maxlength="17"
                        class="kibo-input w-full px-4 py-3 rounded-lg border border-gray-300 uppercase"
                        placeholder="17-character VIN"
                    >
                </div>
            </div>
        </div>
        @endif

        <!-- Order Items -->
        <div class="space-y-5">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 pb-2 border-b flex-1">
                    Order Items ({{ count($orderItems) }} {{ count($orderItems) === 1 ? 'item' : 'items' }})
                </h2>
                @if($orderType === 'bulk')
                    <button
                        type="button"
                        wire:click="addOrderItem"
                        class="ml-4 px-4 py-2 text-white rounded-lg font-medium flex items-center gap-2 transition-colors kibo-btn"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Item
                    </button>
                @endif
            </div>

            
            <div class="space-y-4">
                @foreach($orderItems as $index => $item)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-semibold text-gray-900">Part {{ $index + 1 }}</h3>
                            @if($orderType === 'bulk' && count($orderItems) > 1)
                                <button
                                    type="button"
                                    wire:click="removeOrderItem({{ $item['id'] }})"
                                    class="text-red-600 hover:text-red-700 text-sm"
                                    title="Remove item"
                                >
                                    Remove
                                </button>
                            @endif
                        </div>

                        <!-- Vehicle Info for Bulk Orders -->
                        @if($orderType === 'bulk')
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Make *</label>
                                <select
                                    wire:model.live="orderItems.{{ $index }}.vehicle_make_id"
                                    required
                                    class="kibo-input w-full px-4 py-3 rounded-lg border border-gray-300"
                                >
                                    <option value="">Select Make</option>
                                    @foreach($vehicleMakes as $make)
                                        <option value="{{ $make->id }}">{{ $make->name }}</option>
                                    @endforeach
                                </select>
                                @error('orderItems.' . $index . '.vehicle_make_id')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Model *</label>
                                <select
                                    wire:model="orderItems.{{ $index }}.vehicle_model_id"
                                    required
                                    @if(empty($item['vehicle_make_id'])) disabled @endif
                                    class="kibo-input w-full px-4 py-3 rounded-lg border border-gray-300"
                                >
                                    <option value="">@if(!empty($item['vehicle_make_id'])) Select Model @else Select Make First @endif</option>
                                    @if(isset($item['available_models']))
                                        @foreach($item['available_models'] as $model)
                                            <option value="{{ $model['id'] }}">{{ $model['name'] }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('orderItems.' . $index . '.vehicle_model_id')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        @endif
                        
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Part Number *</label>
                                <input
                                    type="text"
                                    wire:model="orderItems.{{ $index }}.part_number"
                                    required
                                    class="kibo-input w-full px-4 py-3 rounded-lg border border-gray-300"
                                    placeholder="e.g., SP-2024-001"
                                >
                                @error('orderItems.' . $index . '.part_number')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Part Name *</label>
                                <input
                                    type="text"
                                    wire:model="orderItems.{{ $index }}.part_name"
                                    required
                                    class="kibo-input w-full px-4 py-3 rounded-lg border border-gray-300"
                                    placeholder="e.g., Gear Assembly"
                                >
                                @error('orderItems.' . $index . '.part_name')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                                <input
                                    type="number"
                                    wire:model="orderItems.{{ $index }}.quantity"
                                    required
                                    min="1"
                                    class="kibo-input w-full px-4 py-3 rounded-lg border border-gray-300"
                                    placeholder="1"
                                >
                                @error('orderItems.' . $index . '.quantity')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea
                                wire:model="orderItems.{{ $index }}.notes"
                                rows="2"
                                class="kibo-input w-full px-4 py-3 rounded-lg border border-gray-300 resize-none"
                                placeholder="Any special requirements or notes..."
                            ></textarea>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Delivery Information -->
        <div class="space-y-5">
            <h2 class="text-lg font-semibold text-gray-900 pb-2 border-b">Delivery Information</h2>
            
            <div class="grid md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Address *</label>
                    <textarea
                        wire:model="deliveryAddress"
                        required
                        rows="3"
                        class="kibo-input w-full px-4 py-3 rounded-lg border border-gray-300 resize-none"
                        placeholder="Enter your complete delivery address..."
                    ></textarea>
                    @error('deliveryAddress')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input
                        type="text"
                        wire:model="deliveryCity"
                        class="kibo-input w-full px-4 py-3 rounded-lg border border-gray-300"
                        placeholder="e.g., Dar es Salaam"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Region</label>
                    <input
                        type="text"
                        wire:model="deliveryRegion"
                        class="kibo-input w-full px-4 py-3 rounded-lg border border-gray-300"
                        placeholder="e.g., Dar es Salaam"
                    >
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600">
                    <strong>💡 Tip:</strong> Orders are typically processed within 24-48 hours. You'll receive a confirmation email with tracking details once your order is ready.
                </p>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="kibo-btn w-full text-white font-semibold py-4 px-6 rounded-xl transition-colors duration-200 text-lg disabled:opacity-50"
            >
                <span wire:loading.remove>Submit Order</span>
                <span wire:loading>Processing...</span>
            </button>
        </div>
    </form>
    
    <style>
        /* Kibo Brand Color #009866 */
        .kibo-input:focus {
            outline: none;
            border-color: #009866;
            ring: 2px solid rgba(0, 152, 102, 0.2);
            box-shadow: 0 0 0 3px rgba(0, 152, 102, 0.1);
        }
        
        .kibo-btn {
            background-color: #009866;
        }
        
        .kibo-btn:hover:not(:disabled) {
            background-color: #007a52;
        }
    </style>
</div>
