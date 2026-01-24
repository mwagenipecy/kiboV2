@extends('layouts.admin')

@section('title', 'General Settings - Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">General Settings</h1>
        <p class="mt-2 text-sm text-gray-600">Manage your general application settings and preferences</p>
    </div>

    <!-- Settings Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="space-y-6">
            <!-- Application Settings -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Settings</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                        <input type="text" value="Kibo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                        <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">Your trusted vehicle marketplace</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Language</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="en" selected>English</option>
                            <option value="sw">Swahili</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="Africa/Dar_es_Salaam" selected>Africa/Dar es Salaam (EAT)</option>
                            <option value="UTC">UTC</option>
                        </select>
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

