<div>
    @if($show)
    <!-- Modal Overlay -->
    <div class="fixed inset-0 bg-black/50 z-[110] flex items-center justify-center p-4" wire:click="close" aria-modal="true" role="dialog">
        <!-- Modal Content -->
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
            <!-- Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Request Valuation Report</h2>
                    @if($vehicle)
                    <p class="text-sm text-gray-600 mt-1">{{ $vehicle->make->name ?? '' }} {{ $vehicle->model->name ?? '' }} {{ $vehicle->year }}</p>
                    @endif
                </div>
                <button wire:click="close" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6">
                @if($existingReport)
                    <!-- Existing Report Notice -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-1">Existing Valuation Report</h4>
                                <p class="text-sm text-blue-800 mb-2">You already have a {{ $existingReport->status->label() }} valuation report for this vehicle (Order #{{ $existingReport->order_number }}).</p>
                                <a href="{{ route('my-orders') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 underline">
                                    View in Dashboard →
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Service Info -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-900">Service Fee</h3>
                        <span class="text-2xl font-bold" style="color: #009866;">
                            {{ $currencySymbol }} {{ number_format($urgency === 'urgent' ? $urgentPrice : $standardPrice, 0) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Professional vehicle valuation report includes:</p>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" style="color: #009866;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Current market value assessment
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" style="color: #009866;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Comprehensive condition analysis
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" style="color: #009866;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Market comparison report
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" style="color: #009866;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            PDF report delivery
                        </li>
                    </ul>
                </div>

                @if(session()->has('error'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 text-red-800">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Form -->
                <form wire:submit.prevent="submit" class="space-y-6">
                    <!-- Purpose -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Purpose of Valuation <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="purpose" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent" style="--tw-ring-color: #009866;">
                            <option value="">Select purpose...</option>
                            <option value="purchase">Considering Purchase</option>
                            <option value="sale">Planning to Sell</option>
                            <option value="insurance">Insurance Assessment</option>
                            <option value="trade_in">Trade-In Value</option>
                            <option value="general">General Information</option>
                        </select>
                        @error('purpose') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Urgency -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Urgency <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors {{ $urgency === 'standard' ? 'bg-green-50' : 'border-gray-200 hover:border-gray-300' }}" style="{{ $urgency === 'standard' ? 'border-color: #009866;' : '' }}">
                                <input type="radio" wire:model.live="urgency" value="standard" class="sr-only">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">Standard</div>
                                    <div class="text-sm text-gray-600">3-5 business days</div>
                                    <div class="text-xs font-medium mt-1" style="color: #009866;">{{ $currencySymbol }} {{ number_format($standardPrice, 0) }}</div>
                                </div>
                                @if($urgency === 'standard')
                                <svg class="w-5 h-5" style="color: #009866;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                @endif
                            </label>
                            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors {{ $urgency === 'urgent' ? 'bg-green-50' : 'border-gray-200 hover:border-gray-300' }}" style="{{ $urgency === 'urgent' ? 'border-color: #009866;' : '' }}">
                                <input type="radio" wire:model.live="urgency" value="urgent" class="sr-only">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">Urgent</div>
                                    <div class="text-sm text-gray-600">24-48 hours</div>
                                    <div class="text-xs text-orange-600 font-medium mt-1">{{ $currencySymbol }} {{ number_format($urgentPrice, 0) }}</div>
                                </div>
                                @if($urgency === 'urgent')
                                <svg class="w-5 h-5" style="color: #009866;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                @endif
                            </label>
                        </div>
                        @error('urgency') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Additional Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                        <textarea wire:model="customerNotes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none" placeholder="Any specific details or questions..."></textarea>
                        @error('customerNotes') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">{{ strlen($customerNotes) }}/500 characters</p>
                    </div>

                    <!-- Terms -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-600">
                            By submitting this request, you agree to pay the service fee and understand that the valuation report is for informational purposes only. The report will be delivered to your registered email address within the specified timeframe.
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3">
                        <button type="button" wire:click="close" class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-6 py-3 text-white font-medium rounded-lg transition-colors" style="background-color: #009866;">
                            Request Report - {{ $currencySymbol }} {{ number_format($urgency === 'urgent' ? $urgentPrice : $standardPrice, 0) }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
