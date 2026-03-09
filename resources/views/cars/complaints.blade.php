@extends('layouts.customer')

@section('title', 'Complaints & Feedback - Kibo Auto')

@push('styles')
<style>
    .kibo-text { color: #009866 !important; }
</style>
@endpush

@section('content')
<section class="bg-white min-h-screen py-12 sm:py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('cars.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#009866] mb-8">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to home
        </a>
        @livewire('customer.complaints-section')
    </div>
</section>
@endsection
