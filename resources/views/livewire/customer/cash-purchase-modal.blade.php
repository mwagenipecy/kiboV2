<div>
    <!-- Debug: Component is loaded -->
    @if($showModal && $vehicle)
    <!-- Modal Overlay -->
    <div class="fixed inset-0 bg-black/60 z-[110] flex items-center justify-center p-4" wire:click="closeModal" aria-modal="true" role="dialog">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
            <!-- Modal Header -->
            <div class="sticky top-0 bg-gradient-to-r from-green-600 to-green-700 px-6 py-5 rounded-t-2xl flex items-center justify-between z-10">
                <div class="flex items-center gap-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <h2 class="text-2xl font-bold text-white">Buy with Cash</h2>
                </div>
                <button wire:click="closeModal" class="text-white hover:text-green-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Vehicle Summary -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 mb-6 border border-green-200">
                    <h3 class="font-semibold text-green-900 mb-3 text-lg">Vehicle Details</h3>
                    <div class="flex gap-4">
                        @if($vehicle->image_front)
                        <div class="w-24 h-24 bg-white rounded-lg overflow-hidden flex-shrink-0 shadow-sm">
                            <img src="{{ asset('storage/' . $vehicle->image_front) }}" alt="Vehicle" class="w-full h-full object-cover">
                        </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900 mb-1">
                                {{ $vehicle->year }} {{ $vehicle->make->name ?? '' }} {{ $vehicle->model->name ?? '' }}
                            </h4>
                            @if($vehicle->variant)
                            <p class="text-sm text-gray-700 mb-2">{{ $vehicle->variant }}</p>
                            @endif
                            <div class="text-2xl font-bold text-green-700">
                                £{{ number_format($vehicle->price, 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Information -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">How it works:</p>
                            <ul class="space-y-1">
                                <li>• Submit your purchase request</li>
                                <li>• Our team will review and verify availability</li>
                                <li>• Vehicle will be reserved for you upon approval</li>
                                <li>• You'll be contacted to arrange payment and delivery</li>
                                <li>• If order is rejected, vehicle returns to available status</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form wire:submit="submitOrder" class="space-y-6">
                    <!-- Customer Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Additional Notes (Optional)
                        </label>
                        <textarea 
                            wire:model="customerNotes" 
                            rows="4" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none" 
                            placeholder="Any special requests or questions about the vehicle..."></textarea>
                        @error('customerNotes') 
                        <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> 
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">{{ strlen($customerNotes) }}/500 characters</p>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="border-t border-gray-200 pt-6">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                wire:model="agreedTerms" 
                                class="mt-1 w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="text-sm text-gray-700">
                                I agree to the <a href="#" class="text-green-600 hover:text-green-700 font-medium">terms and conditions</a> and understand that:
                                <ul class="mt-2 space-y-1 text-gray-600">
                                    <li>• This is a binding purchase request</li>
                                    <li>• The vehicle will be reserved upon approval</li>
                                    <li>• Payment must be completed within the agreed timeframe</li>
                                    <li>• Cancellation may incur fees as per our policy</li>
                                </ul>
                            </span>
                        </label>
                        @error('agreedTerms') 
                        <span class="text-sm text-red-600 mt-2 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-4">
                        <button 
                            type="button" 
                            wire:click="closeModal" 
                            class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="flex-1 px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Submit Purchase Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

