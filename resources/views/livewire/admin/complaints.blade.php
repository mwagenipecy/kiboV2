<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-6 sm:p-8 border-b border-gray-100">
        <h1 class="text-2xl font-bold text-gray-900">Complaints</h1>
        <p class="mt-1 text-gray-600">
            @if($isAdmin)
                Summary by date range. Resolve or assign to a team member.
            @else
                Complaints assigned to you. Resolve or reassign as needed.
            @endif
        </p>
    </div>

    @if($isAdmin)
        {{-- Summary cards --}}
        <div class="px-6 sm:px-8 py-6 bg-gray-50/50 border-b border-gray-100">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $summary['total'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pending</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1">{{ $summary['pending'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">In progress</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $summary['in_progress'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Closed</p>
                    <p class="text-2xl font-bold text-gray-600 mt-1">{{ $summary['closed'] }}</p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="mt-6 flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">From date</label>
                    <input type="date" wire:model.live="dateFrom" class="rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-[#009866] focus:ring-2 focus:ring-[#009866]/20">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">To date</label>
                    <input type="date" wire:model.live="dateTo" class="rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-[#009866] focus:ring-2 focus:ring-[#009866]/20">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Status</label>
                    <select wire:model.live="statusFilter" class="rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-[#009866] focus:ring-2 focus:ring-[#009866]/20">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In progress</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Category</label>
                    <select wire:model.live="categoryFilter" class="rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-[#009866] focus:ring-2 focus:ring-[#009866]/20">
                        <option value="">All</option>
                        @foreach(\App\Models\Complaint::CATEGORIES as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @else
        <div class="px-6 sm:px-8 py-4 border-b border-gray-100 flex flex-wrap gap-4">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Status</span>
                <select wire:model.live="statusFilter" class="rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-[#009866] focus:ring-2 focus:ring-[#009866]/20">
                    <option value="">All</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In progress</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Complaint #</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Subject / Contact</th>
                    @if($isAdmin)
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Assigned to</th>
                    @endif
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($complaints as $c)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4 font-mono text-sm font-semibold text-[#009866]">{{ $c->complaint_number }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $c->subject }}</div>
                            <div class="text-sm text-gray-500">{{ $c->name }} · {{ $c->email }}</div>
                        </td>
                        @if($isAdmin)
                            <td class="px-6 py-4 text-sm text-gray-700">{{ \App\Models\Complaint::CATEGORIES[$c->category] ?? $c->category }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $c->assignedTo?->name ?? '—' }}</td>
                        @endif
                        <td class="px-6 py-4">
                            @if($c->status === 'closed')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">Closed</span>
                            @elseif($c->status === 'in_progress')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-800">In progress</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-amber-100 text-amber-800">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $c->created_at->format('M j, Y H:i') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.complaints.view', ['id' => $c->id]) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium text-[#009866] hover:bg-[#009866]/10 transition-colors">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $isAdmin ? 7 : 5 }}" class="px-6 py-14 text-center text-gray-500">
                            @if($isAdmin)
                                No complaints found for the selected filters.
                            @else
                                No complaints assigned to you.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
