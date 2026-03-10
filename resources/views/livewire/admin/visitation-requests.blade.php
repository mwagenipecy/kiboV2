<div class="p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Car visitation requests</h1>
            <p class="text-gray-600">View and schedule customer visitations to see vehicles.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.visitations.calendar') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium">
                Calendar
            </a>
        </div>
    </div>

    {{-- Summary cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <button wire:click="$set('filter', 'all')" class="rounded-xl border-2 p-4 text-left transition-colors {{ $filter === 'all' ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white hover:border-gray-300 hover:shadow-sm' }}">
            <p class="text-sm font-medium text-gray-600">All</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ $counts['all'] }}</p>
        </button>
        <button wire:click="$set('filter', 'pending')" class="rounded-xl border-2 p-4 text-left transition-colors {{ $filter === 'pending' ? 'border-amber-500 bg-amber-50' : 'border-gray-200 bg-white hover:border-gray-300 hover:shadow-sm' }}">
            <p class="text-sm font-medium text-gray-600">Pending</p>
            <p class="mt-1 text-2xl font-bold text-amber-700">{{ $counts['pending'] }}</p>
        </button>
        <button wire:click="$set('filter', 'scheduled')" class="rounded-xl border-2 p-4 text-left transition-colors {{ $filter === 'scheduled' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white hover:border-gray-300 hover:shadow-sm' }}">
            <p class="text-sm font-medium text-gray-600">Scheduled</p>
            <p class="mt-1 text-2xl font-bold text-blue-700">{{ $counts['scheduled'] }}</p>
        </button>
        <button wire:click="$set('filter', 'completed')" class="rounded-xl border-2 p-4 text-left transition-colors {{ $filter === 'completed' ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white hover:border-gray-300 hover:shadow-sm' }}">
            <p class="text-sm font-medium text-gray-600">Completed</p>
            <p class="mt-1 text-2xl font-bold text-green-700">{{ $counts['completed'] }}</p>
        </button>
        <button wire:click="$set('filter', 'cancelled')" class="rounded-xl border-2 p-4 text-left transition-colors {{ $filter === 'cancelled' ? 'border-gray-500 bg-gray-100' : 'border-gray-200 bg-white hover:border-gray-300 hover:shadow-sm' }}">
            <p class="text-sm font-medium text-gray-600">Cancelled</p>
            <p class="mt-1 text-2xl font-bold text-gray-700">{{ $counts['cancelled'] }}</p>
        </button>
    </div>

    <div class="flex flex-wrap gap-2">
        <button wire:click="$set('filter', 'all')" class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'all' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            All
        </button>
        <button wire:click="$set('filter', 'pending')" class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'pending' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Pending
        </button>
        <button wire:click="$set('filter', 'scheduled')" class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'scheduled' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Scheduled
        </button>
        <button wire:click="$set('filter', 'completed')" class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'completed' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Completed
        </button>
        <button wire:click="$set('filter', 'cancelled')" class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'cancelled' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Cancelled
        </button>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle / Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($requests as $r)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">
                                {{ $r->vehicle->make?->name }} {{ $r->vehicle->model?->name }} ({{ $r->vehicle->year }})
                            </div>
                            <div class="text-sm text-gray-600">{{ $r->name }}</div>
                            @if($r->visit_reason)
                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($r->visit_reason, 40) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $r->email }}<br>
                            @if($r->phone) <span class="text-gray-500">{{ $r->phone }}</span> @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($r->status === 'pending')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-amber-100 text-amber-800 font-medium text-xs">Pending</span>
                            @elseif($r->status === 'scheduled')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-800 font-medium text-xs">Scheduled</span>
                            @elseif($r->status === 'completed')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800 font-medium text-xs">Completed</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 text-gray-800 font-medium text-xs">Cancelled</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($r->scheduled_at)
                                {{ $r->scheduled_at->format('M j, Y g:i A') }}
                                @if($r->location)<br><span class="text-xs text-gray-500">{{ Str::limit($r->location, 30) }}</span>@endif
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $r->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.visitations.view', $r->id) }}" class="text-green-600 hover:text-green-800 font-medium">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-600">No visitation requests yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
