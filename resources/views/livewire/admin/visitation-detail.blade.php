<div class="p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Visitation request</h1>
            <p class="text-gray-600">Schedule, reschedule, or update this visitation.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.visitations.calendar') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Calendar</a>
            <a href="{{ route('admin.visitations') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Back to list</a>
        </div>
    </div>

    @if (session()->has('visitation_message'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('visitation_message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Request details</h2>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="font-medium text-gray-700">Vehicle</dt>
                    <dd class="text-gray-900">
                        <a href="{{ route('cars.detail', $visitation->vehicle->public_id) }}" target="_blank" class="text-green-600 hover:text-green-800">{{ $visitation->vehicle->make?->name }} {{ $visitation->vehicle->model?->name }} ({{ $visitation->vehicle->year }})</a>
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-700">Customer</dt>
                    <dd class="text-gray-900">{{ $visitation->name }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-700">Email</dt>
                    <dd class="text-gray-900">{{ $visitation->email }}</dd>
                </div>
                @if($visitation->phone)
                <div>
                    <dt class="font-medium text-gray-700">Phone</dt>
                    <dd class="text-gray-900">{{ $visitation->phone }}</dd>
                </div>
                @endif
                @if($visitation->visit_reason)
                <div>
                    <dt class="font-medium text-gray-700">Reason for visit</dt>
                    <dd class="text-gray-900">{{ $visitation->visit_reason }}</dd>
                </div>
                @endif
                <div>
                    <dt class="font-medium text-gray-700">Status</dt>
                    <dd>
                        @if($visitation->status === 'pending')
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-amber-100 text-amber-800 font-medium text-xs">Pending</span>
                        @elseif($visitation->status === 'scheduled')
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-800 font-medium text-xs">Scheduled</span>
                        @elseif($visitation->status === 'completed')
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800 font-medium text-xs">Completed</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 text-gray-800 font-medium text-xs">Cancelled</span>
                        @endif
                    </dd>
                </div>
                @if($visitation->scheduled_at)
                <div>
                    <dt class="font-medium text-gray-700">Scheduled at</dt>
                    <dd class="text-gray-900">{{ $visitation->scheduled_at->format('l, F j, Y \a\t g:i A') }}</dd>
                </div>
                @endif
                @if($visitation->location)
                <div>
                    <dt class="font-medium text-gray-700">Location</dt>
                    <dd class="text-gray-900">{{ $visitation->location }}</dd>
                </div>
                @endif
                @if($visitation->admin_notes)
                <div>
                    <dt class="font-medium text-gray-700">Admin notes</dt>
                    <dd class="text-gray-900">{{ $visitation->admin_notes }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <div class="space-y-6">
            @if($showScheduleForm)
                <div class="bg-white border border-gray-200 rounded-2xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $visitation->status === 'pending' ? 'Schedule visitation' : 'Reschedule' }}</h2>
                    <form wire:submit="{{ $visitation->status === 'pending' ? 'schedule' : 'updateSchedule' }}" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="scheduledDate" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                            @error('scheduledDate') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Time <span class="text-red-500">*</span></label>
                            <input type="time" wire:model="scheduledTime" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                            @error('scheduledTime') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input type="text" wire:model="location" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Address or meeting point">
                            @error('location') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes (included in email to customer)</label>
                            <textarea wire:model="adminNotes" rows="3" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Optional notes for the customer"></textarea>
                            @error('adminNotes') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="px-5 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700">
                                {{ $visitation->status === 'pending' ? 'Schedule & send email' : 'Update & resend email' }}
                            </button>
                            <button type="button" wire:click="$set('showScheduleForm', false)" class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Cancel</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white border border-gray-200 rounded-2xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                    <div class="flex flex-wrap gap-3">
                        @if($visitation->status === 'pending')
                            <button wire:click="reschedule" class="px-4 py-2 rounded-lg bg-green-600 text-white font-medium hover:bg-green-700">Schedule visit</button>
                        @endif
                        @if($visitation->status === 'scheduled')
                            <button wire:click="reschedule" class="px-4 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700">Reschedule</button>
                            <button wire:click="openCompleteModal" class="px-4 py-2 rounded-lg bg-green-600 text-white font-medium hover:bg-green-700">Mark as completed</button>
                            <button wire:click="openCancelModal" class="px-4 py-2 rounded-lg border border-red-300 text-red-700 font-medium hover:bg-red-50">Cancel visitation</button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Mark as completed confirmation modal --}}
    @if($showCompleteModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog">
        <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="closeCompleteModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6" wire:click.stop>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Mark as completed</h3>
                <p class="text-gray-600 mb-6">Mark this visitation as completed? This will update the status and the visitation will appear in the completed list.</p>
                <div class="flex gap-3 justify-end">
                    <button type="button" wire:click="closeCompleteModal" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50">Cancel</button>
                    <button type="button" wire:click="markCompleted" class="px-4 py-2 rounded-lg bg-green-600 text-white font-medium hover:bg-green-700">Mark as completed</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Cancel visitation confirmation modal --}}
    @if($showCancelModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog">
        <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="closeCancelModal"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6" wire:click.stop>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Cancel visitation</h3>
                <p class="text-gray-600 mb-6">Cancel this visitation? The customer will not be notified automatically.</p>
                <div class="flex gap-3 justify-end">
                    <button type="button" wire:click="closeCancelModal" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50">Keep</button>
                    <button type="button" wire:click="cancel" class="px-4 py-2 rounded-lg border border-red-300 text-red-700 font-medium hover:bg-red-50">Cancel visitation</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
