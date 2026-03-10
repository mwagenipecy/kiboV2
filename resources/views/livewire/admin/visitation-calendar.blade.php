<div class="p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Visitation calendar</h1>
            <p class="text-gray-600">View scheduled visitations and manage them from the calendar.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.visitations') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium">List view</a>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <button wire:click="previousMonth" class="p-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">←</button>
                <button wire:click="nextMonth" class="p-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">→</button>
                <button wire:click="goToToday" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">Today</button>
            </div>
            <h2 class="text-xl font-bold text-gray-900">{{ $monthName }}</h2>
        </div>

        <div class="grid grid-cols-7 gap-px bg-gray-200 rounded-lg overflow-hidden">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="bg-gray-50 py-2 text-center text-xs font-semibold text-gray-600">{{ $day }}</div>
            @endforeach
            @foreach($calendarDays as $day)
                <div class="bg-white min-h-[100px] p-2 {{ !$day['isCurrentMonth'] ? 'opacity-50' : '' }} {{ $day['isToday'] ? 'ring-2 ring-green-500 ring-inset' : '' }}">
                    <div class="text-sm font-medium {{ $day['isToday'] ? 'text-green-700' : 'text-gray-700' }}">{{ $day['date']->format('j') }}</div>
                    <div class="mt-1 space-y-1">
                        @foreach($day['visitations'] as $v)
                            <a href="{{ route('admin.visitations.view', $v->id) }}" class="block text-xs px-2 py-1 rounded bg-blue-100 text-blue-800 hover:bg-blue-200 truncate" title="{{ $v->name }} – {{ $v->vehicle->make?->name }} {{ $v->vehicle->model?->name }}">
                                {{ $v->scheduled_at->format('g:i') }} {{ $v->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Legend</h2>
        <ul class="text-sm text-gray-600 space-y-1">
            <li><span class="inline-block w-3 h-3 rounded bg-blue-100 border border-blue-300 mr-2"></span> Scheduled visitation (click to view/reschedule/cancel/mark completed)</li>
            <li><span class="inline-block w-3 h-3 rounded ring-2 ring-green-500 ring-inset mr-2"></span> Today</li>
        </ul>
    </div>
</div>
