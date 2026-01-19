<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <!-- Animated background pattern -->
    <div class="fixed inset-0 opacity-5 pointer-events-none">
        <div class="absolute inset-0" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(0,0,0,.02) 35px, rgba(0,0,0,.02) 70px);"></div>
    </div>

    <div class="max-w-5xl mx-auto relative z-10">
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg shadow-sm animate-fadeIn">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="text-center mb-12 animate-fadeIn">
            <h1 class="text-5xl font-black text-gray-900 mb-3 tracking-tight">
                SPARE PARTS
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-green-600 text-6xl mt-1">
                    ORDER SYSTEM
                </span>
            </h1>
            <p class="text-gray-600 text-lg font-medium">Fast. Simple. Reliable.</p>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-200 overflow-hidden">
            <!-- Order Type Selector -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 border-b border-gray-200">
                <label class="block text-sm font-bold text-gray-700 mb-3 uppercase tracking-wider">
                    Order Type
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <button
                        type="button"
                        wire:click="$set('orderType', 'single'); $dispatch('reset-order-items')"
                        class="relative overflow-hidden px-6 py-4 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105 {{ $orderType === 'single' ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg shadow-green-500/40' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 border-2 border-gray-300' }}"
                    >
                        <div class="relative z-10 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Single Order
                        </div>
                    </button>
                    <button
                        type="button"
                        wire:click="$set('orderType', 'bulk')"
                        class="relative overflow-hidden px-6 py-4 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105 {{ $orderType === 'bulk' ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg shadow-green-500/40' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 border-2 border-gray-300' }}"
                    >
                        <div class="relative z-10 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Bulk Order
                        </div>
                    </button>
                </div>
            </div>

            <form wire:submit.prevent="submitOrders" class="p-8">
                <!-- Customer Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-gray-900 mb-6 flex items-center gap-3">
                        <div class="h-1 w-12 bg-gradient-to-r from-green-500 to-green-600 rounded-full"></div>
                        Customer Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                Full Name <span class="text-red-400">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="customerName"
                                required
                                class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all outline-none font-medium"
                                placeholder="John Doe"
                            >
                            @error('customerName')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                Email Address <span class="text-red-400">*</span>
                            </label>
                            <input
                                type="email"
                                wire:model="customerEmail"
                                required
                                class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all outline-none font-medium"
                                placeholder="john@company.com"
                            >
                            @error('customerEmail')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                Phone Number <span class="text-red-400">*</span>
                            </label>
                            <input
                                type="tel"
                                wire:model="customerPhone"
                                required
                                class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all outline-none font-medium"
                                placeholder="+255 123 456 789"
                            >
                            @error('customerPhone')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                Company Name
                            </label>
                            <input
                                type="text"
                                wire:model="company"
                                class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all outline-none font-medium"
                                placeholder="Optional"
                            >
                        </div>
                    </div>
                </div>

                <!-- Vehicle Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-gray-900 mb-6 flex items-center gap-3">
                        <div class="h-1 w-12 bg-gradient-to-r from-green-500 to-green-600 rounded-full"></div>
                        Vehicle Information
                    </h2>
                    <div class="bg-gray-50 border-2 border-gray-200 rounded-2xl p-6 hover:border-green-500/30 transition-all duration-300">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700 font-medium">Select your vehicle details to ensure correct part compatibility</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="group">
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                    Make <span class="text-red-400">*</span>
                                </label>
                                <select
                                    wire:model.live="vehicleMakeId"
                                    required
                                    class="w-full px-4 py-3.5 bg-white border-2 border-gray-300 rounded-xl text-gray-900 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all outline-none font-medium appearance-none cursor-pointer"
                                    style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%2310b981\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'%3E%3C/path%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1.5em 1.5em;"
                                >
                                    <option value="">Select Make</option>
                                    @foreach($vehicleMakes as $make)
                                        <option value="{{ $make->id }}">{{ $make->name }}</option>
                                    @endforeach
                                </select>
                                @error('vehicleMakeId')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="group">
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                    Model <span class="text-red-400">*</span>
                                </label>
                                <select
                                    wire:model="vehicleModelId"
                                    required
                                    {{ !$vehicleMakeId ? 'disabled' : '' }}
                                    class="w-full px-4 py-3.5 bg-white border-2 border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all outline-none font-medium appearance-none cursor-pointer {{ !$vehicleMakeId ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%2310b981\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'%3E%3C/path%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1.5em 1.5em;"
                                >
                                    <option value="">{{ $vehicleMakeId ? 'Select Model' : 'Select Make First' }}</option>
                                    @foreach($vehicleModels as $model)
                                        <option value="{{ $model->id }}">{{ $model->name }}</option>
                                    @endforeach
                                </select>
                                @error('vehicleModelId')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="group">
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                    Year
                                </label>
                                <input
                                    type="number"
                                    wire:model="vehicleYear"
                                    min="1900"
                                    max="{{ date('Y') + 1 }}"
                                    class="w-full px-4 py-3.5 bg-white border-2 border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all outline-none font-medium"
                                    placeholder="e.g., 2020"
                                >
                            </div>
                            <div class="group">
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                    VIN (Optional)
                                </label>
                                <input
                                    type="text"
                                    wire:model="vehicleVin"
                                    maxlength="17"
                                    class="w-full px-4 py-3.5 bg-white border-2 border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all outline-none font-medium uppercase"
                                    placeholder="17-character VIN"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-black text-gray-900 flex items-center gap-3">
                            <div class="h-1 w-12 bg-gradient-to-r from-green-500 to-green-600 rounded-full"></div>
                            Order Items
                            <span class="text-sm font-bold text-gray-600 ml-2">
                                ({{ count($orderItems) }} {{ count($orderItems) === 1 ? 'item' : 'items' }})
                            </span>
                        </h2>
                        @if($orderType === 'bulk')
                            <button
                                type="button"
                                wire:click="addOrderItem"
                                class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl font-bold flex items-center gap-2 hover:shadow-lg hover:shadow-emerald-500/30 transition-all duration-300 transform hover:scale-105"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Item
                            </button>
                        @endif
                    </div>

                    <div class="space-y-5">
                        @foreach($orderItems as $index => $item)
                            <div class="bg-gray-50 border-2 border-gray-200 rounded-2xl p-6 hover:border-green-500/30 transition-all duration-300 group">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white font-black text-lg shadow-lg">
                                            {{ $index + 1 }}
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">Part {{ $index + 1 }}</h3>
                                    </div>
                                    @if($orderType === 'bulk' && count($orderItems) > 1)
                                        <button
                                            type="button"
                                            wire:click="removeOrderItem({{ $item['id'] }})"
                                            class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-all"
                                            title="Remove item"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                            Part Number <span class="text-red-400">*</span>
                                        </label>
                                        <div class="relative">
                                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            <input
                                                type="text"
                                                wire:model="orderItems.{{ $index }}.part_number"
                                                required
                                                class="w-full pl-11 pr-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all outline-none font-medium"
                                                placeholder="e.g., SP-2024-001"
                                            >
                                        </div>
                                        @error('orderItems.' . $index . '.part_number')
                                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                            Part Name <span class="text-red-400">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            wire:model="orderItems.{{ $index }}.part_name"
                                            required
                                                class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all outline-none font-medium"
                                            placeholder="e.g., Gear Assembly"
                                        >
                                        @error('orderItems.' . $index . '.part_name')
                                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                            Quantity <span class="text-red-400">*</span>
                                        </label>
                                        <input
                                            type="number"
                                            wire:model="orderItems.{{ $index }}.quantity"
                                            required
                                            min="1"
                                                class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all outline-none font-medium"
                                            placeholder="1"
                                        >
                                        @error('orderItems.' . $index . '.quantity')
                                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                        Additional Notes
                                    </label>
                                    <textarea
                                        wire:model="orderItems.{{ $index }}.notes"
                                        rows="2"
                                        class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all outline-none font-medium resize-none"
                                        placeholder="Any special requirements or notes..."
                                    ></textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-gray-900 mb-6 flex items-center gap-3">
                        <div class="h-1 w-12 bg-gradient-to-r from-green-500 to-green-600 rounded-full"></div>
                        Delivery Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                Delivery Address <span class="text-red-400">*</span>
                            </label>
                            <textarea
                                wire:model="deliveryAddress"
                                required
                                rows="3"
                                class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all outline-none font-medium resize-none"
                                placeholder="Enter your complete delivery address..."
                            ></textarea>
                            @error('deliveryAddress')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                City
                            </label>
                            <input
                                type="text"
                                wire:model="deliveryCity"
                                class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all outline-none font-medium"
                                placeholder="e.g., Dar es Salaam"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                Region
                            </label>
                            <input
                                type="text"
                                wire:model="deliveryRegion"
                                class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all outline-none font-medium"
                                placeholder="e.g., Dar es Salaam"
                            >
                        </div>
                    </div>
                </div>

                <!-- Info Banner -->
                <div class="mb-8 bg-gradient-to-r from-blue-500/10 to-cyan-500/10 border-2 border-blue-500/30 rounded-2xl p-5">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="font-bold text-blue-300 mb-1">Order Processing Time</h4>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Orders are typically processed within 24-48 hours. You'll receive a confirmation email with tracking details once your order is ready.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full py-5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-2xl font-black text-xl hover:shadow-2xl hover:shadow-green-500/40 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none uppercase tracking-wide"
                >
                    <span wire:loading.remove class="flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Submit Order
                    </span>
                    <span wire:loading class="flex items-center justify-center gap-3">
                        <svg class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-600 text-sm">
            <p class="font-medium">Need help? Contact support at <span class="text-green-600 font-bold">support@kiboauto.com</span></p>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out;
        }
    </style>

</div>
