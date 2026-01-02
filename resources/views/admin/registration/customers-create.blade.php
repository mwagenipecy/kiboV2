@extends('layouts.admin')

@section('title', 'Create Customer - Admin Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('admin.registration.customers') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Create Customer</h1>
                <p class="mt-1 text-sm text-gray-600">Register a new customer with mandatory phone and NIDA number</p>
            </div>
        </div>
    </div>

    <!-- Form Component -->
    @livewire('admin.registration.customer-form')
@endsection

