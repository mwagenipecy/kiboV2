@extends('layouts.admin')

@section('title', 'Activity Log - Admin Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Activity Log</h1>
        <p class="mt-2 text-sm text-gray-600">Audit trail of successful user logins.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <p class="text-sm font-medium text-gray-600">Filtered Logins</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalLogins) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <p class="text-sm font-medium text-gray-600">Unique Users</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($uniqueUsers) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <p class="text-sm font-medium text-gray-600">Current Page Results</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($activities->count()) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <p class="text-sm font-medium text-gray-600">Last Login At</p>
            <p class="text-base font-semibold text-gray-900 mt-2">
                {{ $lastLoginAt ? \Illuminate\Support\Carbon::parse($lastLoginAt)->format('Y-m-d H:i:s') : 'N/A' }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Login Activity</h2>
                <p class="text-sm text-gray-600 mt-1">Paginated audit records of user logins.</p>
            </div>

            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <form method="GET" action="{{ route('admin.activity-log') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
                    <div class="xl:col-span-2">
                        <label for="user_id" class="block text-sm font-bold uppercase tracking-wide text-gray-700 mb-2">User</label>
                        <select id="user_id" name="user_id" class="w-full h-12 rounded-xl border-2 border-gray-500 bg-white px-3 text-base text-gray-800 shadow-sm focus:border-[#009866] focus:ring-[#009866] focus:ring-2">
                            <option value="">All users</option>
                            @foreach($filterUsers as $user)
                                <option value="{{ $user->id }}" @selected((string) ($filters['user_id'] ?? '') === (string) $user->id)>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="specific_date" class="block text-sm font-bold uppercase tracking-wide text-gray-700 mb-2">Specific Date</label>
                        <input
                            id="specific_date"
                            type="date"
                            name="specific_date"
                            value="{{ $filters['specific_date'] ?? '' }}"
                            class="w-full h-12 rounded-xl border-2 border-gray-500 bg-white px-3 text-base text-gray-800 shadow-sm focus:border-[#009866] focus:ring-[#009866] focus:ring-2"
                        >
                    </div>

                    <div>
                        <label for="from_date" class="block text-sm font-bold uppercase tracking-wide text-gray-700 mb-2">From Date</label>
                        <input
                            id="from_date"
                            type="date"
                            name="from_date"
                            value="{{ $filters['from_date'] ?? '' }}"
                            class="w-full h-12 rounded-xl border-2 border-gray-500 bg-white px-3 text-base text-gray-800 shadow-sm focus:border-[#009866] focus:ring-[#009866] focus:ring-2"
                        >
                    </div>

                    <div>
                        <label for="to_date" class="block text-sm font-bold uppercase tracking-wide text-gray-700 mb-2">To Date</label>
                        <input
                            id="to_date"
                            type="date"
                            name="to_date"
                            value="{{ $filters['to_date'] ?? '' }}"
                            class="w-full h-12 rounded-xl border-2 border-gray-500 bg-white px-3 text-base text-gray-800 shadow-sm focus:border-[#009866] focus:ring-[#009866] focus:ring-2"
                        >
                    </div>

                    <div class="xl:col-span-5 flex items-center gap-3">
                        <button type="submit" class="inline-flex items-center justify-center h-11 px-5 rounded-xl bg-[#009866] text-white text-sm font-semibold hover:bg-[#007a52] transition-colors shadow-sm">
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.activity-log') }}" class="inline-flex items-center justify-center h-11 px-5 rounded-xl border-2 border-gray-400 bg-white text-gray-700 text-sm font-semibold hover:bg-gray-100 transition-colors">
                            Reset
                        </a>
                        <p class="text-sm text-gray-600">If specific date is selected, date range is ignored.</p>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Login Time</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User Agent</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($activities as $activity)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">
                                    <p class="font-medium text-gray-900">{{ $activity->user?->name ?? 'Deleted User' }}</p>
                                    <p class="text-gray-500">{{ $activity->user?->email ?? 'N/A' }}</p>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 capitalize">{{ $activity->user?->role ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $activity->logged_in_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $activity->ip_address ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-xs text-gray-600 max-w-xs truncate" title="{{ $activity->user_agent }}">{{ $activity->user_agent ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">No login activity recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-200">
                {{ $activities->links() }}
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Top Active Users</h2>
                <p class="text-sm text-gray-600 mt-1">Users with highest login counts.</p>
            </div>
            <div class="p-6 space-y-4">
                @forelse($topUsers as $item)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <p class="text-sm font-semibold text-gray-900">{{ $item->user?->name ?? 'Deleted User' }}</p>
                        <p class="text-xs text-gray-500">{{ $item->user?->email ?? 'N/A' }}</p>
                        <div class="mt-2 text-sm text-gray-700">
                            <p>Logins: <span class="font-semibold">{{ number_format($item->login_count) }}</span></p>
                            <p>Last Login: <span class="font-semibold">{{ \Illuminate\Support\Carbon::parse($item->last_login_at)->format('Y-m-d H:i:s') }}</span></p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No user activity yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
