@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Profile</h1>
        <p class="mt-2 text-gray-600">Manage your account information and preferences</p>
    </div>

    <!-- Profile Information Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Profile Information</h2>
                <p class="text-sm text-gray-600 mt-1">Update your name and email address</p>
            </div>
            <div class="flex items-center space-x-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=10b981&color=fff&size=128" 
                     alt="{{ auth()->user()->name }}" 
                     class="w-16 h-16 rounded-full border-2 border-green-500">
            </div>
        </div>

        <!-- Profile Form (Delete Account Section Hidden) -->
        <div class="admin-profile-wrapper">
            @livewire('settings.profile')
        </div>
        
        @push('styles')
        <style>
            /* Hide delete account section in admin profile */
            .admin-profile-wrapper div.mt-8.pt-8.border-t,
            .admin-profile-wrapper form + div,
            .admin-profile-wrapper > div > div:last-child {
                display: none !important;
            }
        </style>
        @endpush
        
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Hide delete account section
                const deleteSection = document.querySelector('.admin-profile-wrapper div.mt-8.pt-8.border-t');
                if (deleteSection) {
                    deleteSection.style.display = 'none';
                }
                
                // Also hide using Livewire's wire:id attribute
                const livewireDivs = document.querySelectorAll('[wire\\:id]');
                livewireDivs.forEach(div => {
                    const deleteForm = div.querySelector('livewire\\:settings\\.delete-user-form, [wire\\:id*="delete-user"]');
                    if (deleteForm) {
                        const parent = deleteForm.closest('div.mt-8.pt-8.border-t') || deleteForm.parentElement;
                        if (parent) {
                            parent.style.display = 'none';
                        }
                    }
                });
            });
        </script>
        @endpush
    </div>

    <!-- Account Information Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Account Information</h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-700">Role</p>
                    <p class="text-sm text-gray-500 mt-1">{{ ucfirst(auth()->user()->role ?? 'User') }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-700">Email Verified</p>
                    <p class="text-sm text-gray-500 mt-1">
                        @if(auth()->user()->email_verified_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Verified
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Not Verified
                            </span>
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center justify-between py-3">
                <div>
                    <p class="text-sm font-medium text-gray-700">Member Since</p>
                    <p class="text-sm text-gray-500 mt-1">{{ auth()->user()->created_at->format('F d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
