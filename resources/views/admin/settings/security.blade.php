@extends('layouts.admin')

@section('title', 'Security Settings - Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Security Settings</h1>
        <p class="mt-2 text-sm text-gray-600">Manage your security preferences and authentication settings</p>
    </div>

    <!-- Settings Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="space-y-6">
            <!-- Authentication Settings -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Authentication</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-200">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Two-Factor Authentication</p>
                            <p class="text-sm text-gray-500 mt-1">Add an extra layer of security to your account</p>
                        </div>
                        <a href="{{ route('two-factor.show') }}" class="px-4 py-2 text-sm font-medium text-green-600 hover:text-green-700">
                            Configure
                        </a>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-gray-200">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Login OTP Verification</p>
                            <p class="text-sm text-gray-500 mt-1">Require OTP code for login verification</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-3">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Session Timeout</p>
                            <p class="text-sm text-gray-500 mt-1">Automatically log out after period of inactivity</p>
                        </div>
                        <select class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="30">30 minutes</option>
                            <option value="60" selected>1 hour</option>
                            <option value="120">2 hours</option>
                            <option value="0">Never</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Password Settings -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Password</h3>
                <div class="space-y-4">
                    <div>
                        <a href="{{ route('user-password.edit') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-600 hover:text-green-700 border border-green-600 rounded-lg hover:bg-green-50 transition-colors">
                            Change Password
                        </a>
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

