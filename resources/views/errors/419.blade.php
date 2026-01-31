@extends('layouts.customer')

@section('title', 'Page Expired - 419')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <!-- Error Code -->
        <div>
            <h1 class="text-9xl font-bold text-orange-600 mb-4">419</h1>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Page Expired</h2>
            <p class="text-lg text-gray-600 mb-8">
                Your session has expired for security reasons. Please refresh the page and try again.
            </p>
        </div>

        <!-- Illustration/Icon -->
        <div class="flex justify-center mb-8">
            <svg class="w-48 h-48 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-4">
            <button onclick="window.location.reload()" 
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700 transition-colors">
                Refresh Page
            </button>
            <a href="{{ url('/') }}" 
               class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700 transition-colors">
                Go to Homepage
            </a>
        </div>

        <!-- Help Text -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                This usually happens when a form has been open for too long. Simply refresh and try again.
            </p>
        </div>
    </div>
</div>
@endsection

