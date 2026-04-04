@php
    $guestTrackStep = 1;
    if ($allowGuestTrack && isset($order)) {
        $guestTrackStep = match (true) {
            in_array($order->status, ['delivered', 'completed'], true) => 5,
            $order->status === 'shipped' => 5,
            in_array($order->status, ['preparing', 'payment_verified'], true) => 4,
            in_array($order->status, ['payment_submitted'], true) => 4,
            in_array($order->status, ['awaiting_payment', 'accepted'], true) => 3,
            $order->status === 'quoted' => 2,
            in_array($order->status, ['rejected', 'cancelled'], true) => 0,
            default => 1,
        };
    }
@endphp

<div class="{{ $allowGuestTrack ? 'w-full' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8' }}">

    {{-- ─── Back Navigation ──────────────────────────────────────────────────── --}}
    <div class="mb-5">
        @if($allowGuestTrack)
            <a href="{{ route('spare-parts.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-700 hover:text-emerald-900 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Spare Parts
            </a>
        @else
            <a href="{{ route('spare-parts.orders') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Orders
            </a>
        @endif
    </div>

    {{-- ─── Page Hero: Order Identity + Status ──────────────────────────────── --}}
    <div class="mb-6 rounded-2xl overflow-hidden border border-gray-200 shadow-sm bg-white">
        {{-- Coloured top band --}}
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-5 sm:px-8 sm:py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="min-w-0">
                @if($allowGuestTrack)
                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-200 mb-1">Spare Parts · Order Tracking</p>
                @endif
                <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight truncate">{{ $order->order_number }}</h1>
                @if($order->part_name)
                    <p class="mt-1 text-emerald-100 text-sm sm:text-base font-medium truncate">{{ $order->part_name }}</p>
                @endif
            </div>
            <div class="flex flex-col items-start sm:items-end gap-2 shrink-0">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold {{ $this->getStatusColor($order->status) }}">
                    {{ $order->status_label ?? ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
                <p class="text-xs text-emerald-200">Placed {{ $order->created_at->format('M j, Y · g:i A') }}</p>
            </div>
        </div>

        {{-- Progress stepper (guest track only, and only when not a dead-end status) --}}
        @if($allowGuestTrack)
            @if($guestTrackStep === 0)
                <div class="px-6 py-4 sm:px-8 bg-amber-50 border-t border-amber-100">
                    <p class="text-sm text-amber-900">
                        @if($order->status === 'cancelled')
                            This order was cancelled. Contact us if you need help starting a new request.
                        @else
                            This request was closed without a sale. You can place a new order anytime from Spare Parts.
                        @endif
                    </p>
                </div>
            @else
                <div class="px-6 py-5 sm:px-8 border-t border-gray-100">
                    @php
                        $steps = [1 => 'Received', 2 => 'Quoted', 3 => 'Confirmed', 4 => 'Processing', 5 => 'Done'];
                    @endphp

                    {{-- Mobile: vertical --}}
                    <ol class="md:hidden space-y-0" aria-label="Order progress">
                        @foreach($steps as $step => $label)
                            @php
                                $stepDone    = $guestTrackStep > $step || ($step === 5 && $guestTrackStep >= 5);
                                $stepCurrent = !$stepDone && $guestTrackStep === $step;
                            @endphp
                            <li class="flex gap-4 min-h-0">
                                <div class="flex w-10 shrink-0 flex-col items-center">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full text-xs font-bold border-2 shadow-sm
                                        {{ $stepDone ? 'bg-emerald-600 border-emerald-600 text-white' : ($stepCurrent ? 'bg-white border-emerald-600 text-emerald-700 ring-4 ring-emerald-100' : 'bg-gray-50 border-gray-200 text-gray-400') }}">
                                        @if($stepDone)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        @else
                                            {{ $step }}
                                        @endif
                                    </div>
                                    @if($step < 5)
                                        <div class="w-0.5 min-h-[32px] {{ $guestTrackStep > $step ? 'bg-emerald-400' : 'bg-gray-200' }}"></div>
                                    @endif
                                </div>
                                <div class="flex-1 pt-2 {{ $step < 5 ? 'pb-6' : 'pb-1' }}">
                                    <p class="text-sm font-semibold text-gray-900">{{ $label }}</p>
                                    <p class="mt-0.5 text-xs {{ $stepCurrent ? 'font-medium text-emerald-700' : ($stepDone ? 'text-gray-400' : 'text-gray-400') }}">
                                        {{ $stepCurrent ? 'Current step' : ($stepDone ? 'Completed' : 'Upcoming') }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ol>

                    {{-- Desktop: horizontal --}}
                    <div class="hidden md:flex items-start w-full gap-1" aria-label="Order progress">
                        @foreach($steps as $step => $label)
                            @php
                                $stepDone    = $guestTrackStep > $step || ($step === 5 && $guestTrackStep >= 5);
                                $stepCurrent = !$stepDone && $guestTrackStep === $step;
                            @endphp
                            <div class="flex flex-col items-center flex-1 min-w-0">
                                <div class="flex items-center w-full">
                                    @if($step > 1)
                                        <div class="h-1 flex-1 rounded-full {{ $guestTrackStep > $step ? 'bg-emerald-500' : 'bg-gray-200' }}"></div>
                                    @endif
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-xs font-bold border-2 transition-colors
                                        {{ $stepDone ? 'bg-emerald-600 border-emerald-600 text-white' : ($stepCurrent ? 'bg-white border-emerald-600 text-emerald-700 ring-2 ring-emerald-100' : 'bg-gray-50 border-gray-200 text-gray-400') }}">
                                        @if($stepDone)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        @else
                                            {{ $step }}
                                        @endif
                                    </div>
                                    @if($step < 5)
                                        <div class="h-1 flex-1 rounded-full {{ $guestTrackStep > $step ? 'bg-emerald-500' : 'bg-gray-200' }}"></div>
                                    @endif
                                </div>
                                <span class="mt-2 text-xs font-medium text-center leading-tight px-1 {{ $stepDone || $stepCurrent ? 'text-gray-800' : 'text-gray-400' }}">{{ $label }}</span>
                                @if($stepCurrent)
                                    <span class="mt-0.5 text-[10px] font-semibold text-emerald-600">Now</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>

    {{-- ─── Main Content Grid ────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── Left / Main Column (2/3) ──────────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Action Banner: quotations, payment prompts, shipping notices --}}
            @if($order->quotations && $order->quotations->count() > 0 && in_array($order->status, ['pending', 'quoted']))
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-base font-bold text-gray-900">{{ $order->quotations->count() }} Quotation(s) Received</h2>
                    <span class="text-xs text-gray-500">Select one to proceed</span>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($order->quotations->where('status', 'pending') as $quotation)
                    <div class="px-5 py-4 flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="text-xl font-bold text-emerald-700">{{ $quotation->currency }} {{ number_format($quotation->quoted_price, 2) }}</span>
                                @if($quotation->estimated_days)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $quotation->estimated_days }}d delivery</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600">{{ $quotation->agent->name ?? 'Verified Supplier' }}</p>
                            @if($quotation->quotation_notes)
                                <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">{{ $quotation->quotation_notes }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-2">Quoted {{ $quotation->created_at->diffForHumans() }}</p>
                        </div>
                        <button
                            wire:click="openQuoteResponseModal({{ $quotation->id }})"
                            class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Accept
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($order->acceptedQuotation)
            <div class="rounded-2xl border-2 border-emerald-200 bg-emerald-50/60 px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-xs font-bold uppercase tracking-wide text-emerald-700">Accepted Quotation</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $order->acceptedQuotation->currency }} {{ number_format($order->acceptedQuotation->quoted_price, 2) }}</p>
                    <p class="text-sm text-gray-600 mt-0.5">{{ $order->acceptedQuotation->agent->name ?? 'Verified Supplier' }}</p>
                </div>
                @if($order->acceptedQuotation->estimated_days)
                    <span class="self-start sm:self-center inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                        {{ $order->acceptedQuotation->estimated_days }} days delivery
                    </span>
                @endif
            </div>
            @endif

            @if($order->status === 'awaiting_payment')
            <div class="rounded-2xl border border-orange-200 bg-orange-50 px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-sm font-medium text-orange-800">Your order is awaiting payment. Please submit proof of payment to continue.</p>
                <button wire:click="openPaymentModal" class="shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Submit Payment Proof
                </button>
            </div>
            @endif

            @if($order->status === 'payment_submitted')
            <div class="rounded-2xl border border-indigo-200 bg-indigo-50 px-5 py-4">
                <p class="text-sm font-medium text-indigo-800">Your payment proof has been submitted and is pending verification. We'll notify you once verified.</p>
            </div>
            @endif

            @if($order->status === 'shipped')
            <div class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4">
                <p class="text-sm font-semibold text-blue-900 mb-1">📦 Your order has been shipped!</p>
                @if($order->tracking_number)
                    <p class="text-sm text-blue-800">Tracking: <span class="font-mono font-semibold">{{ $order->tracking_number }}</span></p>
                @endif
            </div>
            @endif

            {{-- ── Order Detail Cards ────────────────────────────────────────── --}}
            {{-- Vehicle + Part (side by side on wider screens) --}}
            <div class="grid sm:grid-cols-2 gap-5">
                {{-- Vehicle --}}
                <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-5">
                    <h2 class="text-xs font-bold uppercase tracking-wide text-gray-500 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12M8 12h12M8 17h6"/></svg>
                        Vehicle
                    </h2>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Make</dt>
                            <dd class="font-semibold text-gray-900">{{ $order->vehicleMake->name ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Model</dt>
                            <dd class="font-semibold text-gray-900">{{ $order->vehicleModel->name ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Condition</dt>
                            <dd class="font-semibold text-gray-900 capitalize">{{ $order->condition }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Part --}}
                <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-5">
                    <h2 class="text-xs font-bold uppercase tracking-wide text-gray-500 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                        Part
                    </h2>
                    <p class="font-bold text-gray-900 text-base mb-2">{{ $order->part_name ?? '—' }}</p>
                    @if($order->description)
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $order->description }}</p>
                    @endif
                    @if($order->quoted_price)
                        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-xs text-gray-500">Quoted price</span>
                            <span class="font-bold text-emerald-700">{{ number_format($order->quoted_price, 2) }} {{ $order->currency ?? 'TZS' }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Reference Photos --}}
            @php
                $referenceImages = is_array($order->images) ? array_values(array_filter($order->images)) : [];
            @endphp
            @if(count($referenceImages) > 0)
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-5">
                <h2 class="text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">Reference Photos</h2>
                <p class="text-xs text-gray-400 mb-4">Tap any image to open full size.</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach($referenceImages as $imagePath)
                        <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($imagePath) }}" target="_blank" rel="noopener noreferrer"
                           class="group aspect-square rounded-xl overflow-hidden border border-gray-200 shadow-sm block">
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($imagePath) }}"
                                alt="Reference photo"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
                            >
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Delivery --}}
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-5">
                <h2 class="text-xs font-bold uppercase tracking-wide text-gray-500 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Delivery
                </h2>
                <div class="grid sm:grid-cols-2 gap-5 text-sm">
                    <div>
                        <dt class="text-gray-500 mb-1">Address</dt>
                        <dd class="font-medium text-gray-900">{{ $order->delivery_address }}</dd>
                        @if($order->delivery_city || $order->delivery_region)
                            <dd class="text-gray-500 mt-0.5">{{ $order->delivery_city }}{{ $order->delivery_city && $order->delivery_region ? ', ' : '' }}{{ $order->delivery_region }}</dd>
                        @endif
                    </div>
                    <div class="space-y-3">
                        @if($order->estimated_delivery_date)
                            <div>
                                <dt class="text-gray-500 mb-0.5">Estimated delivery</dt>
                                <dd class="font-semibold text-gray-900">{{ $order->estimated_delivery_date->format('M j, Y') }}</dd>
                            </div>
                        @endif
                        @if($order->tracking_number)
                            <div>
                                <dt class="text-gray-500 mb-0.5">Courier tracking</dt>
                                <dd class="font-mono font-semibold text-gray-900">{{ $order->tracking_number }}</dd>
                            </div>
                        @endif
                    </div>
                </div>
                @if($order->delivery_notes)
                    <p class="mt-4 pt-4 border-t border-gray-100 text-sm text-gray-600">{{ $order->delivery_notes }}</p>
                @endif
            </div>

            {{-- Payment --}}
            @if($order->payment_method)
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-5">
                <h2 class="text-xs font-bold uppercase tracking-wide text-gray-500 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Payment
                </h2>
                <div class="grid sm:grid-cols-2 gap-5 text-sm mb-4">
                    <div>
                        <dt class="text-gray-500 mb-0.5">Method</dt>
                        <dd class="font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 mb-1">Status</dt>
                        <dd>
                            @if($order->payment_verified)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Verified
                                </span>
                            @elseif($order->payment_submitted_at)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pending Verification</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">Awaiting Payment</span>
                            @endif
                        </dd>
                    </div>
                </div>
                @if($order->payment_account_details)
                    <div class="rounded-xl bg-gray-50 border border-gray-100 px-4 py-3 text-sm space-y-1">
                        @if(isset($order->payment_account_details['bank_name']))
                            <p class="text-gray-900"><span class="text-gray-500 w-32 inline-block">Bank</span> {{ $order->payment_account_details['bank_name'] }}</p>
                            <p class="text-gray-900"><span class="text-gray-500 w-32 inline-block">Account name</span> {{ $order->payment_account_details['account_name'] }}</p>
                            <p class="text-gray-900"><span class="text-gray-500 w-32 inline-block">Account number</span> <span class="font-mono">{{ $order->payment_account_details['account_number'] }}</span></p>
                        @endif
                        @if(isset($order->payment_account_details['mobile_provider']))
                            <p class="text-gray-900"><span class="text-gray-500 w-32 inline-block">Provider</span> {{ $order->payment_account_details['mobile_provider'] }}</p>
                            <p class="text-gray-900"><span class="text-gray-500 w-32 inline-block">Mobile number</span> <span class="font-mono">{{ $order->payment_account_details['mobile_number'] }}</span></p>
                            <p class="text-gray-900"><span class="text-gray-500 w-32 inline-block">Account name</span> {{ $order->payment_account_details['account_name'] }}</p>
                        @endif
                    </div>
                @endif
            </div>
            @endif

            {{-- Admin Notes --}}
            @if($order->admin_notes)
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-5">
                <h2 class="text-xs font-bold uppercase tracking-wide text-gray-500 mb-3">Admin Notes</h2>
                <p class="text-sm text-gray-800 bg-gray-50 rounded-xl border border-gray-100 px-4 py-3 leading-relaxed">{{ $order->admin_notes }}</p>
            </div>
            @endif

        </div>

        {{-- ── Right Sidebar (1/3) ───────────────────────────────────────────── --}}
        <div class="lg:col-span-1 space-y-5">

            {{-- Chat with Supplier --}}
            @if(in_array($order->status, ['accepted', 'quoted', 'processing']))
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden sticky top-4">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <h2 class="text-base font-bold text-gray-900">Chat with Supplier</h2>
                </div>

                <div class="h-80 overflow-y-auto px-4 py-4 space-y-3" id="chat-messages">
                    @if(count($messages) > 0)
                        @foreach($messages as $message)
                            @php
                                $isMine = auth()->check()
                                    ? (int) ($message['user_id'] ?? 0) === (int) auth()->id()
                                    : ($allowGuestTrack && empty($message['user_id']));
                            @endphp
                            <div class="flex items-end gap-2 {{ $isMine ? 'flex-row-reverse' : '' }}">
                                <div class="w-7 h-7 {{ $isMine ? 'bg-emerald-600' : 'bg-gray-300' }} rounded-full flex items-center justify-center flex-shrink-0 mb-1">
                                    <span class="text-white text-[10px] font-bold">{{ strtoupper(substr($message['user_name'] ?? 'U', 0, 1)) }}</span>
                                </div>
                                <div class="max-w-[76%] {{ $isMine ? 'items-end' : 'items-start' }} flex flex-col">
                                    <div class="{{ $isMine ? 'bg-emerald-600 text-white rounded-t-2xl rounded-bl-2xl rounded-br-sm' : 'bg-gray-100 text-gray-900 rounded-t-2xl rounded-br-2xl rounded-bl-sm' }} px-3.5 py-2.5">
                                        <p class="text-sm leading-relaxed">{{ $message['message'] }}</p>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-1 px-1">{{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="h-full flex flex-col items-center justify-center text-center px-4">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <p class="text-sm font-medium text-gray-500">No messages yet</p>
                            <p class="text-xs text-gray-400 mt-0.5">Start the conversation below</p>
                        </div>
                    @endif
                </div>

                <div class="px-4 py-3 border-t border-gray-100">
                    <form wire:submit.prevent="sendMessage" class="flex gap-2">
                        <input
                            type="text"
                            wire:model="newMessage"
                            placeholder="Type a message…"
                            class="flex-1 px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-gray-50 placeholder-gray-400"
                        >
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="shrink-0 px-3.5 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors disabled:opacity-50"
                        >
                            <span wire:loading.remove>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </span>
                            <span wire:loading>
                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                            </span>
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Guest: bookmark / order again CTA --}}
            @if($allowGuestTrack && !in_array($order->status, ['accepted', 'quoted', 'processing'], true))
            <div class="rounded-2xl border border-emerald-100 bg-gradient-to-b from-emerald-50 to-white p-5 shadow-sm sticky top-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                </div>
                <h3 class="font-bold text-gray-900 text-sm mb-1.5">Save this page</h3>
                <p class="text-sm text-gray-600 leading-relaxed">Bookmark this link or keep the SMS handy — you can return here anytime to see updates without signing in.</p>
                <a href="{{ route('spare-parts.index') }}" class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors">
                    Order more parts
                </a>
            </div>
            @endif

            {{-- Order summary meta (order number, placed date) — shown on desktop sidebar for non-guest --}}
            @unless($allowGuestTrack)
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-5 text-sm space-y-3">
                <h2 class="text-xs font-bold uppercase tracking-wide text-gray-500 mb-3">Order Summary</h2>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Order number</span>
                    <span class="font-mono font-semibold text-gray-900">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Date placed</span>
                    <span class="font-medium text-gray-900">{{ $order->created_at->format('M j, Y') }}</span>
                </div>
                @if($order->quoted_price)
                <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                    <span class="text-gray-500">Total</span>
                    <span class="text-lg font-bold text-emerald-700">{{ number_format($order->quoted_price, 2) }} {{ $order->currency ?? 'TZS' }}</span>
                </div>
                @endif
            </div>
            @endunless

        </div>
    </div>
</div>


    {{-- Quote Response Modal --}}
    @if($showQuoteResponseModal && $selectedQuotationId)
    @php
        $selectedQuotation = $order->quotations->firstWhere('id', $selectedQuotationId);
    @endphp
    @if($selectedQuotation)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" style="display: block !important;">
        <div class="fixed inset-0 bg-black/50 z-[9998]" wire:click="closeQuoteResponseModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-[9999]" wire:click.stop>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Confirm Quotation Acceptance</h3>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-gray-600">Part Name:</span>
                        <span class="font-medium text-gray-900">{{ $order->part_name }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-gray-600">Vehicle:</span>
                        <span class="font-medium text-gray-900">{{ $order->vehicleMake->name ?? 'N/A' }} {{ $order->vehicleModel->name ?? '' }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-gray-600">Supplier:</span>
                        <span class="font-medium text-gray-900">{{ $selectedQuotation->agent->name ?? 'Verified Supplier' }}</span>
                    </div>
                    @if($selectedQuotation->estimated_days)
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-gray-600">Est. Delivery:</span>
                        <span class="font-medium text-gray-900">{{ $selectedQuotation->estimated_days }} days</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                        <span class="text-gray-600">Quoted Price:</span>
                        <span class="text-2xl font-bold" style="color: #009866;">{{ $selectedQuotation->currency }} {{ number_format($selectedQuotation->quoted_price, 2) }}</span>
                    </div>
                </div>
                
                @if($selectedQuotation->quotation_notes)
                <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                    <p class="text-sm text-yellow-800"><strong>Supplier Notes:</strong> {{ $selectedQuotation->quotation_notes }}</p>
                </div>
                @endif

                @if($order->quotations->where('status', 'pending')->count() > 1)
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Note: You have {{ $order->quotations->where('status', 'pending')->count() }} quotation(s). Accepting this one will automatically decline the others.
                    </p>
                </div>
                @endif
                
                <p class="text-gray-600 text-sm mb-6">By accepting this quotation, you agree to proceed with the order at the quoted price.</p>
                
                <div class="flex gap-3">
                    <button wire:click="acceptQuotation({{ $selectedQuotationId }})" wire:loading.attr="disabled" class="flex-1 inline-flex justify-center items-center px-4 py-2 text-white font-medium rounded-lg transition-colors disabled:opacity-50" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                        <span wire:loading.remove wire:target="acceptQuotation">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Accept This Quotation
                        </span>
                        <span wire:loading wire:target="acceptQuotation" class="flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
                <button wire:click="closeQuoteResponseModal" class="w-full mt-3 px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    @endif
    @endif

    {{-- Payment Modal --}}
    @if($showPaymentModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" style="display: block !important;">
        <div class="fixed inset-0 bg-black/50 z-[9998]" wire:click="closePaymentModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 z-[9999]" wire:click.stop>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Submit Payment Proof</h3>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-700 mb-2"><strong>Amount to Pay:</strong></p>
                    <p class="text-2xl font-bold" style="color: #009866;">{{ $order->currency ?? 'TZS' }} {{ number_format($order->quoted_price, 2) }}</p>
                </div>

                @if($order->payment_account_details)
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm font-medium text-blue-800 mb-2">Pay to:</p>
                    @if(isset($order->payment_account_details['bank_name']))
                    <p class="text-blue-900"><strong>Bank:</strong> {{ $order->payment_account_details['bank_name'] }}</p>
                    <p class="text-blue-900"><strong>Account Name:</strong> {{ $order->payment_account_details['account_name'] }}</p>
                    <p class="text-blue-900 font-mono"><strong>Account Number:</strong> {{ $order->payment_account_details['account_number'] }}</p>
                    @endif
                    @if(isset($order->payment_account_details['mobile_provider']))
                    <p class="text-blue-900"><strong>Provider:</strong> {{ $order->payment_account_details['mobile_provider'] }}</p>
                    <p class="text-blue-900"><strong>Name:</strong> {{ $order->payment_account_details['account_name'] }}</p>
                    <p class="text-blue-900 font-mono"><strong>Number:</strong> {{ $order->payment_account_details['mobile_number'] }}</p>
                    @endif
                </div>
                @endif
                
                <form wire:submit.prevent="submitPayment" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Proof (Receipt/Screenshot) *</label>
                        <input type="file" wire:model="paymentProof" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                        @error('paymentProof') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, PNG, PDF (Max 5MB)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Notes (Optional)</label>
                        <textarea wire:model="paymentNotes" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent resize-none" placeholder="Transaction ID, reference number, etc."></textarea>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="submit" wire:loading.attr="disabled" wire:target="submitPayment,paymentProof" class="flex-1 inline-flex justify-center items-center px-4 py-2 text-white font-medium rounded-lg transition-colors disabled:opacity-50" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                            <span wire:loading.remove wire:target="submitPayment">Submit Payment</span>
                            <span wire:loading wire:target="submitPayment,paymentProof" class="flex items-center">
                                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Uploading...
                            </span>
                        </button>
                        <button type="button" wire:click="closePaymentModal" class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Success Modal --}}
    @if($showSuccessModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" style="display: block !important;">
        <div class="fixed inset-0 bg-black/50 z-[9998]" wire:click="closeSuccessModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 z-[9999]" wire:click.stop>
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full mb-4" style="background-color: rgba(0, 152, 102, 0.1);">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #009866;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Success!</h3>
                    <p class="text-gray-600 mb-6">{{ $successMessage }}</p>
                    <button wire:click="closeSuccessModal" class="px-6 py-2 text-white font-medium rounded-lg transition-colors" style="background-color: #009866;" onmouseover="this.style.backgroundColor='#007a52'" onmouseout="this.style.backgroundColor='#009866'">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Error Modal --}}
    @if($showErrorModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" style="display: block !important;">
        <div class="fixed inset-0 bg-black/50 z-[9998]" wire:click="closeErrorModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 z-[9999]" wire:click.stop>
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Error</h3>
                    <p class="text-gray-600 mb-6">{{ $errorMessage }}</p>
                    <button wire:click="closeErrorModal" class="px-6 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

@script
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('message-sent', () => {
            const chatMessages = document.getElementById('chat-messages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        });
    });

    // Auto-scroll to bottom when new messages arrive
    document.addEventListener('livewire:update', () => {
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
</script>
@endscript

