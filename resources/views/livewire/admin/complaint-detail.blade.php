<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-6 sm:p-8 border-b border-gray-100 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Complaint {{ $complaint->complaint_number }}</h1>
            <p class="mt-1 text-gray-600">View details, resolve or assign to a team member.</p>
        </div>
        <a href="{{ route('admin.complaints') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium transition-colors">
            ← Back to list
        </a>
    </div>

    @if($successMessage)
        <div class="mx-6 sm:mx-8 mt-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm">
            {{ $successMessage }}
        </div>
    @endif

    <div class="p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Complaint details --}}
        <div class="bg-gray-50/50 rounded-xl border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-4">Details</h2>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="font-medium text-gray-500">Subject</dt>
                    <dd class="text-gray-900 mt-0.5">{{ $complaint->subject }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Category</dt>
                    <dd class="text-gray-900 mt-0.5">{{ \App\Models\Complaint::CATEGORIES[$complaint->category] ?? $complaint->category }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">From</dt>
                    <dd class="text-gray-900 mt-0.5">{{ $complaint->name }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Email</dt>
                    <dd class="text-gray-900 mt-0.5">{{ $complaint->email }}</dd>
                </div>
                @if($complaint->phone)
                    <div>
                        <dt class="font-medium text-gray-500">Phone</dt>
                        <dd class="text-gray-900 mt-0.5">{{ $complaint->phone }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="font-medium text-gray-500">Status</dt>
                    <dd class="mt-0.5">
                        @if($complaint->status === 'closed')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">Closed</span>
                        @elseif($complaint->status === 'in_progress')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-800">In progress</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-amber-100 text-amber-800">Pending</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Assigned to</dt>
                    <dd class="text-gray-900 mt-0.5">{{ $complaint->assignedTo?->name ?? '— Unassigned' }}</dd>
                </div>
                @if($complaint->assignedTo)
                    <div>
                        <dt class="font-medium text-gray-500">Assigned user email</dt>
                        <dd class="text-gray-900 mt-0.5">{{ $complaint->assignedTo->email }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="font-medium text-gray-500">Submitted</dt>
                    <dd class="text-gray-900 mt-0.5">{{ $complaint->created_at->format('M j, Y H:i') }}</dd>
                </div>
                @if($complaint->resolved_at)
                    <div>
                        <dt class="font-medium text-gray-500">Resolved at</dt>
                        <dd class="text-gray-900 mt-0.5">{{ $complaint->resolved_at->format('M j, Y H:i') }}</dd>
                    </div>
                @endif
            </dl>
            <div class="mt-5 pt-5 border-t border-gray-200">
                <dt class="font-medium text-gray-500 mb-1">Message</dt>
                <dd class="text-gray-900 whitespace-pre-wrap mt-1">{{ $complaint->message }}</dd>
            </div>
            @if($complaint->resolution_notes)
                <div class="mt-5 pt-5 border-t border-gray-200">
                    <dt class="font-medium text-gray-500 mb-1">Resolution notes</dt>
                    <dd class="text-gray-900 whitespace-pre-wrap mt-1">{{ $complaint->resolution_notes }}</dd>
                </div>
            @endif
        </div>

        {{-- Assigned user card (when assigned) --}}
        @if($complaint->assignedTo)
            <div class="bg-[#009866]/5 rounded-xl border border-[#009866]/20 p-6">
                <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-3">Assigned to</h2>
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#009866]/20 flex items-center justify-center text-[#009866] font-semibold">
                        {{ strtoupper(substr($complaint->assignedTo->name ?? '?', 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-medium text-gray-900">{{ $complaint->assignedTo->name }}</p>
                        <p class="text-sm text-gray-600 mt-0.5">{{ $complaint->assignedTo->email }}</p>
                        <p class="text-xs text-gray-500 mt-1 capitalize">{{ $complaint->assignedTo->role }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Actions: Resolve & Assign --}}
        @if($complaint->status !== 'closed' && ($canResolve || $canAssign))
            <div class="space-y-6">
                @if($canResolve)
                    <div class="bg-gray-50/50 rounded-xl border border-gray-200 p-6">
                        <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-4">Resolve complaint</h2>
                        <form wire:submit.prevent="resolve" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Resolution notes (optional)</label>
                                <textarea wire:model="resolutionNotes" rows="3" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-[#009866] focus:ring-2 focus:ring-[#009866]/20 placeholder-gray-400" placeholder="How was this resolved?"></textarea>
                                @error('resolutionNotes') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <button type="submit" wire:loading.attr="disabled" class="px-5 py-2.5 rounded-xl bg-gray-800 text-white text-sm font-medium hover:bg-gray-900 disabled:opacity-60 transition-colors">
                                <span wire:loading.remove wire:target="resolve">Mark as resolved</span>
                                <span wire:loading wire:target="resolve">Saving…</span>
                            </button>
                        </form>
                    </div>
                @endif

                @if($canAssign)
                    <div class="bg-gray-50/50 rounded-xl border border-gray-200 p-6">
                        <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-4">Assign to</h2>
                        <form wire:submit.prevent="assign" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">User</label>
                                <select wire:model="assignToUserId" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-[#009866] focus:ring-2 focus:ring-[#009866]/20">
                                    <option value="">Select user…</option>
                                    @foreach($assignableUsers as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }}) — {{ $u->role }}</option>
                                    @endforeach
                                </select>
                                @error('assignToUserId') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <button type="submit" wire:loading.attr="disabled" class="px-5 py-2.5 rounded-xl bg-[#009866] text-white text-sm font-medium hover:bg-[#007a52] disabled:opacity-60 transition-colors">
                                <span wire:loading.remove wire:target="assign">Assign</span>
                                <span wire:loading wire:target="assign">Assigning…</span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @elseif($complaint->status === 'closed')
            <div class="bg-gray-50/50 rounded-xl border border-gray-200 p-6 flex items-center justify-center">
                <p class="text-gray-500 text-sm">This complaint has been closed.</p>
            </div>
        @endif
    </div>
</div>
