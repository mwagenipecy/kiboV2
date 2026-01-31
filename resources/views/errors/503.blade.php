@extends('layouts.customer')

@section('title', 'Service Unavailable - 503')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <!-- Error Code -->
        <div>
            <h1 class="text-9xl font-bold text-yellow-600 mb-4">503</h1>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Service Unavailable</h2>
            <p class="text-lg text-gray-600 mb-8">
                We're currently performing maintenance. We'll be back online shortly.
            </p>
        </div>

        <!-- Illustration/Icon -->
        <div class="flex justify-center mb-8">
            <svg class="w-48 h-48 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-4">
            <a href="{{ url('/') }}" 
               class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700 transition-colors">
                Go to Homepage
            </a>
            <button onclick="window.location.reload()" 
                    class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700 transition-colors">
                Refresh Page
            </button>
        </div>

        <!-- Help Text -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                Thank you for your patience. We're working hard to improve your experience.
            </p>
        </div>
    </div>
</div>
@endsection

