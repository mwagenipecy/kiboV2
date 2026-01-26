@php
    $currency = $lease->currency ?? 'TZS';
@endphp

<div>
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.leasing.index') }}" class="hover:text-green-600">Leasing</a>
            <span>/</span>
            <span class="text-gray-900">View Details</span>
        </div>
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">{{ $lease->lease_title }}</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.leasing.edit', $lease->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    Edit Lease
                </a>
                <a href="{{ route('admin.leasing.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Vehicle Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Vehicle Information</h2>
                
                <div class="flex gap-4">
                    @if($lease->image_front)
                        <img src="{{ asset('storage/' . $lease->image_front) }}" alt="{{ $lease->vehicle_title }}" class="w-48 h-32 object-cover rounded-lg">
                    @else
                        <div class="w-48 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $lease->vehicle_title }}</h3>
                        <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                            <div><span class="text-gray-600">Year:</span> <span class="font-medium">{{ $lease->vehicle_year }}</span></div>
                            <div><span class="text-gray-600">Make:</span> <span class="font-medium">{{ $lease->vehicle_make }}</span></div>
                            <div><span class="text-gray-600">Model:</span> <span class="font-medium">{{ $lease->vehicle_model }}</span></div>
                            <div><span class="text-gray-600">Mileage:</span> <span class="font-medium">{{ number_format($lease->mileage) }} km</span></div>
                            <div><span class="text-gray-600">Fuel Type:</span> <span class="font-medium">{{ ucfirst($lease->fuel_type) }}</span></div>
                            <div><span class="text-gray-600">Condition:</span> <span class="font-medium">{{ ucfirst($lease->condition) }}</span></div>
                        </div>
                    </div>
                </div>
                
                @if($lease->vehicle_description)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-700">{{ $lease->vehicle_description }}</p>
                </div>
                @endif
            </div>

            <!-- Lease Terms -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Lease Terms</h2>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Monthly Payment</p>
                        <p class="text-2xl font-bold text-green-600">{{ $currency }} {{ number_format($lease->monthly_payment, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Lease Term</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $lease->lease_term_months }} months</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Down Payment</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $currency }} {{ number_format($lease->down_payment, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Security Deposit</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $currency }} {{ number_format($lease->security_deposit, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Mileage Limit/Year</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($lease->mileage_limit_per_year) }} km</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Excess Mileage</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $currency }} {{ number_format($lease->excess_mileage_charge, 2) }}/km</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 {{ $lease->maintenance_included ? 'text-green-600' : 'text-gray-400' }} mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="{{ $lease->maintenance_included ? 'text-gray-900' : 'text-gray-400' }}">Maintenance {{ $lease->maintenance_included ? 'Included' : 'Not Included' }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 {{ $lease->insurance_included ? 'text-green-600' : 'text-gray-400' }} mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="{{ $lease->insurance_included ? 'text-gray-900' : 'text-gray-400' }}">Insurance {{ $lease->insurance_included ? 'Included' : 'Not Included' }}</span>
                        </div>
                    </div>
                </div>

                @if($lease->lease_description)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-700">{{ $lease->lease_description }}</p>
                </div>
                @endif
            </div>

            <!-- Additional Costs -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Additional Costs</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Acquisition Fee</span>
                        <span class="font-semibold text-gray-900">{{ $currency }} {{ number_format($lease->acquisition_fee, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Disposition Fee</span>
                        <span class="font-semibold text-gray-900">{{ $currency }} {{ number_format($lease->disposition_fee, 2) }}</span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-900 font-medium">Total Upfront Cost</span>
                        <span class="text-xl font-bold text-green-600">{{ $currency }} {{ number_format($lease->total_upfront_cost, 2) }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Down payment + Security deposit + Acquisition fee</p>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-900 font-medium">Total Lease Cost</span>
                        <span class="text-xl font-bold text-gray-900">{{ $currency }} {{ number_format($lease->total_lease_cost, 2) }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Total payments over {{ $lease->lease_term_months }} months + upfront costs</p>
                </div>
            </div>

            <!-- Included Services -->
            @if($lease->included_services && count($lease->included_services) > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Included Services</h2>
                <ul class="space-y-2">
                    @foreach($lease->included_services as $service)
                        <li class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ $service }}
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Eligibility Requirements -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Eligibility Requirements</h2>
                
                <div class="grid grid-cols-3 gap-4 text-sm">
                    @if($lease->min_credit_score)
                    <div>
                        <p class="text-gray-600">Min. Credit Score</p>
                        <p class="font-semibold text-gray-900">{{ $lease->min_credit_score }}</p>
                    </div>
                    @endif
                    
                    @if($lease->min_monthly_income)
                    <div>
                        <p class="text-gray-600">Min. Monthly Income</p>
                        <p class="font-semibold text-gray-900">{{ $currency }} {{ number_format($lease->min_monthly_income, 2) }}</p>
                    </div>
                    @endif
                    
                    <div>
                        <p class="text-gray-600">Min. Age</p>
                        <p class="font-semibold text-gray-900">{{ $lease->min_age }} years</p>
                    </div>
                </div>

                @if($lease->additional_requirements)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-700">{{ $lease->additional_requirements }}</p>
                </div>
                @endif
            </div>

            <!-- Purchase Options -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Purchase & Termination Options</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 {{ $lease->purchase_option_available ? 'text-green-600' : 'text-gray-400' }} mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-900">Purchase Option {{ $lease->purchase_option_available ? 'Available' : 'Not Available' }}</span>
                    </div>

                    @if($lease->residual_value)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Residual Value (Buy-out Price)</span>
                        <span class="font-semibold text-gray-900">{{ $currency }} {{ number_format($lease->residual_value, 2) }}</span>
                    </div>
                    @endif

                    @if($lease->early_termination_fee)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Early Termination Fee</span>
                        <span class="font-semibold text-gray-900">{{ $currency }} {{ number_format($lease->early_termination_fee, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Current Status</p>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $lease->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $lease->status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $lease->status === 'reserved' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                            {{ ucfirst($lease->status) }}
                        </span>
                    </div>

                    @if($lease->is_featured)
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                            ⭐ Featured
                        </span>
                    </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-600">Priority</p>
                        <p class="font-semibold text-gray-900">{{ $lease->priority }}</p>
                    </div>

                    @if($lease->available_from)
                    <div>
                        <p class="text-sm text-gray-600">Available From</p>
                        <p class="font-semibold text-gray-900">{{ $lease->available_from->format('M d, Y') }}</p>
                    </div>
                    @endif

                    @if($lease->available_until)
                    <div>
                        <p class="text-sm text-gray-600">Available Until</p>
                        <p class="font-semibold text-gray-900">{{ $lease->available_until->format('M d, Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Entity Card -->
            @if($lease->entity)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Dealer/Entity</h3>
                <div>
                    <p class="font-semibold text-gray-900">{{ $lease->entity->name }}</p>
                    @if($lease->entity->email)
                    <p class="text-sm text-gray-600 mt-1">{{ $lease->entity->email }}</p>
                    @endif
                    @if($lease->entity->phone)
                    <p class="text-sm text-gray-600">{{ $lease->entity->phone }}</p>
                    @endif
                </div>
            </div>
            @endif

            <!-- Metadata -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Metadata</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <p class="text-gray-600">Created</p>
                        <p class="text-gray-900">{{ $lease->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Last Updated</p>
                        <p class="text-gray-900">{{ $lease->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Internal Notes -->
            @if($lease->notes)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-yellow-900 mb-2">Internal Notes</h3>
                <p class="text-sm text-yellow-800">{{ $lease->notes }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
