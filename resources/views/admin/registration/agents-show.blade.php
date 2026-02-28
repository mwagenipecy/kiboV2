@extends('layouts.admin')

@section('title', 'View Agent - Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('admin.registration.agents') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Agent Details</h1>
                <p class="mt-1 text-sm text-gray-600">{{ $agent->name }}</p>
            </div>
            <a 
                href="{{ route('admin.registration.agents.edit', $agent->id) }}" 
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center font-medium"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Agent
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
        </div>
        <div class="px-6 py-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">ID</dt>
                    <dd class="mt-1 text-sm text-gray-900">#{{ $agent->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->phone_number ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Agent Type</dt>
                    <dd class="mt-1">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $agentTypes[$agent->agent_type] ?? $agent->agent_type }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->company_name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        @if ($agent->status === 'active')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Approval Status</dt>
                    <dd class="mt-1">
                        @if ($agent->approval_status === 'approved')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                        @elseif ($agent->approval_status === 'rejected')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                        @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">License Number</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->license_number ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        @if(in_array($agent->agent_type, ['garage_owner', 'spare_part']) && !empty($vehicleMakeNames))
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Vehicle Makes</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($vehicleMakeNames as $name)
                    <span class="px-3 py-1 bg-white border border-gray-200 rounded-lg text-sm text-gray-700">{{ $name }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @if($agent->agent_type === 'garage_owner' && !empty($agent->services))
        <div class="px-6 py-4 border-t border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Services Offered</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($agent->services as $serviceKey)
                    <span class="px-3 py-1 bg-green-50 text-green-800 rounded-lg text-sm font-medium">{{ $serviceLabels[$serviceKey] ?? $serviceKey }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @if($agent->agent_type === 'spare_part')
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Spare Part Details</h2>
            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $agent->spare_part_details ?: '-' }}</p>
            <div class="mt-3">
                <dt class="text-sm font-medium text-gray-500">Support logistics</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $agent->support_logistics ? 'Yes' : 'No' }}</dd>
            </div>
        </div>
        @endif

        <div class="px-6 py-4 border-t border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Address & Location</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                    <dd class="mt-1 text-sm text-gray-700">{{ $agent->address ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Latitude</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->latitude ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Longitude</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->longitude ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Record Info</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->created_at?->format('M j, Y g:i A') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last updated</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->updated_at?->format('M j, Y g:i A') ?? '-' }}</dd>
                </div>
                @if($agent->approved_at)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Approved at</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $agent->approved_at->format('M j, Y g:i A') }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
@endsection
