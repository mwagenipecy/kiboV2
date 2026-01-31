<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.lending-criteria.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $criteria->name }}</h1>
                    <p class="mt-1 text-gray-600">View lending criteria details</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $criteria->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $criteria->is_active ? 'Active' : 'Inactive' }}
                </span>
                <a href="{{ route('admin.lending-criteria.edit', $criteria->id) }}" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Lender</label>
                        <p class="mt-1 text-base text-gray-900">{{ $criteria->entity->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Priority</label>
                        <p class="mt-1 text-base text-gray-900">{{ $criteria->priority }}</p>
                    </div>
                    @if($criteria->description)
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-600">Description</label>
                        <p class="mt-1 text-base text-gray-700">{{ $criteria->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Vehicle Requirements -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Vehicle Requirements</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Vehicle Year Range</label>
                        <p class="mt-1 text-base text-gray-900">
                            {{ $criteria->min_vehicle_year ?? 'Any' }} - {{ $criteria->max_vehicle_year ?? 'Current' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Price Range</label>
                        <p class="mt-1 text-base text-gray-900">
                            TZS {{ $criteria->min_vehicle_price ? number_format($criteria->min_vehicle_price) : '0' }} - 
                            TZS {{ $criteria->max_vehicle_price ? number_format($criteria->max_vehicle_price) : '∞' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Maximum Mileage</label>
                        <p class="mt-1 text-base text-gray-900">
                            {{ $criteria->max_mileage ? number_format($criteria->max_mileage) . ' km' : 'No limit' }}
                        </p>
                    </div>
                    @if($criteria->allowed_fuel_types)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Allowed Fuel Types</label>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @foreach($criteria->allowed_fuel_types as $fuel)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">{{ ucfirst($fuel) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($criteria->allowed_transmissions)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Allowed Transmissions</label>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @foreach($criteria->allowed_transmissions as $transmission)
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded">{{ ucfirst($transmission) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($criteria->allowed_body_types)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Allowed Body Types</label>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @foreach($criteria->allowed_body_types as $body)
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">{{ ucfirst($body) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($criteria->allowed_conditions)
                    <div>
                        <label class="text-sm font-medium text-gray-600">Allowed Conditions</label>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @foreach($criteria->allowed_conditions as $condition)
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded">{{ ucfirst($condition) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Loan Terms -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Loan Terms</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Interest Rate</label>
                        <p class="mt-1 text-2xl font-bold text-green-600">{{ number_format($criteria->interest_rate, 2) }}%</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Loan Amount Range</label>
                        <p class="mt-1 text-base text-gray-900">
                            TZS {{ $criteria->min_loan_amount ? number_format($criteria->min_loan_amount) : '0' }} - 
                            TZS {{ $criteria->max_loan_amount ? number_format($criteria->max_loan_amount) : '∞' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Loan Term</label>
                        <p class="mt-1 text-base text-gray-900">
                            {{ $criteria->min_loan_term_months }}-{{ $criteria->max_loan_term_months }} months
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Down Payment</label>
                        <p class="mt-1 text-2xl font-bold text-blue-600">{{ number_format($criteria->down_payment_percentage, 0) }}%</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Processing Fee</label>
                        <p class="mt-1 text-base text-gray-900">
                            TZS {{ $criteria->processing_fee ? number_format($criteria->processing_fee) : '0' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Processing Time</label>
                        <p class="mt-1 text-base text-gray-900">
                            {{ $criteria->processing_time_days ?? 'N/A' }} days
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Borrower Requirements -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Borrower Requirements</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Minimum Credit Score</label>
                        <p class="mt-1 text-base text-gray-900">{{ $criteria->min_credit_score ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Minimum Monthly Income</label>
                        <p class="mt-1 text-base text-gray-900">
                            TZS {{ $criteria->min_monthly_income ? number_format($criteria->min_monthly_income) : 'Not specified' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Max Debt-to-Income Ratio</label>
                        <p class="mt-1 text-base text-gray-900">{{ $criteria->max_debt_to_income_ratio ?? 'Not specified' }}%</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Minimum Employment</label>
                        <p class="mt-1 text-base text-gray-900">{{ $criteria->min_employment_months ?? 'Not specified' }} months</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Collateral Required</label>
                        <p class="mt-1">
                            <span class="px-2 py-1 text-xs font-medium rounded {{ $criteria->require_collateral ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $criteria->require_collateral ? 'Yes' : 'No' }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Guarantor Required</label>
                        <p class="mt-1">
                            <span class="px-2 py-1 text-xs font-medium rounded {{ $criteria->require_guarantor ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $criteria->require_guarantor ? 'Yes' : 'No' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Required Documents -->
        @if($criteria->required_documents)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Required Documents</h2>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-2">
                    @foreach($criteria->required_documents as $document)
                    <span class="px-3 py-2 bg-gray-100 text-gray-800 text-sm font-medium rounded-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ ucwords(str_replace('_', ' ', $document)) }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Custom Requirements -->
        @if($criteria->custom_requirements)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Additional Requirements</h2>
            </div>
            <div class="p-6">
                <p class="text-gray-700 whitespace-pre-wrap">{{ $criteria->custom_requirements }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
