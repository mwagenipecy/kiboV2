@php
    $fmt = function ($n, $currency = 'TZS') {
        return number_format($n) . ' ' . $currency;
    };
    $maxDay = collect($byDate)->map(fn ($v) => $v['paid'] + $v['unpaid'])->max() ?: 1;
@endphp
<div class="w-full" x-data="{ copiedLink: null }" x-init="window.paytrackCopy = (url) => { navigator.clipboard.writeText(url || ''); copiedLink = url; setTimeout(() => copiedLink = null, 2000); }">
    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4 flex flex-wrap items-center gap-4">
        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Date range</span>
        <div class="flex items-center gap-2">
            <input type="date" wire:model.live="dateFrom" class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 focus:ring-2 focus:ring-[#009866] focus:border-[#009866]" />
            <span class="text-gray-400">→</span>
            <input type="date" wire:model.live="dateTo" class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 focus:ring-2 focus:ring-[#009866] focus:border-[#009866]" />
        </div>
        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</span>
        <select wire:model.live="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 focus:ring-2 focus:ring-[#009866] focus:border-[#009866]">
            <option value="all">All</option>
            <option value="paid">Paid</option>
            <option value="partial">Partial</option>
            <option value="unpaid">Unpaid</option>
        </select>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search customer, reference..." class="border border-gray-300 rounded-lg px-3 py-2 text-sm min-w-[180px] focus:ring-2 focus:ring-[#009866] focus:border-[#009866]" />
        <span class="text-sm text-gray-500">{{ $filteredLinks->total() }} results</span>
    </div>

    @if($section === 'overview')
        {{-- Stat cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Total amount</div>
                <div class="text-2xl font-bold text-gray-900">{{ $fmt($stats['total']) }}</div>
                <div class="text-sm text-gray-500 mt-1">{{ $stats['links'] }} links</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Paid</div>
                <div class="text-2xl font-bold text-[#009866]">{{ $fmt($stats['paid']) }}</div>
                <div class="text-sm text-gray-500 mt-1">{{ $stats['paid_count'] }} paid</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Unpaid</div>
                <div class="text-2xl font-bold text-red-600">{{ $fmt($stats['unpaid']) }}</div>
                <div class="text-sm text-gray-500 mt-1">{{ $stats['unpaid_count'] }} unpaid · {{ $stats['partial_count'] }} partial</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Links generated</div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['links'] }}</div>
                <div class="text-sm text-gray-500 mt-1">in date range</div>
            </div>
        </div>

        {{-- Collection rate --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 mb-6">
            <div class="flex justify-between items-center mb-3">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Collection rate</span>
                <span class="text-xl font-bold text-[#009866]">{{ $stats['total'] > 0 ? (int) round(($stats['paid'] / $stats['total']) * 100) : 0 }}%</span>
            </div>
            <div class="bg-gray-200 rounded-lg h-2.5 overflow-hidden">
                <div class="h-full rounded-lg bg-gradient-to-r from-[#009866] to-[#007a52] transition-all duration-500" style="width: {{ $stats['total'] > 0 ? ($stats['paid'] / $stats['total']) * 100 : 0 }}%;"></div>
            </div>
            <div class="flex justify-between mt-2 text-xs text-gray-500">
                <span>Paid {{ $fmt($stats['paid']) }}</span>
                <span>Unpaid {{ $fmt($stats['unpaid']) }}</span>
            </div>
        </div>

        {{-- Daily volume --}}
        @if(count($byDate) > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 mb-6">
            <div class="flex justify-between items-center mb-4">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Daily volume</span>
                <div class="flex gap-4">
                    <div class="flex items-center gap-1.5"><div class="w-2 h-2 rounded bg-[#009866]"></div><span class="text-xs text-gray-500">Paid</span></div>
                    <div class="flex items-center gap-1.5"><div class="w-2 h-2 rounded bg-red-500"></div><span class="text-xs text-gray-500">Unpaid</span></div>
                </div>
            </div>
            <div class="flex gap-3 items-end" style="height: 120px;">
                @foreach($byDate as $date => $vals)
                @php
                    $paidH = $maxDay > 0 ? ($vals['paid'] / $maxDay) * 100 : 0;
                    $unpaidH = $maxDay > 0 ? ($vals['unpaid'] / $maxDay) * 100 : 0;
                @endphp
                <div class="flex-1 flex flex-col items-center gap-1 min-w-0">
                    <div class="w-full flex flex-col justify-end gap-0.5" style="height: 90px;">
                        @if($vals['paid'] > 0)<div class="w-full rounded-t min-h-[4px] bg-[#009866]" style="height: {{ $paidH }}%;"></div>@endif
                        @if($vals['unpaid'] > 0)<div class="w-full rounded-t min-h-[4px] bg-red-500" style="height: {{ $unpaidH }}%;"></div>@endif
                    </div>
                    <span class="text-[10px] text-gray-500">{{ substr($date, 5) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Recent payment links --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Recent payment links</span>
                <a href="{{ route('admin.payment-links.transactions') }}" class="text-sm text-[#009866] hover:text-[#007a52] font-medium">View all →</a>
            </div>
            @forelse($filteredLinks->getCollection()->take(5) as $link)
            @php $status = $link->overall_payment_status; @endphp
            <a href="{{ route('admin.payment-links.show', $link->id) }}" class="flex items-center gap-3 px-5 py-3.5 border-b border-gray-100 hover:bg-gray-50 transition-colors last:border-0">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0 {{ $status === 'paid' ? 'bg-green-100 text-[#009866]' : ($status === 'partial' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-600') }}">{{ $status === 'paid' ? '✓' : ($status === 'partial' ? '◐' : '○') }}</div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-900">{{ $link->customer_name ?? '—' }}</div>
                    <div class="text-xs text-gray-500">{{ $link->customer_reference ?? '—' }} · {{ $link->created_at->format('Y-m-d') }}</div>
                </div>
                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full {{ $status === 'paid' ? 'bg-green-100 text-[#007a52]' : ($status === 'partial' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">{{ $status }}</span>
                <span class="font-semibold text-gray-900 min-w-[90px] text-right">{{ $fmt($link->total_amount, $link->currency) }}</span>
            </a>
            @empty
            <div class="px-5 py-12 text-center text-gray-500 text-sm">No payment links in this range.</div>
            @endforelse
        </div>
    @endif

    @if($section === 'transactions')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-2">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">All payment links — {{ $filteredLinks->total() }} results</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($filteredLinks as $link)
                        @php $status = $link->overall_payment_status; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-500">#{{ $link->id }}</td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $link->customer_name ?? '—' }}</div>
                                <div class="text-xs text-gray-500">{{ $link->created_at->format('Y-m-d H:i') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-[#009866]">{{ $link->short_code ?? $link->link_id ?? '—' }}</span>
                                    @if($link->payment_url)
                                    <button type="button" class="text-xs px-2 py-1 border border-gray-300 rounded text-gray-600 hover:border-[#009866] hover:text-[#009866] transition-colors" data-url="{{ $link->payment_url }}" @click="paytrackCopy($el.getAttribute('data-url'))" :class="{ 'border-[#009866] text-[#009866]': copiedLink === $el.getAttribute('data-url') }" x-text="copiedLink === $el.getAttribute('data-url') ? '✓' : 'copy'"></button>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full {{ $status === 'paid' ? 'bg-green-100 text-[#007a52]' : ($status === 'partial' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">{{ $status }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="font-semibold {{ $status === 'paid' ? 'text-[#009866]' : ($status === 'partial' ? 'text-amber-700' : 'text-red-600') }}">{{ $fmt($link->total_amount, $link->currency) }}</span>
                                <a href="{{ route('admin.payment-links.show', $link->id) }}" class="block text-xs text-[#009866] hover:text-[#007a52] mt-0.5">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-5 py-12 text-center text-gray-500 text-sm">No payment links match the filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 border-t border-gray-200">
                {{ $filteredLinks->links() }}
            </div>
        </div>
    @endif

    @if($section === 'links')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-200">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Payment links — {{ $filteredLinks->total() }} generated</span>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($filteredLinks as $link)
                @php
                    $status = $link->overall_payment_status;
                    $pct = $link->total_amount > 0 ? ($link->total_paid_amount / $link->total_amount) * 100 : 0;
                @endphp
                <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50 transition-colors">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center text-lg flex-shrink-0 bg-gray-100 text-[#009866]">⌁</div>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('admin.payment-links.show', $link->id) }}" class="text-sm font-medium text-[#009866] hover:text-[#007a52]">{{ $link->short_code ?? $link->link_id ?? 'Link #'.$link->id }}</a>
                        <div class="bg-gray-200 rounded h-1.5 overflow-hidden mt-1">
                            <div class="h-full rounded bg-[#009866] transition-all duration-500" style="width: {{ $pct }}%;"></div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold text-gray-900">{{ $fmt($link->total_amount, $link->currency) }}</div>
                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full mt-1 {{ $status === 'paid' ? 'bg-green-100 text-[#007a52]' : ($status === 'partial' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">{{ $status }}</span>
                    </div>
                    @if($link->payment_url)
                    <button type="button" class="text-xs px-2 py-1 border border-gray-300 rounded text-gray-600 hover:border-[#009866] hover:text-[#009866] shrink-0" data-url="{{ $link->payment_url }}" @click="paytrackCopy($el.getAttribute('data-url'))" :class="{ 'border-[#009866] text-[#009866]': copiedLink === $el.getAttribute('data-url') }" x-text="copiedLink === $el.getAttribute('data-url') ? '✓ copied' : 'copy link'"></button>
                    @endif
                </div>
                @empty
                <div class="px-5 py-12 text-center text-gray-500 text-sm">No payment links in this range.</div>
                @endforelse
            </div>
            <div class="px-5 py-3 border-t border-gray-200">
                {{ $filteredLinks->links() }}
            </div>
        </div>
    @endif

    @if($section === 'log')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Generation log — successful & failed</span>
                <span class="text-sm text-gray-500">Last 50 attempts</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer / Ref</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($generationLogs as $log)
                        @php $ref = $log->request_payload['customer_reference'] ?? '—'; $name = $log->request_payload['customer_name'] ?? '—'; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full {{ $log->success ? 'bg-green-100 text-[#007a52]' : 'bg-red-100 text-red-700' }}">{{ $log->success ? 'Success' : 'Failed' }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $name }} <span class="text-gray-500">({{ $ref }})</span></td>
                            <td class="px-4 py-3 text-xs text-gray-500 font-mono">{{ $log->request_id ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($log->success && $log->payment_link_id)
                                <a href="{{ route('admin.payment-links.show', $log->payment_link_id) }}" class="text-[#009866] hover:text-[#007a52]">View link</a>
                                @endif
                                @if(!$log->success && $log->error_message)
                                <span class="text-red-600" title="{{ $log->error_message }}">{{ Str::limit($log->error_message, 40) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-5 py-12 text-center text-gray-500 text-sm">No generation attempts logged yet. Generate a link via the API to see entries here.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
