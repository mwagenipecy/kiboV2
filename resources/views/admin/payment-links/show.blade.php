@extends('layouts.admin')

@section('title', 'Payment Link #' . $paymentLink->id . ' - Admin')

@section('content')
@php
    $link = $paymentLink;
    $status = $link->overall_payment_status;
@endphp
<div class="mb-8">
    <a href="{{ route('admin.payment-links.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Payment Links
    </a>
    <h1 class="text-3xl font-bold text-gray-900">Payment Link #{{ $link->id }}</h1>
    <p class="mt-2 text-gray-600">{{ $link->description ?? 'Universal payment link' }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main card -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Link details</h2>
            </div>
            <dl class="px-6 py-4 divide-y divide-gray-200">
                <div class="py-3 flex justify-between text-sm">
                    <dt class="text-gray-500">Link ID / Short code</dt>
                    <dd class="font-medium text-gray-900">{{ $link->link_id ?? '—' }} / {{ $link->short_code ?? '—' }}</dd>
                </div>
                <div class="py-3 flex justify-between text-sm">
                    <dt class="text-gray-500">Customer</dt>
                    <dd class="font-medium text-gray-900">{{ $link->customer_name ?? '—' }} ({{ $link->customer_reference ?? '—' }})</dd>
                </div>
                <div class="py-3 flex justify-between text-sm">
                    <dt class="text-gray-500">Email / Phone</dt>
                    <dd class="text-gray-900">{{ $link->customer_email ?? '—' }} / {{ $link->customer_phone ?? '—' }}</dd>
                </div>
                <div class="py-3 flex justify-between text-sm">
                    <dt class="text-gray-500">Total amount</dt>
                    <dd class="font-medium text-gray-900">{{ number_format($link->total_amount) }} {{ $link->currency }}</dd>
                </div>
                <div class="py-3 flex justify-between text-sm">
                    <dt class="text-gray-500">Paid so far</dt>
                    <dd class="font-medium text-gray-900">{{ number_format($link->total_paid_amount) }} {{ $link->currency }}</dd>
                </div>
                <div class="py-3 flex justify-between text-sm">
                    <dt class="text-gray-500">Overall status</dt>
                    <dd>
                        @if($status === 'paid')
                            <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Paid</span>
                        @elseif($status === 'partial')
                            <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-800">Partial</span>
                        @else
                            <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Unpaid</span>
                        @endif
                    </dd>
                </div>
                @if($link->expires_at)
                <div class="py-3 flex justify-between text-sm">
                    <dt class="text-gray-500">Expires at</dt>
                    <dd class="text-gray-900">{{ $link->expires_at->format('M j, Y H:i') }}</dd>
                </div>
                @endif
                @if($link->payment_url)
                <div class="py-3 flex justify-between text-sm items-center">
                    <dt class="text-gray-500">Payment URL</dt>
                    <dd>
                        <a href="{{ $link->payment_url }}" target="_blank" rel="noopener" class="inline-flex px-3 py-1.5 text-sm font-medium rounded-lg text-white transition-colors" style="background-color: #009866;">Open pay link</a>
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Items -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Items ({{ $link->items->count() }})</h2>
            </div>
            <ul class="divide-y divide-gray-200">
                @foreach($link->items as $item)
                <li class="px-6 py-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item->product_service_name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->product_service_reference }} · {{ number_format($item->amount) }} {{ $link->currency }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-medium text-gray-700">Paid: {{ number_format($item->paid_amount) }}</span>
                            <br>
                            @if($item->payment_status === 'paid')
                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">Paid</span>
                            @elseif($item->payment_status === 'partial')
                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800">Partial</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-800">Unpaid</span>
                            @endif
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{-- Transactions list (for partial/paid links) --}}
@if(in_array($status, ['partial', 'paid']) || $link->transactions->isNotEmpty())
<div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h2 class="text-lg font-semibold text-gray-900">Transactions</h2>
        <p class="text-sm text-gray-500 mt-0.5">Payment events for this link</p>
    </div>
    @if($link->transactions->isEmpty())
    <div class="px-6 py-8 text-center text-gray-500 text-sm">
        No transactions recorded yet. Transactions will appear here when payments are received (e.g. via webhook).
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($link->transactions as $txn)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-sm text-gray-900">{{ $txn->paid_at ? $txn->paid_at->format('M j, Y H:i') : $txn->created_at->format('M j, Y H:i') }}</td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $txn->reference ?? '—' }}</td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $txn->payment_method ?? '—' }}</td>
                    <td class="px-6 py-3">
                        <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full {{ $txn->status === 'completed' ? 'bg-green-100 text-[#007a52]' : ($txn->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">{{ $txn->status }}</span>
                    </td>
                    <td class="px-6 py-3 text-sm font-medium text-right text-gray-900">{{ number_format($txn->amount) }} {{ $txn->currency }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endif

<div class="mt-4 text-sm text-gray-500">
    Created {{ $link->created_at->format('M j, Y H:i') }} · Request ID: {{ $link->api_request_id ?? '—' }}
</div>
@endsection
