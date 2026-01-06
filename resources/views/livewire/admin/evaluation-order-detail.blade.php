<div>
    <!-- Header with Back Button -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('admin.orders.evaluations.index') }}" class="flex items-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Evaluations
            </a>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Evaluation Order Details</h1>
                <p class="mt-2 text-gray-600">Order #{{ $order->order_number }}</p>
            </div>
            <div class="flex gap-3">
                @if($order->isCompleted() && $order->completion_data)
                <button wire:click="downloadCertificate" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download Certificate
                </button>
                @endif
                
                @if($order->payment_completed && !$order->isCompleted())
                <button wire:click="toggleIssueForm" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Issue Assessment Report
                </button>
                @endif
            </div>
        </div>
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
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Order Status</h2>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        @if($order->status->value === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status->value === 'processing') bg-blue-100 text-blue-800
                        @elseif($order->status->value === 'completed') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $order->status_label }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Order Number:</p>
                        <p class="font-medium text-gray-900">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Order Type:</p>
                        <p class="font-medium text-gray-900">{{ $order->order_type_label }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Submitted:</p>
                        <p class="font-medium text-gray-900">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @if($order->processed_at)
                    <div>
                        <p class="text-gray-600">Processed:</p>
                        <p class="font-medium text-gray-900">{{ $order->processed_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                    @if($order->completed_at)
                    <div>
                        <p class="text-gray-600">Completed:</p>
                        <p class="font-medium text-gray-900">{{ $order->completed_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Customer Information</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Name:</p>
                        <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Email:</p>
                        <p class="font-medium text-gray-900">{{ $order->user->email }}</p>
                    </div>
                    @if($order->user->phone)
                    <div>
                        <p class="text-gray-600">Phone:</p>
                        <p class="font-medium text-gray-900">{{ $order->user->phone }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Vehicle Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Vehicle Information</h2>
                @if($order->vehicle)
                <div class="flex gap-6">
                    @if($order->vehicle->image_front)
                    <div class="w-48 h-48 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        <img src="{{ asset('storage/' . $order->vehicle->image_front) }}" alt="Vehicle" class="w-full h-full object-cover">
                    </div>
                    @endif
                    <div class="flex-1">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-4">
                            {{ $order->vehicle->year }} {{ $order->vehicle->make->name ?? '' }} {{ $order->vehicle->model->name ?? '' }}
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Asking Price:</p>
                                <p class="font-medium text-gray-900 text-lg">£{{ number_format($order->vehicle->price, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Mileage:</p>
                                <p class="font-medium text-gray-900">{{ number_format($order->vehicle->mileage) }} miles</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Condition:</p>
                                <p class="font-medium text-gray-900 capitalize">{{ $order->vehicle->condition }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Transmission:</p>
                                <p class="font-medium text-gray-900 capitalize">{{ $order->vehicle->transmission }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Fuel Type:</p>
                                <p class="font-medium text-gray-900 capitalize">{{ $order->vehicle->fuel_type }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Body Type:</p>
                                <p class="font-medium text-gray-900 capitalize">{{ $order->vehicle->body_type }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

    <!-- Assessment Form Modal -->
    @if($showIssueForm && $order->payment_completed && !$order->isCompleted())
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" wire:click="toggleIssueForm">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
            <!-- Modal Header -->
            <div class="sticky top-0 bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 rounded-t-xl flex items-center justify-between z-10">
                <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Issue Assessment Report
                </h2>
                <button wire:click="toggleIssueForm" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form class="space-y-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h4 class="font-semibold text-blue-900 mb-1">Assessment Guidelines</h4>
                        <p class="text-sm text-blue-800">Please conduct a thorough inspection of the vehicle and complete all required fields below to issue an official evaluation report.</p>
                    </div>

                    <!-- Overall Vehicle Condition -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Overall Vehicle Condition <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="vehicleCondition" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Select overall condition...</option>
                            <option value="excellent">Excellent - Like new, no visible wear</option>
                            <option value="very_good">Very Good - Minor wear, well maintained</option>
                            <option value="good">Good - Normal wear, some minor issues</option>
                            <option value="fair">Fair - Noticeable wear, needs attention</option>
                            <option value="poor">Poor - Significant issues, major repairs needed</option>
                        </select>
                        @error('vehicleCondition') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Condition Assessment Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Exterior Condition -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Exterior Condition <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="exteriorCondition" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select...</option>
                                <option value="excellent">Excellent</option>
                                <option value="very_good">Very Good</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                            </select>
                            @error('exteriorCondition') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Body, paint, glass, lights</p>
                        </div>

                        <!-- Interior Condition -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Interior Condition <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="interiorCondition" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select...</option>
                                <option value="excellent">Excellent</option>
                                <option value="very_good">Very Good</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                            </select>
                            @error('interiorCondition') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Seats, dashboard, controls</p>
                        </div>

                        <!-- Mechanical Condition -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mechanical Condition <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="mechanicalCondition" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select...</option>
                                <option value="excellent">Excellent</option>
                                <option value="very_good">Very Good</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                            </select>
                            @error('mechanicalCondition') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Engine, transmission, brakes</p>
                        </div>
                    </div>

                    <!-- Issues Found -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Issues Found (Optional)
                        </label>
                        <textarea wire:model="issuesFound" rows="5" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none" 
                            placeholder="List any issues or problems identified during inspection:
• Scratches, dents, or paint damage
• Worn tires or brakes
• Engine or transmission issues
• Electrical problems
• Interior damage or wear
• Any other concerns"></textarea>
                        @error('issuesFound') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">{{ strlen($issuesFound) }}/2000 characters - Be specific and detailed</p>
                    </div>

                    <!-- Recommended Repairs -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Recommended Repairs (Optional)
                        </label>
                        <textarea wire:model="recommendedRepairs" rows="4" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none" 
                            placeholder="List recommended repairs or maintenance:
• Immediate repairs needed
• Preventive maintenance suggested
• Estimated costs (if known)"></textarea>
                        @error('recommendedRepairs') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">{{ strlen($recommendedRepairs) }}/1000 characters</p>
                    </div>

                    <!-- Valuation Amount -->
                    <div class="border-t-2 border-gray-200 pt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Market Valuation Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg">£</span>
                            <input type="number" wire:model="valuationAmount" step="0.01" min="0" 
                                class="w-full pl-10 pr-4 py-3 text-lg font-semibold border-2 border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-green-50" 
                                placeholder="0.00">
                        </div>
                        @error('valuationAmount') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-2">Enter the current market valuation based on condition assessment and market comparison</p>
                    </div>

                    <!-- Additional Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Additional Assessment Notes (Optional)</label>
                        <textarea wire:model="reportNotes" rows="4" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none" 
                            placeholder="Any additional observations or market analysis notes..."></textarea>
                        @error('reportNotes') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">{{ strlen($reportNotes) }}/1000 characters</p>
                    </div>

                    <!-- Certificate Info -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="font-semibold text-green-900 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Certificate Information
                        </h4>
                        <ul class="text-sm text-green-800 space-y-1">
                            <li>• Official certificate will be automatically generated</li>
                            <li>• Valid for 2 weeks from issue date</li>
                            <li>• Customer will receive PDF certificate via email</li>
                            <li>• Certificate number will be unique and traceable</li>
                            <li>• Report will be marked as completed</li>
                        </ul>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 sticky bottom-0 bg-white pt-4 pb-2 border-t border-gray-200">
                        <button type="button" wire:click="toggleIssueForm" class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="button" wire:click="prepareIssueReport" class="flex-1 px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Review & Issue Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Confirmation Modal -->
    @if($showConfirmModal)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" wire:click="cancelConfirmation">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full" wire:click.stop>
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 rounded-t-xl">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Confirm Assessment Report
                </h3>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div class="mb-6">
                    <p class="text-gray-700 mb-4">You are about to issue an official evaluation report with the following details:</p>
                    
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Valuation Amount:</span>
                            <span class="text-lg font-bold text-green-600">£{{ number_format($valuationAmount, 2) }}</span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-gray-600">Overall:</span>
                                <span class="font-medium text-gray-900 capitalize ml-2">{{ str_replace('_', ' ', $vehicleCondition) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Exterior:</span>
                                <span class="font-medium text-gray-900 capitalize ml-2">{{ str_replace('_', ' ', $exteriorCondition) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Interior:</span>
                                <span class="font-medium text-gray-900 capitalize ml-2">{{ str_replace('_', ' ', $interiorCondition) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Mechanical:</span>
                                <span class="font-medium text-gray-900 capitalize ml-2">{{ str_replace('_', ' ', $mechanicalCondition) }}</span>
                            </div>
                        </div>

                        @if($issuesFound)
                        <div class="pt-3 border-t border-gray-200">
                            <span class="text-sm text-gray-600">Issues Documented:</span>
                            <span class="font-medium text-orange-600 ml-2">Yes</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div class="text-sm text-yellow-800">
                            <p class="font-semibold mb-1">Important Notice:</p>
                            <ul class="space-y-1 text-xs">
                                <li>• This will generate an official certificate valid for 2 weeks</li>
                                <li>• The report cannot be edited once issued</li>
                                <li>• Customer will be notified via email</li>
                                <li>• Order status will be marked as Completed</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <p class="text-sm text-gray-600 mb-6">
                    Are you sure you want to issue this assessment report?
                </p>

                <!-- Modal Actions -->
                <div class="flex gap-3">
                    <button wire:click="cancelConfirmation" class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        <span>Cancel</span>
                    </button>
                    <button wire:click="issueReport" class="flex-1 px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Yes, Issue Report</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

            <!-- Request Details -->
            @if($order->order_data)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Request Details</h2>
                <dl class="space-y-3 text-sm">
                    @foreach($order->order_data as $key => $value)
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <dt class="text-gray-600 capitalize font-medium">{{ str_replace('_', ' ', $key) }}:</dt>
                        <dd class="font-medium text-gray-900 capitalize">{{ is_array($value) ? implode(', ', $value) : $value }}</dd>
                    </div>
                    @endforeach
                </dl>
            </div>
            @endif

            <!-- Customer Notes -->
            @if($order->customer_notes)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="font-semibold text-blue-900 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    Customer Notes
                </h3>
                <p class="text-sm text-blue-800">{{ $order->customer_notes }}</p>
            </div>
            @endif

            <!-- Completed Assessment Report -->
            @if($order->isCompleted() && $order->completion_data)
            <div class="bg-green-50 border-2 border-green-500 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-green-900 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Assessment Report Issued
                    </h2>
                    <div class="flex gap-2">
                        <button wire:click="downloadCertificate" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download Certificate
                        </button>
                    </div>
                </div>

                <!-- Valuation Amount -->
                <div class="bg-white rounded-lg p-6 mb-4">
                    <p class="text-sm text-gray-600 mb-1">Market Valuation</p>
                    <p class="text-4xl font-bold text-green-600">£{{ number_format($order->completion_data['valuation_amount'], 2) }}</p>
                </div>

                <!-- Condition Assessment -->
                @if(isset($order->completion_data['vehicle_condition']))
                <div class="bg-white rounded-lg p-4 mb-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Condition Assessment</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Overall Condition:</p>
                            <p class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $order->completion_data['vehicle_condition']) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Exterior:</p>
                            <p class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $order->completion_data['exterior_condition']) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Interior:</p>
                            <p class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $order->completion_data['interior_condition']) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Mechanical:</p>
                            <p class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $order->completion_data['mechanical_condition']) }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Issues Found -->
                @if(!empty($order->completion_data['issues_found']))
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <h4 class="font-semibold text-yellow-900 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Issues Found
                    </h4>
                    <p class="text-sm text-yellow-800 whitespace-pre-line">{{ $order->completion_data['issues_found'] }}</p>
                </div>
                @endif

                <!-- Recommended Repairs -->
                @if(!empty($order->completion_data['recommended_repairs']))
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
                    <h4 class="font-semibold text-orange-900 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Recommended Repairs
                    </h4>
                    <p class="text-sm text-orange-800 whitespace-pre-line">{{ $order->completion_data['recommended_repairs'] }}</p>
                </div>
                @endif

                <!-- Certificate Details -->
                <div class="bg-white rounded-lg p-4 mb-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Certificate Details</h4>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <dt class="text-gray-600">Certificate Number:</dt>
                            <dd class="font-medium text-gray-900">{{ $order->completion_data['certificate_number'] }}</dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <dt class="text-gray-600">Issued By:</dt>
                            <dd class="font-medium text-gray-900">{{ $order->completion_data['issued_by'] }}</dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <dt class="text-gray-600">Issue Date:</dt>
                            <dd class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($order->completion_data['issued_at'])->format('M d, Y h:i A') }}</dd>
                        </div>
                        <div class="flex justify-between py-2">
                            <dt class="text-gray-600">Valid Until:</dt>
                            <dd class="font-medium text-orange-600">{{ \Carbon\Carbon::parse($order->completion_data['valid_until'])->format('M d, Y h:i A') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Additional Notes -->
                @if(!empty($order->completion_data['report_notes']))
                <div class="bg-white rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Additional Assessment Notes</h4>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $order->completion_data['report_notes'] }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Payment Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Payment Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Service Fee:</span>
                        <span class="text-xl font-bold text-gray-900">£{{ number_format($order->fee, 2) }}</span>
                    </div>
                    
                    @if($order->payment_completed)
                    <div class="flex items-center gap-2 text-green-700 bg-green-50 px-3 py-2 rounded-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-medium text-sm">Payment Completed</p>
                            @if($order->paid_at)
                            <p class="text-xs">{{ $order->paid_at->format('M d, Y h:i A') }}</p>
                            @endif
                        </div>
                    </div>
                    
                    @if($order->payment_method)
                    <div class="text-sm">
                        <span class="text-gray-600">Method:</span>
                        <span class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                    </div>
                    @endif
                    
                    @if($order->payment_reference)
                    <div class="text-sm">
                        <span class="text-gray-600">Reference:</span>
                        <span class="font-mono text-xs text-gray-900">{{ $order->payment_reference }}</span>
                    </div>
                    @endif
                    @else
                    <div class="flex items-center gap-2 text-red-700 bg-red-50 px-3 py-2 rounded-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="font-medium text-sm">Payment Required</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Order Timeline</h3>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-green-500"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Order Submitted</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($order->paid_at)
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-green-500"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Payment Received</p>
                            <p class="text-xs text-gray-500">{{ $order->paid_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($order->processed_at)
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-green-500"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Order Processed</p>
                            <p class="text-xs text-gray-500">{{ $order->processed_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($order->completed_at)
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-green-500"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Report Issued</p>
                            <p class="text-xs text-gray-500">{{ $order->completed_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Admin Notes -->
            @if($order->admin_notes)
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <h4 class="font-semibold text-purple-900 mb-2">Admin Notes</h4>
                <p class="text-sm text-purple-800">{{ $order->admin_notes }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

