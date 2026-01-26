<!-- Lease Terms -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Lease Terms</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Currency -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Currency *</label>
            <select wire:model="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <option value="TZS">TZS - Tanzanian Shilling</option>
                <option value="USD">USD - US Dollar</option>
                <option value="EUR">EUR - Euro</option>
                <option value="GBP">GBP - British Pound</option>
                <option value="KES">KES - Kenyan Shilling</option>
            </select>
            @error('currency') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Payment *</label>
            <input type="number" step="0.01" wire:model="monthly_payment" placeholder="0.00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            @error('monthly_payment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Lease Term * (months)</label>
            <select wire:model="lease_term_months" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <option value="12">12 months</option>
                <option value="24">24 months</option>
                <option value="36">36 months</option>
                <option value="48">48 months</option>
                <option value="60">60 months</option>
            </select>
            @error('lease_term_months') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Down Payment</label>
            <input type="number" step="0.01" wire:model="down_payment" placeholder="0.00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            @error('down_payment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Security Deposit</label>
            <input type="number" step="0.01" wire:model="security_deposit" placeholder="0.00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            @error('security_deposit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mileage Limit (per year)</label>
            <input type="number" wire:model="mileage_limit_per_year" placeholder="15000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            @error('mileage_limit_per_year') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Excess Mileage Charge (per km)</label>
            <input type="number" step="0.01" wire:model="excess_mileage_charge" placeholder="0.25" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            @error('excess_mileage_charge') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>
</div>

<!-- Additional Costs & Services -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Additional Costs & Services</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Acquisition Fee</label>
            <input type="number" step="0.01" wire:model="acquisition_fee" placeholder="0.00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Disposition Fee</label>
            <input type="number" step="0.01" wire:model="disposition_fee" placeholder="0.00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>

        <div class="flex items-center">
            <input type="checkbox" wire:model="maintenance_included" id="maintenance_included" class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
            <label for="maintenance_included" class="ml-2 text-sm font-medium text-gray-700">Maintenance Included</label>
        </div>

        <div class="flex items-center">
            <input type="checkbox" wire:model="insurance_included" id="insurance_included" class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
            <label for="insurance_included" class="ml-2 text-sm font-medium text-gray-700">Insurance Included</label>
        </div>
    </div>

    <div class="mt-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Included Services</label>
        <div class="flex gap-2 mb-2">
            <input type="text" wire:model="new_service" placeholder="e.g., Roadside Assistance" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            <button type="button" wire:click="addService" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Add</button>
        </div>
        @if(count($included_services) > 0)
            <div class="flex flex-wrap gap-2">
                @foreach($included_services as $index => $service)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                        {{ $service }}
                        <button type="button" wire:click="removeService({{ $index }})" class="ml-2 text-green-600 hover:text-green-900">×</button>
                    </span>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Eligibility Requirements -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Eligibility Requirements</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Min. Credit Score</label>
            <input type="number" wire:model="min_credit_score" placeholder="e.g., 650" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Min. Monthly Income</label>
            <input type="number" step="0.01" wire:model="min_monthly_income" placeholder="e.g., 3000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Min. Age *</label>
            <input type="number" wire:model="min_age" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>

        <div class="md:col-span-3">
            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Requirements</label>
            <textarea wire:model="additional_requirements" rows="2" placeholder="Any additional eligibility requirements..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
        </div>
    </div>
</div>

<!-- Purchase Options -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Purchase & Termination Options</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="flex items-center md:col-span-3">
            <input type="checkbox" wire:model="purchase_option_available" id="purchase_option" class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
            <label for="purchase_option" class="ml-2 text-sm font-medium text-gray-700">Purchase Option Available</label>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Residual Value (Buy-out Price)</label>
            <input type="number" step="0.01" wire:model="residual_value" placeholder="0.00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Early Termination Fee</label>
            <input type="number" step="0.01" wire:model="early_termination_fee" placeholder="0.00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>
    </div>
</div>

<!-- Status & Visibility -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Status & Visibility</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
            <select wire:model="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="reserved">Reserved</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Priority (0-100)</label>
            <input type="number" wire:model="priority" placeholder="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>

        <div class="flex items-center">
            <input type="checkbox" wire:model="is_featured" id="is_featured" class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
            <label for="is_featured" class="ml-2 text-sm font-medium text-gray-700">Featured Lease</label>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Available From</label>
            <input type="date" wire:model="available_from" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Available Until</label>
            <input type="date" wire:model="available_until" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>
    </div>
</div>

<!-- Notes -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Internal Notes</h2>
    <textarea wire:model="notes" rows="3" placeholder="Internal notes (not visible to customers)..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
</div>

