@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
        <p class="mt-2 text-gray-600">Manage your account settings and preferences</p>
    </div>

    <!-- Settings Tabs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px" aria-label="Tabs">
                <button onclick="showTab('profile')" id="tab-profile" class="tab-button active px-6 py-4 text-sm font-medium border-b-2 border-green-500 text-green-600">
                    Profile
                </button>
                <button onclick="showTab('password')" id="tab-password" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Password
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Profile Tab -->
            <div id="content-profile" class="tab-content">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Profile Information</h2>
                    <p class="text-sm text-gray-600">Update your name and email address</p>
                </div>
                @livewire('settings.profile')
            </div>

            <!-- Password Tab -->
            <div id="content-password" class="tab-content hidden">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Update Password</h2>
                    <p class="text-sm text-gray-600">Ensure your account is using a strong, unique password</p>
                </div>
                @livewire('settings.password')
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'border-green-500', 'text-green-600');
            button.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Show selected tab content
        document.getElementById('content-' + tabName).classList.remove('hidden');
        
        // Add active class to selected tab
        const activeTab = document.getElementById('tab-' + tabName);
        activeTab.classList.add('active', 'border-green-500', 'text-green-600');
        activeTab.classList.remove('border-transparent', 'text-gray-500');
    }
</script>
@endpush
@endsection

