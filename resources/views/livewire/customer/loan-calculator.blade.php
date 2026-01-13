<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-emerald-50">
    <!-- Hero Section -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-green-700 to-emerald-600 text-white py-8 px-8 rounded-2xl shadow-lg">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold mb-2">Vehicle Loan Calculator</h1>
                        <p class="text-green-100 text-sm md:text-base max-w-xl">
                            Calculate your monthly payments, compare loan terms, and plan your vehicle purchase with confidence.
                        </p>
                    </div>
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-5 py-3">
                        <svg class="w-10 h-10 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <div class="text-sm">
                            <div class="font-semibold">Free Calculator</div>
                            <div class="text-green-200 text-xs">Download PDF Report</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Calculator Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Loan Details Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Loan Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Vehicle Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Price ({{ $currency }})</label>
                            <input 
                                type="number" 
                                wire:model.live.debounce.300ms="vehiclePrice"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                placeholder="Enter vehicle price"
                            >
                            <input 
                                type="range" 
                                wire:model.live="vehiclePrice"
                                min="500000" 
                                max="100000000" 
                                step="100000"
                                class="w-full mt-2 accent-green-600"
                            >
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>500K</span>
                                <span>100M</span>
                            </div>
                        </div>

                        <!-- Down Payment -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Down Payment ({{ $currency }})</label>
                            <input 
                                type="number" 
                                wire:model.live.debounce.300ms="downPayment"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                placeholder="Enter down payment"
                            >
                            <input 
                                type="range" 
                                wire:model.live="downPayment"
                                min="0" 
                                max="{{ $vehiclePrice * 0.8 }}" 
                                step="50000"
                                class="w-full mt-2 accent-green-600"
                            >
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>0%</span>
                                <span>{{ number_format($downPayment / $vehiclePrice * 100, 0) }}%</span>
                                <span>80%</span>
                            </div>
                        </div>

                        <!-- Loan Term -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Loan Term (Months)</label>
                            <select 
                                wire:model.live="loanTerm"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                            >
                                <option value="12">12 months (1 year)</option>
                                <option value="24">24 months (2 years)</option>
                                <option value="36">36 months (3 years)</option>
                                <option value="48">48 months (4 years)</option>
                                <option value="60">60 months (5 years)</option>
                                <option value="72">72 months (6 years)</option>
                                <option value="84">84 months (7 years)</option>
                            </select>
                        </div>

                        <!-- Interest Rate -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Annual Interest Rate (%)</label>
                            <input 
                                type="number" 
                                wire:model.live.debounce.300ms="interestRate"
                                step="0.1"
                                min="0"
                                max="50"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                placeholder="Enter interest rate"
                            >
                            <input 
                                type="range" 
                                wire:model.live="interestRate"
                                min="5" 
                                max="30" 
                                step="0.5"
                                class="w-full mt-2 accent-green-600"
                            >
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>5%</span>
                                <span>30%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Costs Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Additional Costs</h2>
                    </div>

                    <div class="space-y-4">
                        <!-- Insurance -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <input 
                                    type="checkbox" 
                                    wire:model.live="includeInsurance"
                                    id="includeInsurance"
                                    class="w-5 h-5 rounded text-green-600 focus:ring-green-500"
                                >
                                <label for="includeInsurance" class="font-medium text-gray-700">Vehicle Insurance</label>
                            </div>
                            @if($includeInsurance)
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="number" 
                                        wire:model.live.debounce.300ms="insuranceRate"
                                        step="0.5"
                                        min="0"
                                        max="20"
                                        class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500"
                                    >
                                    <span class="text-sm text-gray-500">% / year</span>
                                </div>
                            @endif
                        </div>

                        <!-- Registration Fee -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <input 
                                    type="checkbox" 
                                    wire:model.live="includeRegistration"
                                    id="includeRegistration"
                                    class="w-5 h-5 rounded text-green-600 focus:ring-green-500"
                                >
                                <label for="includeRegistration" class="font-medium text-gray-700">Registration Fee</label>
                            </div>
                            @if($includeRegistration)
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="number" 
                                        wire:model.live.debounce.300ms="registrationFee"
                                        min="0"
                                        class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500"
                                    >
                                    <span class="text-sm text-gray-500">{{ $currency }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Processing Fee -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <input 
                                    type="checkbox" 
                                    wire:model.live="includeProcessingFee"
                                    id="includeProcessingFee"
                                    class="w-5 h-5 rounded text-green-600 focus:ring-green-500"
                                >
                                <label for="includeProcessingFee" class="font-medium text-gray-700">Loan Processing Fee</label>
                            </div>
                            @if($includeProcessingFee)
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="number" 
                                        wire:model.live.debounce.300ms="processingFeeRate"
                                        step="0.5"
                                        min="0"
                                        max="10"
                                        class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500"
                                    >
                                    <span class="text-sm text-gray-500">% of loan</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Term Comparison -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">Compare Loan Terms</h2>
                        </div>
                        <button 
                            wire:click="toggleComparison"
                            class="text-green-600 hover:text-green-700 font-medium text-sm"
                        >
                            {{ $showComparison ? 'Hide' : 'Show' }} Comparison
                        </button>
                    </div>

                    @if($showComparison)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Term</th>
                                        <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600">Monthly</th>
                                        <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600">Total Interest</th>
                                        <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600">Total Payment</th>
                                        <th class="py-3 px-4"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($comparisonResults as $result)
                                        <tr class="border-b border-gray-100 {{ $result['selected'] ? 'bg-green-50' : 'hover:bg-gray-50' }} transition-colors">
                                            <td class="py-3 px-4">
                                                <span class="font-medium text-gray-900">{{ $result['term'] }} months</span>
                                                <span class="text-sm text-gray-500">({{ $result['termYears'] }} yrs)</span>
                                            </td>
                                            <td class="py-3 px-4 text-right font-semibold text-gray-900">
                                                {{ number_format($result['monthlyPayment'], 0) }}
                                            </td>
                                            <td class="py-3 px-4 text-right text-red-600">
                                                {{ number_format($result['totalInterest'], 0) }}
                                            </td>
                                            <td class="py-3 px-4 text-right text-gray-700">
                                                {{ number_format($result['totalPayment'], 0) }}
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                @if($result['selected'])
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Selected
                                                    </span>
                                                @else
                                                    <button 
                                                        wire:click="selectTerm({{ $result['term'] }})"
                                                        class="text-green-600 hover:text-green-700 text-sm font-medium"
                                                    >
                                                        Select
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="flex flex-wrap gap-2">
                            @foreach($comparisonResults as $result)
                                <button 
                                    wire:click="selectTerm({{ $result['term'] }})"
                                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $result['selected'] ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                                >
                                    {{ $result['termYears'] }} years
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Amortization Schedule -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">Amortization Schedule</h2>
                        </div>
                        <button 
                            wire:click="toggleAmortization"
                            class="text-green-600 hover:text-green-700 font-medium text-sm"
                        >
                            {{ $showAmortization ? 'Hide' : 'Show' }} Schedule
                        </button>
                    </div>

                    @if($showAmortization && count($amortizationSchedule) > 0)
                        <div class="overflow-x-auto max-h-96 overflow-y-auto">
                            <table class="w-full">
                                <thead class="sticky top-0 bg-white">
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Month</th>
                                        <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600">Payment</th>
                                        <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600">Principal</th>
                                        <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600">Interest</th>
                                        <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($amortizationSchedule as $row)
                                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                            <td class="py-2 px-4 text-gray-700">{{ $row['month'] }}</td>
                                            <td class="py-2 px-4 text-right text-gray-900 font-medium">{{ number_format($row['payment'], 0) }}</td>
                                            <td class="py-2 px-4 text-right text-green-600">{{ number_format($row['principal'], 0) }}</td>
                                            <td class="py-2 px-4 text-right text-red-600">{{ number_format($row['interest'], 0) }}</td>
                                            <td class="py-2 px-4 text-right text-gray-700">{{ number_format($row['balance'], 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Click "Show Schedule" to view the full payment breakdown for each month.</p>
                    @endif
                </div>
            </div>

            <!-- Results Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    <!-- Main Results Card -->
                    <div class="bg-gradient-to-br from-green-600 to-emerald-700 rounded-2xl shadow-xl p-6 text-white">
                        <h3 class="text-lg font-semibold mb-4 opacity-90">Monthly Payment</h3>
                        <div class="text-4xl font-bold mb-2">
                            {{ $currency }} {{ number_format($monthlyPayment, 0) }}
                        </div>
                        <p class="text-green-100 text-sm">per month for {{ $loanTerm }} months</p>

                        @if($monthlyInsurance > 0)
                            <div class="mt-4 pt-4 border-t border-green-500/30">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-green-100">+ Insurance</span>
                                    <span class="font-medium">{{ number_format($monthlyInsurance, 0) }}/mo</span>
                                </div>
                                <div class="flex justify-between items-center mt-2 text-lg font-semibold">
                                    <span>Total Monthly</span>
                                    <span>{{ number_format($monthlyPayment + $monthlyInsurance, 0) }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Breakdown Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Loan Breakdown</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Vehicle Price</span>
                                <span class="font-semibold text-gray-900">{{ number_format($vehiclePrice, 0) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Down Payment</span>
                                <span class="font-semibold text-green-600">- {{ number_format($downPayment, 0) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Loan Amount</span>
                                <span class="font-semibold text-gray-900">{{ number_format($loanAmount, 0) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Total Interest</span>
                                <span class="font-semibold text-red-600">+ {{ number_format($totalInterest, 0) }}</span>
                            </div>
                            @if($processingFee > 0)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Processing Fee</span>
                                    <span class="font-semibold text-orange-600">+ {{ number_format($processingFee, 0) }}</span>
                                </div>
                            @endif
                            @if($includeRegistration && $registrationFee > 0)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Registration Fee</span>
                                    <span class="font-semibold text-orange-600">+ {{ number_format($registrationFee, 0) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center py-3 bg-gray-50 rounded-lg px-3 -mx-3">
                                <span class="font-semibold text-gray-900">Total Repayment</span>
                                <span class="font-bold text-lg text-gray-900">{{ number_format($totalPayment, 0) }}</span>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-200">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-medium text-amber-800">Effective Rate</span>
                            </div>
                            <p class="text-2xl font-bold text-amber-700">{{ number_format($effectiveRate, 2) }}%</p>
                            <p class="text-xs text-amber-600 mt-1">Annual rate including all fees</p>
                        </div>
                    </div>

                    <!-- Download Button -->
                    <button 
                        wire:click="downloadPdf"
                        wire:loading.attr="disabled"
                        class="w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold py-4 px-6 rounded-xl transition-colors flex items-center justify-center gap-3 shadow-lg disabled:opacity-50"
                    >
                        <svg wire:loading.remove wire:target="downloadPdf" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <svg wire:loading wire:target="downloadPdf" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="downloadPdf">Download PDF Report</span>
                        <span wire:loading wire:target="downloadPdf">Generating PDF...</span>
                    </button>

                    <!-- Tips Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            Helpful Tips
                        </h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Higher down payment = Lower monthly payments
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Shorter term = Less total interest paid
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Compare rates from multiple lenders
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Check for prepayment penalties
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

