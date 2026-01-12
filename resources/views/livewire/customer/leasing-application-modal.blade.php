<div>
    @if($show && $lease)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('show') }" x-show="show" style="display: none;">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black/50 bg-opacity-50 transition-opacity" wire:click="close"></div>

        <!-- Modal panel - Slide in from right -->
        <div class="fixed right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl overflow-y-auto">
            <!-- Header -->
            <div class="sticky top-0 bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-white">Apply for Lease</h3>
                        <p class="text-sm text-green-100 mt-1">{{ $lease->vehicle_make }} {{ $lease->vehicle_model }} {{ $lease->vehicle_year }}</p>
                    </div>
                    <button wire:click="close" class="text-white hover:text-green-200 transition-colors w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Lease Summary -->
                <div class="mt-4 bg-white/20 rounded-lg p-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <div class="text-green-100 text-xs">Monthly Payment</div>
                            <div class="text-white font-bold text-lg">${{ number_format($lease->monthly_payment, 0) }}/mo</div>
                        </div>
                        <div>
                            <div class="text-green-100 text-xs">Lease Term</div>
                            <div class="text-white font-bold text-lg">{{ $lease->lease_term_months }} months</div>
                        </div>
                        <div>
                            <div class="text-green-100 text-xs">Down Payment</div>
                            <div class="text-white font-semibold">${{ number_format($lease->down_payment, 0) }}</div>
                        </div>
                        <div>
                            <div class="text-green-100 text-xs">Mileage Limit</div>
                            <div class="text-white font-semibold">{{ number_format($lease->mileage_limit_per_year ?? 0) }} km/yr</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <form wire:submit.prevent="submit" class="px-6 py-6 space-y-6">
                <!-- Personal Information Section -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Personal Information
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" wire:model="full_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('full_name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" wire:model="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" wire:model="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('phone') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                            <input type="date" wire:model="date_of_birth" max="{{ date('Y-m-d', strtotime('-18 years')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('date_of_birth') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                            <input type="text" wire:model="address" placeholder="Street address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('address') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                            <input type="text" wire:model="city" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('city') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                            <input type="text" wire:model="postal_code" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('postal_code') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Financial Information Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Financial Information
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Income *</label>
                            <input type="number" step="0.01" wire:model="monthly_income" placeholder="0.00" min="{{ $lease->min_monthly_income ?? 0 }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('monthly_income') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            @if($lease->min_monthly_income)
                            <p class="text-xs text-gray-500 mt-1">Minimum required: ${{ number_format($lease->min_monthly_income, 0) }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Employment Status *</label>
                            <select wire:model="employment_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">-- Select --</option>
                                <option value="employed">Employed</option>
                                <option value="self_employed">Self Employed</option>
                                <option value="unemployed">Unemployed</option>
                                <option value="retired">Retired</option>
                                <option value="student">Student</option>
                            </select>
                            @error('employment_status') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Employer Name *</label>
                            <input type="text" wire:model="employer_name" placeholder="Company name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('employer_name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Employment Duration (months)</label>
                            <input type="number" wire:model="employment_months" placeholder="0" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('employment_months') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Credit Score</label>
                            <input type="number" wire:model="credit_score" placeholder="300-850" min="300" max="850" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('credit_score') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            @if($lease->min_credit_score)
                            <p class="text-xs text-gray-500 mt-1">Minimum required: {{ $lease->min_credit_score }}</p>
                            @endif
                        </div>

                        <div class="flex items-center pt-6">
                            <input type="checkbox" wire:model="current_lease" id="current_lease" class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                            <label for="current_lease" class="ml-2 text-sm font-medium text-gray-700">I currently have an active lease</label>
                        </div>
                    </div>
                </div>

                <!-- Documents Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Required Documents
                    </h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ID Document (NIDA/Passport) *</label>
                            <input type="file" wire:model="id_document" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                            @error('id_document') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            @if($id_document)
                            <p class="text-xs text-green-600 mt-1">✓ File selected: {{ $id_document->getClientOriginalName() }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Proof of Income (Payslip/Bank Statement) *</label>
                            <input type="file" wire:model="proof_of_income" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                            @error('proof_of_income') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            @if($proof_of_income)
                            <p class="text-xs text-green-600 mt-1">✓ File selected: {{ $proof_of_income->getClientOriginalName() }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Proof of Address (Utility Bill/Bank Statement) *</label>
                            <input type="file" wire:model="proof_of_address" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                            @error('proof_of_address') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            @if($proof_of_address)
                            <p class="text-xs text-green-600 mt-1">✓ File selected: {{ $proof_of_address->getClientOriginalName() }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Driving License *</label>
                            <input type="file" wire:model="driving_license" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                            @error('driving_license') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            @if($driving_license)
                            <p class="text-xs text-green-600 mt-1">✓ File selected: {{ $driving_license->getClientOriginalName() }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Documents (Optional)</label>
                            <input type="file" wire:model="additional_documents" multiple accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                            @if(count($additional_documents) > 0)
                            <p class="text-xs text-green-600 mt-1">✓ {{ count($additional_documents) }} file(s) selected</p>
                            @endif
                        </div>

                        <p class="text-xs text-gray-500 mt-2">Maximum file size: 5MB per file. Accepted formats: PDF, JPG, PNG</p>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="border-t border-gray-200 pt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                    <textarea wire:model="notes" rows="3" placeholder="Any additional information you'd like to provide..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                    @error('notes') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Agreements -->
                <div class="border-t border-gray-200 pt-6 space-y-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="agreeToTerms" class="w-5 h-5 mt-0.5 text-green-600 rounded focus:ring-green-500">
                        <span class="text-sm text-gray-700">I agree to the <a href="#" class="text-green-600 hover:underline">Terms and Conditions</a> and <a href="#" class="text-green-600 hover:underline">Privacy Policy</a> *</span>
                    </label>
                    @error('agreeToTerms') <span class="text-red-500 text-sm block">{{ $message }}</span> @enderror

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="agreeToCreditCheck" class="w-5 h-5 mt-0.5 text-green-600 rounded focus:ring-green-500">
                        <span class="text-sm text-gray-700">I authorize a credit check to be performed as part of this application *</span>
                    </label>
                    @error('agreeToCreditCheck') <span class="text-red-500 text-sm block">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Button -->
                <div class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4 -mx-6 flex items-center justify-between gap-4">
                    <button type="button" wire:click="close" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" wire:loading.attr="disabled" class="px-8 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove>Submit Application</span>
                        <span wire:loading>Processing...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

