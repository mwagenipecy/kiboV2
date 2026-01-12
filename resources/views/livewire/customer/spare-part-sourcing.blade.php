<div class="max-w-7xl mx-auto">
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <form wire:submit.prevent="submitOrders" class="space-y-6">
        {{-- Step 1: Orders Summary Table --}}
        @if(count($orders) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">1</span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Orders Summary</h2>
                            <p class="text-sm text-gray-600">Review and manage your spare part orders</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="addNewOrder"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2 shadow-sm"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Order
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Vehicle</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Condition</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Part Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Description</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Images</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders as $index => $order)
                                @php
                                    $make = $vehicleMakes->firstWhere('id', $order['vehicle_make_id'] ?? null);
                                    $model = null;
                                    if ($order['vehicle_model_id'] ?? null) {
                                        $model = \App\Models\VehicleModel::find($order['vehicle_model_id']);
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors {{ $currentOrderIndex === $index ? 'bg-green-50 border-l-4 border-green-500' : '' }}">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-sm font-semibold text-gray-900">{{ $index + 1 }}</span>
                                            @if($currentOrderIndex === $index)
                                                <span class="ml-2 px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded-full">Editing</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            @if($make)
                                                {{ $make->name }}
                                            @else
                                                <span class="text-red-500">Not selected</span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            @if($model)
                                                {{ $model->name }}
                                            @else
                                                <span class="text-red-500">Not selected</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($order['condition'] ?? 'new') === 'new' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }} capitalize">
                                            {{ $order['condition'] ?? 'new' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs">
                                            @if($order['part_name'])
                                                {{ $order['part_name'] }}
                                            @else
                                                <span class="text-gray-400">Not specified</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm text-gray-500 max-w-xs">
                                            @if($order['description'] ?? null)
                                                <p class="truncate" title="{{ $order['description'] }}">{{ $order['description'] }}</p>
                                            @else
                                                <span class="text-gray-400">No description</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        @if(count($order['images'] ?? []) > 0)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ count($order['images']) }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">No images</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button
                                                type="button"
                                                wire:click="selectOrder({{ $index }})"
                                                class="px-3 py-1.5 text-xs font-medium text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition-colors"
                                            >
                                                Edit
                                            </button>
                                            @if(count($orders) > 1)
                                                <button
                                                    type="button"
                                                    wire:click="removeOrder({{ $index }})"
                                                    class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors"
                                                    onclick="return confirm('Are you sure you want to remove this order?')"
                                                >
                                                    Remove
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- Step 2: Add/Edit Order Form --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">2</span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">
                                @if(count($orders) > 0 && $currentOrderIndex >= 0)
                                    Edit Order {{ $currentOrderIndex + 1 }}
                                @else
                                    Add New Order
                                @endif
                            </h2>
                            <p class="text-sm text-gray-600">Fill in the details for the spare part you need</p>
                        </div>
                    </div>
                    @if(count($orders) > 0)
                        <button
                            type="button"
                            wire:click="addNewOrder"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Another
                        </button>
                    @endif
                </div>
            </div>

            <div class="p-6 space-y-6">
                {{-- Vehicle Information Section --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0h-.01M15 17a2 2 0 104 0m-4 0h-.01"></path>
                        </svg>
                        Vehicle Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Make <span class="text-red-500">*</span></label>
                            <select
                                wire:model.live="vehicleMakeId"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                required
                            >
                                <option value="">Select Make</option>
                                @foreach($vehicleMakes as $make)
                                    <option value="{{ $make->id }}">{{ $make->name }}</option>
                                @endforeach
                            </select>
                            @error('orders.' . $currentOrderIndex . '.vehicle_make_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Model <span class="text-red-500">*</span></label>
                            <select
                                wire:model="vehicleModelId"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors {{ !$vehicleMakeId ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                required
                                {{ !$vehicleMakeId ? 'disabled' : '' }}
                            >
                                <option value="">{{ $vehicleMakeId ? 'Select Model' : 'Select Make First' }}</option>
                                @foreach($vehicleModels as $model)
                                    <option value="{{ $model->id }}">{{ $model->name }}</option>
                                @endforeach
                            </select>
                            @error('orders.' . $currentOrderIndex . '.vehicle_model_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Condition <span class="text-red-500">*</span></label>
                            <select
                                wire:model="condition"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                required
                            >
                                <option value="new">New</option>
                                <option value="used">Used</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Part Name <span class="text-gray-400 text-xs">(Optional)</span></label>
                            <input
                                type="text"
                                wire:model="partName"
                                placeholder="e.g., Brake Pad, Engine Oil Filter, Headlight"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                            >
                        </div>
                    </div>
                </div>

                {{-- Part Details Section --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Part Details
                    </h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea
                            wire:model="description"
                            rows="4"
                            placeholder="Describe the spare part you need in detail. Include any specific requirements, part numbers, or other relevant information..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none"
                        ></textarea>
                        <p class="mt-1 text-xs text-gray-500">Provide as much detail as possible to help us find the right part for you</p>
                    </div>
                </div>

                {{-- Images Section --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Images <span class="text-gray-400 text-xs font-normal">(Optional)</span>
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <input
                                type="file"
                                wire:model="tempImages"
                                multiple
                                accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-colors"
                            >
                            <p class="mt-2 text-xs text-gray-500">Upload images of the part you need (max 5MB per image). Multiple images allowed.</p>
                        </div>

                        @if(count($images) > 0)
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($images as $index => $image)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Part image" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                        <button
                                            type="button"
                                            wire:click="removeImage({{ $index }})"
                                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1.5 hover:bg-red-600 transition-colors opacity-0 group-hover:opacity-100"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Step 3: Customer & Delivery Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-sm">3</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Customer & Delivery Information</h2>
                        <p class="text-sm text-gray-600">Your contact details and delivery address</p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-6">
                {{-- Customer Information --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Customer Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                wire:model="customerName"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                required
                            >
                            @error('customerName')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                            <input
                                type="email"
                                wire:model="customerEmail"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                required
                            >
                            @error('customerEmail')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone <span class="text-red-500">*</span></label>
                            <input
                                type="tel"
                                wire:model="customerPhone"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                required
                            >
                            @error('customerPhone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Delivery Information --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Delivery Information
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Address <span class="text-red-500">*</span></label>
                            <textarea
                                wire:model="deliveryAddress"
                                rows="3"
                                placeholder="Enter your complete delivery address..."
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none"
                                required
                            ></textarea>
                            @error('deliveryAddress')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                <input
                                    type="text"
                                    wire:model="deliveryCity"
                                    placeholder="e.g., Dar es Salaam"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Region</label>
                                <input
                                    type="text"
                                    wire:model="deliveryRegion"
                                    placeholder="e.g., Dar es Salaam"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                                <input
                                    type="text"
                                    wire:model="deliveryPostalCode"
                                    placeholder="Optional"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                >
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            @if($deliveryLatitude && $deliveryLongitude)
                                <div class="flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-green-700">Location captured</span>
                                </div>
                                <button
                                    type="button"
                                    wire:click="$set('deliveryLatitude', ''); $set('deliveryLongitude', '')"
                                    class="text-sm text-gray-600 hover:text-gray-900 underline"
                                >
                                    Clear
                                </button>
                            @else
                                <button
                                    type="button"
                                    wire:click="getCurrentLocation"
                                    class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors shadow-sm"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Use My Location
                                </button>
                                <span class="text-xs text-gray-500">Automatically capture your location for faster delivery</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Contact Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Name <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                wire:model="contactName"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                required
                            >
                            @error('contactName')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone <span class="text-red-500">*</span></label>
                            <input
                                type="tel"
                                wire:model="contactPhone"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                required
                            >
                            @error('contactPhone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email <span class="text-gray-400 text-xs">(Optional)</span></label>
                            <input
                                type="email"
                                wire:model="contactEmail"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                            >
                            @error('contactEmail')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-600">
                    <p class="font-medium">Ready to submit?</p>
                    <p class="text-xs mt-1">Review all information before submitting your order(s)</p>
                </div>
                <div class="flex gap-3 w-full sm:w-auto">
                    <button
                        type="button"
                        wire:click="addNewOrder"
                        class="flex-1 sm:flex-none px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                    >
                        Add Another Order
                    </button>
                    <button
                        type="submit"
                        class="flex-1 sm:flex-none px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold shadow-sm"
                    >
                        Submit {{ count($orders) > 1 ? count($orders) . ' Orders' : 'Order' }}
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- Location Modal --}}
    @if($showLocationModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click="showLocationModal = false">
            <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-xl" wire:click.stop>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Get Your Location</h3>
                <p class="text-gray-600 mb-4">We need your location for delivery. Please allow location access in your browser.</p>
                <div class="flex gap-3">
                    <button
                        wire:click="showLocationModal = false"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- JavaScript for Geolocation --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('request-location', () => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            @this.setLocation(
                                position.coords.latitude,
                                position.coords.longitude
                            );
                        },
                        function(error) {
                            alert('Unable to get your location. Please enable location access in your browser settings.');
                            @this.showLocationModal = false;
                        },
                        { enableHighAccuracy: true, timeout: 10000 }
                    );
                } else {
                    alert('Geolocation is not supported by your browser.');
                    @this.showLocationModal = false;
                }
            });
        });
    </script>
</div>
