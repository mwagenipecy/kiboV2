@extends('layouts.customer')

@section('title', 'Page Not Found - 404')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <!-- Error Code -->
        <div>
            <h1 class="text-9xl font-bold text-green-700 mb-4">404</h1>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Page Not Found</h2>
            <p class="text-lg text-gray-600 mb-8">
                Oops! The page you're looking for doesn't exist or has been moved.
            </p>
        </div>

        <!-- Illustration/Icon -->
        <div class="flex justify-center mb-8">
            <svg class="w-48 h-48 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-4">
            <a href="{{ url('/') }}" 
               class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700 transition-colors">
                Go to Homepage
            </a>
            <button onclick="window.history.back()" 
                    class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700 transition-colors">
                Go Back
            </button>
        </div>

        <!-- Helpful Links -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <p class="text-sm text-gray-500 mb-4">You might be looking for:</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ url('/cars') }}" class="text-sm text-green-700 hover:text-green-800 font-medium">Browse Cars</a>
                <a href="{{ url('/trucks') }}" class="text-sm text-green-700 hover:text-green-800 font-medium">Browse Trucks</a>
                <a href="{{ url('/vans') }}" class="text-sm text-green-700 hover:text-green-800 font-medium">Browse Vans</a>
                <a href="{{ url('/spare-parts') }}" class="text-sm text-green-700 hover:text-green-800 font-medium">Spare Parts</a>
            </div>
        </div>
    </div>
</div>
@endsection

