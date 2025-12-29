<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Lender</h1>
            <p class="mt-1 text-sm text-gray-500">Update lender entity information</p>
        </div>
        <button wire:click="cancel" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to List
        </button>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Form -->
    <form wire:submit.prevent="update">
        <div class="space-y-6">
            <!-- Entity Information Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Entity Information</h2>
                    <p class="text-sm text-gray-500 mt-1">Basic information about the lender organization</p>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Lender Name -->
                        <div class="col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Lender Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" wire:model="name" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('name') border-red-500 @enderror"
                                placeholder="Enter lender organization name">
                            @error('name') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" wire:model="email" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('email') border-red-500 @enderror"
                                placeholder="contact@lender.com">
                            @error('email') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number
                            </label>
                            <input type="text" id="phone" wire:model="phone" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                placeholder="+254 700 000000">
                            @error('phone') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Registration Number -->
                        <div>
                            <label for="registration_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Registration Number
                            </label>
                            <input type="text" id="registration_number" wire:model="registration_number" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('registration_number') border-red-500 @enderror"
                                placeholder="REG-123456">
                            @error('registration_number') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tax ID -->
                        <div>
                            <label for="tax_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Tax ID / KRA PIN
                            </label>
                            <input type="text" id="tax_id" wire:model="tax_id" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                placeholder="A000000000X">
                            @error('tax_id') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Website -->
                        <div class="col-span-2">
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                                Website
                            </label>
                            <input type="url" id="website" wire:model="website" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('website') border-red-500 @enderror"
                                placeholder="https://www.lender.com">
                            @error('website') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Physical Address
                            </label>
                            <textarea id="address" wire:model="address" rows="3"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                placeholder="Enter full physical address"></textarea>
                            @error('address') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                City
                            </label>
                            <input type="text" id="city" wire:model="city" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                placeholder="Nairobi">
                            @error('city') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- State/County -->
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                                State / County
                            </label>
                            <input type="text" id="state" wire:model="state" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                placeholder="Nairobi County">
                            @error('state') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Zip Code -->
                        <div>
                            <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">
                                Zip / Postal Code
                            </label>
                            <input type="text" id="zip_code" wire:model="zip_code" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                placeholder="00100">
                            @error('zip_code') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                Country <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="country" wire:model="country" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('country') border-red-500 @enderror"
                                placeholder="Kenya">
                            @error('country') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <!-- Description -->
                        <div class="col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description / Notes
                            </label>
                            <textarea id="description" wire:model="description" rows="4"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                placeholder="Additional information about the lender..."></textarea>
                            @error('description') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Primary User Information Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Primary User Account</h2>
                    <p class="text-sm text-gray-500 mt-1">Create the first user account for this lender</p>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User Name -->
                        <div>
                            <label for="user_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="user_name" wire:model="user_name" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('user_name') border-red-500 @enderror"
                                placeholder="John Doe">
                            @error('user_name') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- User Email -->
                        <div>
                            <label for="user_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="user_email" wire:model="user_email" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('user_email') border-red-500 @enderror"
                                placeholder="john@lender.com">
                            @error('user_email') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Info Box -->
                        <div class="col-span-2">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Auto-Generated Password</h3>
                                        <p class="mt-1 text-sm text-blue-700">
                                            A secure password will be automatically generated and sent to the user's email address. 
                                            The user will be able to change their password after first login.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-4 bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4">
                <button type="button" wire:click="cancel" 
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit" 
                    class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Lender
                </button>
            </div>
        </div>
    </form>
</div>
