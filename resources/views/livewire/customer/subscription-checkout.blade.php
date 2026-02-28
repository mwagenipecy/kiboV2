<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (!$paymentStep)
            {{-- Plan summary & proceed to payment --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h1 class="text-xl font-bold text-gray-900">Checkout</h1>
                    <p class="text-sm text-gray-600 mt-1">Review your plan and proceed to payment</p>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h2>
                        @if($plan->description)
                            <p class="text-gray-600 mt-1">{{ $plan->description }}</p>
                        @endif
                        <div class="mt-4 flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-gray-900">{{ $plan->currency }} {{ number_format($plan->price, 2) }}</span>
                            @if($plan->duration_days)
                                <span class="text-gray-600">/ {{ $plan->duration_days }} days</span>
                            @else
                                <span class="text-gray-600">one-time</span>
                            @endif
                        </div>
                        @if($plan->max_listings !== null)
                            <p class="mt-2 text-sm font-medium text-green-700">{{ $plan->max_listings }} {{ $plan->max_listings === 1 ? 'car' : 'cars' }} can be listed</p>
                        @endif
                    </div>

                    @if($entity->pricingPlan)
                        <div class="rounded-lg bg-amber-50 border border-amber-200 p-4">
                            <p class="text-sm text-amber-800">Your current plan: <strong>{{ $entity->pricingPlan->name }}</strong>. Subscribing to this plan will upgrade your listing limit.</p>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="button" wire:click="backToPricing" class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                            Back to plans
                        </button>
                        <button type="button" wire:click="proceedToPayment" class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors">
                            Proceed to payment
                        </button>
                    </div>
                </div>
            </div>
        @else
            {{-- Payment instructions --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                    <h1 class="text-xl font-bold text-gray-900">Complete your payment</h1>
                    <p class="text-sm text-gray-600 mt-1">Use the details below to pay. Your subscription will activate once payment is confirmed.</p>
                </div>
                <div class="p-6 space-y-6">
                    <div class="rounded-lg border border-gray-200 p-4 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Amount</span>
                            <span class="font-semibold text-gray-900">{{ $subscription->currency }} {{ number_format($subscription->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Plan</span>
                            <span class="font-medium text-gray-900">{{ $plan->name }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Payment reference</span>
                            <span class="font-mono font-semibold text-green-700">SUB-{{ $subscription->id }}</span>
                        </div>
                    </div>

                    <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Payment options</h3>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li><strong>M-Pesa:</strong> Paybill 123456, Account <span class="font-mono">SUB-{{ $subscription->id }}</span></li>
                            <li><strong>Bank transfer:</strong> Contact support for account details and use reference <span class="font-mono">SUB-{{ $subscription->id }}</span></li>
                        </ul>
                        <p class="mt-3 text-xs text-gray-500">After paying, our team will verify and activate your plan. You can also contact us to pay in person.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <a href="{{ route('admin.dashboard') }}" class="flex-1 text-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors">
                            Go to dealer dashboard
                        </a>
                        <a href="{{ route('pricing.show', ['category' => 'cars']) }}" class="flex-1 text-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                            Back to pricing
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
