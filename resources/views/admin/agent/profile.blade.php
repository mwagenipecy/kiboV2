@extends('layouts.admin')

@section('title', 'My Shop Profile')

@section('content')
<div class="min-w-0">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">My Shop Profile</h1>
        <p class="mt-2 text-sm text-gray-600">Manage your lubricant shop information.</p>
        <p class="mt-1 text-xs text-gray-500">Email and phone are controlled by admin and remain read-only.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <livewire:admin.agent-profile-settings />
    </div>
</div>
@endsection
