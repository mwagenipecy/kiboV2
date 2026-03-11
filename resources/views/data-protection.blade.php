@extends('layouts.customer')

@section('title', 'Data Protection Notice - Kibo Auto')

@section('content')
<div class="min-h-screen bg-white py-8" x-data="{ lang: 'en' }">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: #009866;">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Data Protection Notice</h1>
                    <p class="text-gray-600 mt-1">KiboAuto Marketplace – kiboauto.co.tz · Operated by Savanna Hills Limited</p>
                </div>
            </div>
            <!-- Language tabs -->
            <div class="flex gap-6 mt-4 border-b border-gray-200">
                <button @click="lang = 'en'" type="button" class="pb-3 px-1 -mb-px border-b-2 font-medium transition-colors"
                    :class="lang === 'en' ? 'text-gray-900' : 'text-gray-500 hover:text-gray-700 border-transparent'"
                    :style="lang === 'en' ? 'border-color: #009866;' : ''">English</button>
                <button @click="lang = 'sw'" type="button" class="pb-3 px-1 -mb-px border-b-2 font-medium transition-colors"
                    :class="lang === 'sw' ? 'text-gray-900' : 'text-gray-500 hover:text-gray-700 border-transparent'"
                    :style="lang === 'sw' ? 'border-color: #009866;' : ''">Kiswahili</button>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sm:p-8 lg:p-10">
            <div class="prose prose-lg max-w-none">
                <div x-show="lang === 'en'" x-cloak class="space-y-8">
                    @include('partials.data-protection-en')
                </div>
                <div x-show="lang === 'sw'" x-cloak class="space-y-8">
                    @include('partials.data-protection-sw')
                </div>
            </div>
        </div>

        <!-- Back -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('cars.index') }}" class="inline-flex items-center gap-2 font-medium" style="color: #009866;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Home
            </a>
            <span class="mx-2 text-gray-300">|</span>
            <a href="{{ route('terms') }}" class="font-medium text-gray-700 hover:text-gray-900">Terms and Conditions</a>
        </div>
    </div>
</div>
@endsection
