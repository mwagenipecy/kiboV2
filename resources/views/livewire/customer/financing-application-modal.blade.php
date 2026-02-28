<div>
    @if($show)
    <div class="fixed inset-0 z-[110] overflow-y-auto" x-data="{ show: @entangle('show') }" x-show="show" style="display: none;" aria-modal="true" role="dialog">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black/50 bg-opacity-50 transition-opacity" wire:click="close"></div>

        <!-- Modal panel -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-600 to-green-800 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h3 class="text-xl font-bold text-white">Apply for Financing</h3>
                                <p class="text-sm text-green-100">{{ $vehicle ? $vehicle->full_name : '' }}</p>
                            </div>
                        </div>
                        <button wire:click="close" class="text-white hover:text-green-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Progress indicator -->
                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                    <div class="flex items-center justify-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full {{ $step >= 1 ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-semibold">
                                1
                            </div>
                            <span class="text-sm font-medium {{ $step >= 1 ? 'text-green-600' : 'text-gray-500' }}">Select Lender</span>
                        </div>
                        <div class="w-16 h-0.5 {{ $step >= 2 ? 'bg-green-600' : 'bg-gray-300' }}"></div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full {{ $step >= 2 ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-semibold">
                                2
                            </div>
                            <span class="text-sm font-medium {{ $step >= 2 ? 'text-green-600' : 'text-gray-500' }}">Application</span>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                    @if($step === 1)
                        <!-- Step 1: Select Lender -->
                        @if(count($matchingCriteria) > 0)
                            <div class="space-y-4">
                                <p class="text-gray-600 mb-4">We found <span class="font-semibold text-green-600">{{ count($matchingCriteria) }}</span> lender(s) with financing options that match this vehicle:</p>
                                
                                @foreach($matchingCriteria as $criteria)
                                <div wire:click="selectLender({{ $criteria->id }})" class="border-2 border-gray-200 rounded-lg p-5 hover:border-green-500 hover:bg-green-50 cursor-pointer transition-all">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h4 class="text-lg font-bold text-gray-900">{{ $criteria->entity->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $criteria->name }}</p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-green-600">{{ number_format($criteria->interest_rate, 2) }}%</div>
                                            <div class="text-xs text-gray-500">Interest Rate</div>
                                        </div>
                                    </div>
                                    
                                    @if($criteria->description)
                                    <p class="text-sm text-gray-700 mb-3">{{ $criteria->description }}</p>
                                    @endif
                                    
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                        <div class="bg-white rounded p-2">
                                            <div class="text-gray-600 text-xs">Down Payment</div>
                                            <div class="font-semibold text-gray-900">{{ number_format($criteria->down_payment_percentage, 0) }}%</div>
                                        </div>
                                        <div class="bg-white rounded p-2">
                                            <div class="text-gray-600 text-xs">Loan Term</div>
                                            <div class="font-semibold text-gray-900">{{ $criteria->min_loan_term_months }}-{{ $criteria->max_loan_term_months }} mo</div>
                                        </div>
                                        <div class="bg-white rounded p-2">
                                            <div class="text-gray-600 text-xs">Processing</div>
                                            <div class="font-semibold text-gray-900">{{ $criteria->processing_time_days }} days</div>
                                        </div>
                                        <div class="bg-white rounded p-2">
                                            <div class="text-gray-600 text-xs">Processing Fee</div>
                                            <div class="font-semibold text-gray-900">TZS {{ number_format($criteria->processing_fee, 0) }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 flex items-center gap-2 text-sm">
                                        @if($criteria->require_collateral)
                                        <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded text-xs">Collateral Required</span>
                                        @endif
                                        @if($criteria->require_guarantor)
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">Guarantor Required</span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Financing Options Available</h3>
                                <p class="text-gray-600">This vehicle doesn't match any lender's criteria at the moment.</p>
                            </div>
                        @endif
                    @elseif($step === 2 && $selectedCriteria)
                        <!-- Step 2: Application Form -->
                        <form wire:submit.prevent="submit" class="space-y-6">
                            <!-- Selected Lender Info -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-green-900">{{ $selectedCriteria->entity->name }}</h4>
                                        <p class="text-sm text-green-700">{{ $selectedCriteria->name }}</p>
                                    </div>
                                    <button type="button" wire:click="backToLenderSelection" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                        Change Lender
                                    </button>
                                </div>
                            </div>

                            <!-- Loan Details -->
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">Loan Details</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Price</label>
                                        <input type="text" value="TZS {{ number_format($vehicle->price, 2) }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Loan Amount <span class="text-red-500">*</span></label>
                                        <input type="number" wire:model="loanAmount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        @error('loanAmount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Down Payment ({{ number_format($selectedCriteria->down_payment_percentage, 0) }}%) <span class="text-red-500">*</span></label>
                                        <input type="number" wire:model="downPaymentAmount" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        @error('downPaymentAmount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Loan Term (months) <span class="text-red-500">*</span></label>
                                        <select wire:model="loanTermMonths" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                            @for($i = $selectedCriteria->min_loan_term_months; $i <= $selectedCriteria->max_loan_term_months; $i += 6)
                                                <option value="{{ $i }}">{{ $i }} months ({{ round($i/12, 1) }} years)</option>
                                            @endfor
                                        </select>
                                        @error('loanTermMonths') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Financial Information -->
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">Your Financial Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Income <span class="text-red-500">*</span></label>
                                        <input type="number" wire:model="monthlyIncome" step="0.01" placeholder="Enter your monthly income" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        @error('monthlyIncome') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Employment Duration (months) <span class="text-red-500">*</span></label>
                                        <input type="number" wire:model="employmentMonths" placeholder="How long have you been employed?" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        @error('employmentMonths') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Credit Score (Optional)</label>
                                        <input type="number" wire:model="creditScore" placeholder="300-850" min="300" max="850" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        @error('creditScore') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Required Documents -->
                            @if($selectedCriteria && $selectedCriteria->required_documents && count($selectedCriteria->required_documents) > 0)
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">Required Documents</h4>
                                <p class="text-sm text-gray-600 mb-4">Please upload the following documents. Accepted formats: PDF, JPG, JPEG, PNG (Max 5MB each)</p>
                                <div class="space-y-4">
                                    @foreach($selectedCriteria->required_documents as $docType)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            {{ ucwords(str_replace('_', ' ', $docType)) }} <span class="text-red-500">*</span>
                                        </label>
                                        <input 
                                            type="file" 
                                            wire:model="documents.{{ $docType }}" 
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                                        @error('documents.' . $docType) 
                                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                                        @enderror
                                        @if(isset($documents[$docType]) && $documents[$docType])
                                            <p class="text-xs text-green-600 mt-1">
                                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                File selected: {{ $documents[$docType]->getClientOriginalName() }}
                                            </p>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Additional Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Additional Notes (Optional)</label>
                                <textarea wire:model="notes" rows="3" placeholder="Any additional information you'd like to share..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" wire:model="agreeToTerms" class="mt-1 w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                    <span class="text-sm text-gray-700">
                                        I agree to the terms and conditions and authorize the lender to process my financing application and perform a credit check if necessary. <span class="text-red-500">*</span>
                                    </span>
                                </label>
                                @error('agreeToTerms') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <button type="button" wire:click="backToLenderSelection" class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                    Back
                                </button>
                                <button type="submit" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Submit Application
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
