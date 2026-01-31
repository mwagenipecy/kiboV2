<div>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Reports Management</h1>
            <p class="text-gray-600">Review and manage reports from different sections of the platform</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Section</label>
                    <select wire:model.live="selectedSection" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="all">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section }}">{{ ucfirst($section) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select wire:model.live="selectedStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="all">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="reviewed">Reviewed</option>
                        <option value="resolved">Resolved</option>
                        <option value="dismissed">Dismissed</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by reason, description, reporter name or email..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pending Reports</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($reports as $report)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">{{ ucfirst($report->section) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">{{ $report->reporter_name ?? $report->reporter->name ?? 'Anonymous' }}</div>
                                        <div class="text-gray-500">{{ $report->reporter_email ?? $report->reporter->email ?? '' }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $report->reason }}</div>
                                    @if($report->description)
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($report->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'reviewed' => 'bg-blue-100 text-blue-800',
                                            'resolved' => 'bg-green-100 text-green-800',
                                            'dismissed' => 'bg-gray-100 text-gray-800',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$report->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $report->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="viewReport({{ $report->id }})" class="text-emerald-600 hover:text-emerald-900">View</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    No reports found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reports->links() }}
            </div>
        </div>
    </div>

    <!-- Report Detail Modal -->
    @if($showReportModal && $selectedReport)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 bg-opacity-50" wire:click="closeModal">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900">Report Details</h2>
                <button wire:click="closeModal" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-full">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <!-- Report Info -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                        <p class="text-gray-900">{{ ucfirst($selectedReport->section) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'reviewed' => 'bg-blue-100 text-blue-800',
                                'resolved' => 'bg-green-100 text-green-800',
                                'dismissed' => 'bg-gray-100 text-gray-800',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$selectedReport->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($selectedReport->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reporter</label>
                        <p class="text-gray-900">{{ $selectedReport->reporter_name ?? $selectedReport->reporter->name ?? 'Anonymous' }}</p>
                        <p class="text-sm text-gray-500">{{ $selectedReport->reporter_email ?? $selectedReport->reporter->email ?? '' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reported Date</label>
                        <p class="text-gray-900">{{ $selectedReport->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                <!-- Reason -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                    <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $selectedReport->reason)) }}</p>
                </div>

                <!-- Description -->
                @if($selectedReport->description)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $selectedReport->description }}</p>
                </div>
                @endif

                <!-- Admin Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Admin Notes</label>
                    <textarea wire:model.live.debounce.500ms="adminNotes" wire:blur="updateAdminNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Add notes about this report..."></textarea>
                </div>

                <!-- Status Actions -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button wire:click="updateStatus({{ $selectedReport->id }}, 'reviewed')" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        Mark as Reviewed
                    </button>
                    <button wire:click="updateStatus({{ $selectedReport->id }}, 'resolved')" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        Mark as Resolved
                    </button>
                    <button wire:click="updateStatus({{ $selectedReport->id }}, 'dismissed')" class="flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                        Dismiss
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('message'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('message') }}
    </div>
    @endif
</div>
