<div>
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('admin.lending-criteria.index') }}" class="flex items-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Lending Criteria
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">
            {{ $criteriaId ? 'Edit' : 'Add New' }} Lending Criteria
        </h1>
        <p class="mt-2 text-gray-600">Define lending rules and requirements for a lender</p>
    </div>

    @if(session()->has('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session()->has('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    <form wire:submit="save">
        <div class="space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Basic Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Lender -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Lender <span class="text-red-500">*</span>
                        </label>
                        @if($userIsAdmin)
                        <select wire:model="entity_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Select Lender...</option>
                            @foreach($entities as $entity)
                            <option value="{{ $entity->id }}">{{ $entity->name }}</option>
                            @endforeach
                        </select>
                        @error('entity_id') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        @else
                        @if($userEntityName)
                        <input type="text" value="{{ $userEntityName }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed" readonly>
                        <input type="hidden" wire:model="entity_id">
                        <p class="text-xs text-gray-500 mt-1">Lending criteria will be created for your lender entity.</p>
                        @else
                        <p class="text-red-500 text-sm mt-1">You are not associated with any lender entity. Cannot create lending criteria.</p>
                        @endif
                        @error('entity_id') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        @endif
                    </div>

                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Criteria Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="name" placeholder="e.g., Standard Auto Loan, Premium Vehicle Financing" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('name') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea wire:model="description" rows="3" placeholder="Brief description of this lending criteria..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"></textarea>
                        @error('description') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Vehicle Requirements -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Vehicle Requirements</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Year Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Vehicle Year</label>
                        <input type="number" wire:model="min_vehicle_year" placeholder="e.g., 2015" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('min_vehicle_year') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Vehicle Year</label>
                        <input type="number" wire:model="max_vehicle_year" placeholder="e.g., 2024" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('max_vehicle_year') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Vehicle Price (£)</label>
                        <input type="number" step="0.01" wire:model="min_vehicle_price" placeholder="e.g., 5000" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('min_vehicle_price') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Vehicle Price (£)</label>
                        <input type="number" step="0.01" wire:model="max_vehicle_price" placeholder="e.g., 50000" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('max_vehicle_price') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Max Mileage -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Mileage (miles)</label>
                        <input type="number" wire:model="max_mileage" placeholder="e.g., 100000" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('max_mileage') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Allowed Fuel Types -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Allowed Fuel Types</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach(['petrol' => 'Petrol', 'diesel' => 'Diesel', 'electric' => 'Electric', 'hybrid' => 'Hybrid'] as $value => $label)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="allowed_fuel_types" value="{{ $value }}" 
                                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Allowed Transmissions -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Allowed Transmissions</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach(['manual' => 'Manual', 'automatic' => 'Automatic'] as $value => $label)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="allowed_transmissions" value="{{ $value }}" 
                                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Allowed Body Types -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Allowed Body Types</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach(['sedan' => 'Sedan', 'suv' => 'SUV', 'truck' => 'Truck', 'hatchback' => 'Hatchback', 'coupe' => 'Coupe', 'van' => 'Van', 'wagon' => 'Wagon', 'convertible' => 'Convertible'] as $value => $label)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="allowed_body_types" value="{{ $value }}" 
                                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Allowed Conditions -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Allowed Vehicle Conditions</label>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach(['new' => 'New', 'used' => 'Used', 'certified' => 'Certified Pre-Owned'] as $value => $label)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="allowed_conditions" value="{{ $value }}" 
                                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Terms -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Loan Terms</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Loan Amount Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Loan Amount (£)</label>
                        <input type="number" step="0.01" wire:model="min_loan_amount" placeholder="e.g., 3000" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('min_loan_amount') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Loan Amount (£)</label>
                        <input type="number" step="0.01" wire:model="max_loan_amount" placeholder="e.g., 50000" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('max_loan_amount') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Interest Rate -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Annual Interest Rate (%) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" wire:model="interest_rate" placeholder="e.g., 5.99" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('interest_rate') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Down Payment -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Down Payment (%) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" wire:model="down_payment_percentage" placeholder="e.g., 20" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('down_payment_percentage') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Loan Term Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Minimum Loan Term (months) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="min_loan_term_months" placeholder="e.g., 12" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('min_loan_term_months') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Maximum Loan Term (months) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="max_loan_term_months" placeholder="e.g., 84" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('max_loan_term_months') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Processing Fee & Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Processing Fee (£)</label>
                        <input type="number" step="0.01" wire:model="processing_fee" placeholder="e.g., 250" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('processing_fee') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Processing Time (days)</label>
                        <input type="number" wire:model="processing_time_days" placeholder="e.g., 7" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('processing_time_days') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Borrower Requirements -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Borrower Requirements</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Credit Score</label>
                        <input type="number" wire:model="min_credit_score" placeholder="e.g., 650" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('min_credit_score') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Monthly Income (£)</label>
                        <input type="number" step="0.01" wire:model="min_monthly_income" placeholder="e.g., 2000" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('min_monthly_income') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Debt-to-Income Ratio (%)</label>
                        <input type="number" step="0.01" wire:model="max_debt_to_income_ratio" placeholder="e.g., 40" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('max_debt_to_income_ratio') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Employment (months)</label>
                        <input type="number" wire:model="min_employment_months" placeholder="e.g., 6" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('min_employment_months') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2 space-y-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="require_collateral" 
                                class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="text-sm font-medium text-gray-700">Require Collateral</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="require_guarantor" 
                                class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="text-sm font-medium text-gray-700">Require Guarantor</span>
                        </label>
                    </div>

                    <!-- Required Documents -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Required Documents</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach(['id' => 'ID/Passport', 'proof_of_income' => 'Proof of Income', 'bank_statements' => 'Bank Statements', 'employment_letter' => 'Employment Letter', 'utility_bill' => 'Utility Bill', 'credit_report' => 'Credit Report'] as $value => $label)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="required_documents" value="{{ $value }}" 
                                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Additional Requirements -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Additional Requirements</label>
                        <textarea wire:model="additional_requirements" rows="4" 
                            placeholder="Any additional requirements or notes..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"></textarea>
                        @error('additional_requirements') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Status & Priority -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Status & Priority</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_active" 
                                class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="text-sm font-medium text-gray-700">Active (Available for customers)</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority Order</label>
                        <input type="number" wire:model="priority" placeholder="0" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Higher number = higher priority in listings</p>
                        @error('priority') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.lending-criteria.index') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $criteriaId ? 'Update' : 'Create' }} Criteria
                </button>
            </div>
        </div>
    </form>
</div>

