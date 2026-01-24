@extends('layouts.admin')

@section('title', 'Notification Settings - Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Notification Settings</h1>
        <p class="mt-2 text-sm text-gray-600">Manage your notification preferences and email settings</p>
    </div>

    <!-- Settings Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="space-y-6">
            <!-- Email Notifications -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Email Notifications</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-200">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Order Updates</p>
                            <p class="text-sm text-gray-500 mt-1">Receive email notifications when orders are updated</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-200">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">New Customer Registrations</p>
                            <p class="text-sm text-gray-500 mt-1">Get notified when new customers register</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-200">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Vehicle Listings</p>
                            <p class="text-sm text-gray-500 mt-1">Notifications for new vehicle listings and updates</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-3">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">System Alerts</p>
                            <p class="text-sm text-gray-500 mt-1">Important system notifications and alerts</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="pt-6 border-t border-gray-200">
                <button type="button" class="px-6 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
@endsection

