<div class="bg-gray-50">
  
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('message') }}</p>
                </div>
                <button wire:click="$refresh" class="text-green-600 hover:text-green-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
                <button wire:click="$refresh" class="text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @if($vehicle)
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="font-semibold text-blue-900 mb-1">Vehicle Information Pre-filled</p>
                        <p class="text-sm text-blue-700">
                            The form has been pre-filled with details from <strong>{{ $vehicle->year }} {{ $vehicle->make->name ?? '' }} {{ $vehicle->model->name ?? '' }}</strong>. 
                            You can modify any values as needed.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Calculator Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <form wire:submit.prevent="calculateInsurance">
                        <!-- Step 1: Insurable Value -->
                        <div class="mb-8 bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 kibo-bg text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</div>
                                Vehicle Information
                            </h3>
                            
                            <!-- Currency Converter (if vehicle price is in foreign currency) -->
                            @if($showCurrencyConverter)
                            <div class="mb-4 p-4 bg-blue-50 border-l-4 rounded-lg" style="border-color: #009866;">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: #009866;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900 mb-2">Currency Conversion</p>
                                        <p class="text-sm text-gray-600 mb-3">
                                            Vehicle price is in <strong>{{ $vehicleCurrency }}</strong>. Converting to Tanzanian Shillings (TZS) for insurance calculation.
                                        </p>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-white p-3 rounded-lg">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Original Price</label>
                                                <div class="text-base font-bold text-gray-900">{{ number_format($vehiclePriceOriginal, 2) }} {{ $vehicleCurrency }}</div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Conversion Rate ({{ $vehicleCurrency }} to TZS)</label>
                                                <input 
                                                    wire:model.live="conversionRate" 
                                                    type="number" 
                                                    step="0.01" 
                                                    min="0" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:border-transparent"
                                                    style="focus:ring-color: #009866;"
                                                    placeholder="e.g., 2850"
                                                >
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Converted Value</label>
                                                <div class="text-base font-bold" style="color: #009866;">{{ number_format($insurableValue) }} TZS</div>
                                            </div>
                                        </div>
                                        
                                        <p class="text-xs text-gray-500 mt-2">
                                            💡 Adjust the conversion rate to match current exchange rates. Default: 1 {{ $vehicleCurrency }} = {{ number_format($conversionRate) }} TZS
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Insurable Value (TZS) *
                                        @if($showCurrencyConverter)
                                            <span class="text-xs text-gray-500">(auto-calculated from {{ $vehicleCurrency }})</span>
                                        @endif
                                    </label>
                                    <input 
                                        wire:model="insurableValue" 
                                        type="number" 
                                        placeholder="e.g., 2,500,000" 
                                        min="500000" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:border-transparent text-lg font-medium"
                                        style="focus:ring-color: #009866;"
                                        @if($showCurrencyConverter) readonly @endif
                                    >
                                    @error('insurableValue') 
                                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                                    @enderror
                                    @if($showCurrencyConverter)
                                        <p class="text-xs text-gray-500 mt-1">This value is auto-calculated. Adjust the conversion rate above if needed.</p>
                                    @endif
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Year *</label>
                                    <select wire:model="year" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                        @for($y = date('Y'); $y >= 1980; $y--)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                    @error('year') 
                                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                                    @enderror
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                                    <input wire:model="startDate" type="date" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                    @error('startDate') 
                                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Vehicle Class & Passengers -->
                        <div class="mb-8 bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 kibo-bg text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</div>
                                Vehicle Classification
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Class *</label>
                                    <select wire:model.live="vehicleClass" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500 text-lg">
                                        <option value="">-- Select Vehicle Class --</option>
                                        @foreach($vehicleClasses as $key => $class)
                                            <option value="{{ $key }}">{{ $class }}</option>
                                        @endforeach
                                    </select>
                                    @error('vehicleClass') 
                                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Carrying Passengers? *</label>
                                    <select wire:model="carryingPassengers" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Number of Passengers 
                                        @if(in_array($vehicleClass, ['Motorcycle Wheelers 2', 'Motorcycle Wheelers 3']))
                                            <span class="text-gray-600">(including driver)</span>
                                        @endif
                                    </label>
                                    <input wire:model="noPassengers" type="number" min="0" max="50" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                    @error('noPassengers') 
                                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                                    @enderror
                                    @if($vehicleClass === 'Motorcycle Wheelers 2' && $carryingPassengers === 'Yes')
                                        <p class="text-sm text-gray-600 mt-1">
                                            <strong>Additional TSh 15,000</strong> for passenger coverage (Bodaboda)
                                        </p>
                                    @elseif($vehicleClass === 'Motorcycle Wheelers 3' && $carryingPassengers === 'Yes')
                                        <p class="text-sm text-gray-600 mt-1">
                                            <strong>Additional TSh 45,000</strong> for passenger coverage (Bajaji)
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Coverage Type & Claim Status -->
                        <div class="mb-8 bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 kibo-bg text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</div>
                                Coverage & Claims History
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Claim Status *</label>
                                    <select wire:model.live="claimStatus" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                        @foreach($claimStatusOptions as $key => $status)
                                            <option value="{{ $key }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    @error('claimStatus') 
                                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                                    @enderror
                                </div>

                                @if($vehicleClass && $claimStatus)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Type of Cover *</label>
                                        <select wire:model="typeOfCover" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500 text-sm">
                                            <option value="">-- Select Coverage Type --</option>
                                            @foreach($this->getAvailableCoverageOptions() as $coverageKey => $coverageData)
                                                <option value="{{ $coverageKey }}">
                                                    {{ $coverageKey }}
                                                    @if($coverageData['rate'] > 0)
                                                        ({{ ($coverageData['rate'] * 100) }}% rate)
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('typeOfCover') 
                                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Calculate Button -->
                        <div class="flex space-x-4">
                            <button type="submit" class="flex-1 kibo-bg text-white px-6 py-4 rounded-lg font-semibold text-lg transition-colors flex items-center justify-center space-x-2 shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <span>Calculate Premium</span>
                            </button>
                            <button type="button" wire:click="resetCalculator" class="px-6 py-4 border-2 border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Sidebar -->
            <div class="lg:col-span-1">
                @if($showResults && $calculationResults)
                    <!-- Premium Results -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Calculation Results
                        </h3>
                        
                        <!-- Summary Box -->
                        <div class="bg-gray-800 rounded-lg p-4 text-white mb-4">
                            <div class="text-center">
                                <div class="text-sm opacity-90">Total Premium Inc. VAT</div>
                                <div class="text-2xl font-bold">TSh {{ number_format($calculationResults['total_premium']) }}</div>
                                <div class="text-sm opacity-90">Annual Premium</div>
                            </div>
                        </div>

                        <!-- Breakdown -->
                        <div class="space-y-3 text-sm">
                            <!-- Input Summary -->
                            <div class="bg-gray-50 rounded p-3 border border-gray-200">
                                <div class="font-semibold text-gray-700 mb-2">Input Summary:</div>
                                <div class="space-y-1 text-xs">
                                    <div class="flex justify-between">
                                        <span>Insurable Value:</span>
                                        <span class="font-semibold">TSh {{ number_format($calculationResults['insurable_value']) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Vehicle Class:</span>
                                        <span class="font-semibold">{{ $calculationResults['vehicle_class'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Carrying Passengers:</span>
                                        <span class="font-semibold">{{ $calculationResults['carrying_passengers'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>No. Passengers:</span>
                                        <span class="font-semibold">{{ $calculationResults['no_passengers'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Type of Cover:</span>
                                        <span class="font-semibold text-gray-800">{{ $calculationResults['type_of_cover'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Claim Status:</span>
                                        <span class="font-semibold">{{ $calculationResults['claim_status'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Year:</span>
                                        <span class="font-semibold">{{ $calculationResults['year'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Premium Calculation -->
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span>Minimum Premium:</span>
                                    <span class="font-semibold">TSh {{ number_format($calculationResults['minimum_premium']) }}</span>
                                </div>
                                
                                @if($calculationResults['plus_tpp'] > 0)
                                    <div class="flex justify-between">
                                        <span>Plus TPP:</span>
                                        <span class="font-semibold">TSh {{ number_format($calculationResults['plus_tpp']) }}</span>
                                    </div>
                                @else
                                    <div class="flex justify-between">
                                        <span>Plus TPP:</span>
                                        <span class="font-semibold">-</span>
                                    </div>
                                @endif

                                @if($calculationResults['additional_charge'] > 0)
                                    <div class="flex justify-between">
                                        <span>Additional Charge:</span>
                                        <span class="font-semibold text-gray-800">TSh {{ number_format($calculationResults['additional_charge']) }}</span>
                                    </div>
                                @else
                                    <div class="flex justify-between">
                                        <span>Additional Charge:</span>
                                        <span class="font-semibold">-</span>
                                    </div>
                                @endif

                                <hr class="border-gray-300">

                                <div class="flex justify-between">
                                    <span>Premium Excl. VAT:</span>
                                    <span class="font-semibold">TSh {{ number_format($calculationResults['premium_excl_vat']) }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span>VAT %:</span>
                                    <span class="font-semibold">{{ ($calculationResults['vat_rate'] * 100) }}%</span>
                                </div>

                                <div class="flex justify-between">
                                    <span>Premium Rate:</span>
                                    <span class="font-semibold">{{ ($calculationResults['premium_rate'] * 100) }}%</span>
                                </div>

                                <hr class="border-gray-300">

                                <div class="flex justify-between text-gray-900 font-bold">
                                    <span>Total Premium Inc. VAT:</span>
                                    <span>TSh {{ number_format($calculationResults['total_premium']) }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span>VAT Amount:</span>
                                    <span class="font-semibold">TSh {{ number_format($calculationResults['vat_amount']) }}</span>
                                </div>

                                <hr class="border-gray-300">

                                <div class="flex justify-between text-gray-700">
                                    <span>Estimated Commission:</span>
                                    <span class="font-semibold">TSh {{ number_format($calculationResults['estimated_commission']) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Excess Policy -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-4">
                            <div class="text-sm">
                                <div class="font-bold text-yellow-800 mb-2">Excess Policy:</div>
                                <div class="text-yellow-700 text-xs leading-relaxed">
                                    {{ $calculationResults['excess_info'] }}
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 mt-6">
                            <a href="tel:+255757330260" class="w-full kibo-bg text-white px-4 py-3 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span>Call Now</span>
                            </a>
                            <a href="https://wa.me/255757330260?text=Hi, I need vehicle insurance. My quote is TSh {{ number_format($calculationResults['total_premium']) }}" target="_blank" class="w-full kibo-bg text-white px-4 py-3 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.109"/>
                                </svg>
                                <span>WhatsApp</span>
                            </a>
                        </div>
                    </div>
                @else
                    <!-- How It Works -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">How It Works</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 kibo-bg text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                                <div>
                                    <div class="font-medium text-gray-900">Enter Vehicle Details</div>
                                    <div class="text-sm text-gray-600">Vehicle value, year, and start date</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 kibo-bg text-white rounded-full flex items-center justify-center text-sm font-bold">2</div>
                                <div>
                                    <div class="font-medium text-gray-900">Select Vehicle Class</div>
                                    <div class="text-sm text-gray-600">Choose class and passenger details</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 kibo-bg text-white rounded-full flex items-center justify-center text-sm font-bold">3</div>
                                <div>
                                    <div class="font-medium text-gray-900">Choose Coverage & Claim Status</div>
                                    <div class="text-sm text-gray-600">Select protection level and claim history</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 kibo-bg text-white rounded-full flex items-center justify-center text-sm font-bold">✓</div>
                                <div>
                                    <div class="font-medium text-gray-900">Get Detailed Results</div>
                                    <div class="text-sm text-gray-600">Complete breakdown with all calculations</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Coverage Info -->
                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-3">Coverage Types Available</h4>
                        <div class="space-y-3 text-sm">
                            <div class="p-3 bg-gray-50 rounded border border-gray-200">
                                <div class="font-medium text-gray-900">Comprehensive Coverage</div>
                                <div class="text-gray-600 text-xs">Complete protection including own damage, theft, fire, and third party liability</div>
                            </div>
                            <div class="p-3 bg-gray-50 rounded border border-gray-200">
                                <div class="font-medium text-gray-900">Third Party Fire & Theft</div>
                                <div class="text-gray-600 text-xs">Third party liability plus fire and theft protection for your vehicle</div>
                            </div>
                            <div class="p-3 bg-gray-50 rounded border border-gray-200">
                                <div class="font-medium text-gray-900">Third Party Only</div>
                                <div class="text-gray-600 text-xs">Legal minimum coverage for third party claims</div>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-3 bg-yellow-50 rounded border border-yellow-200">
                            <div class="text-xs text-yellow-800">
                                <strong>Note:</strong> All calculations follow the exact formula structure with proper VAT, commission, and excess calculations.
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
/* Professional theme custom styles */
* {
    transition-property: color, background-color, border-color, opacity, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Enhanced form styling */
input:focus, select:focus, textarea:focus {
    outline: none;
    ring: 2px solid #6b7280;
    border-color: #6b7280;
    box-shadow: 0 0 0 3px rgba(107, 114, 128, 0.1);
}

/* Step indicators */
.step-indicator {
    background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
    box-shadow: 0 4px 6px rgba(55, 65, 81, 0.3);
}

/* Results animation */
.results-appear {
    animation: slideInRight 0.5s ease-out;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Responsive improvements */
@media (max-width: 768px) {
    .grid {
        gap: 1rem;
    }
    
    .text-3xl {
        font-size: 1.875rem;
        line-height: 2.25rem;
    }
    
    .px-6 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}

/* Kibo Brand Color */
.kibo-bg {
    background-color: #009866 !important;
}

.kibo-bg:hover {
    background-color: #007a52 !important;
}

.kibo-text {
    color: #009866 !important;
}

.kibo-border {
    border-color: #009866 !important;
}

/* Professional table appearance */
.excel-table {
    border-collapse: collapse;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Gray gradient backgrounds */
.bg-gray-gradient {
    background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
}

/* Number formatting */
.currency-display {
    font-family: 'Courier New', monospace;
    font-weight: bold;
    color: #374151;
}

/* Loading states */
.loading-shimmer {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% {
        background-position: -200% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

/* Button hover effects */
.btn-gray {
    background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
    transition: all 0.3s ease;
}

.btn-gray:hover {
    background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(55, 65, 81, 0.3);
}

/* Card elevation effects */
.card-elevated {
    transition: all 0.3s ease;
}

.card-elevated:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Success states */
.success-glow {
    box-shadow: 0 0 20px rgba(55, 65, 81, 0.3);
    border-color: #374151;
}
    </style>
</div>