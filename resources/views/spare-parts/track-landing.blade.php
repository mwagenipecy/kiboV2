@extends('layouts.customer')

@section('title', 'Track Spare Part Order | Kibo Auto')

@section('content')
    <div class="max-w-lg mx-auto px-4 py-16 text-center">
        <h1 class="text-2xl font-bold text-gray-900 mb-3">Track your spare part order</h1>
        @if (session('track_error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 text-left" role="alert">
                {{ session('track_error') }}
            </div>
        @endif
        <p class="text-gray-600 mb-6">
            Use the <strong>Track order</strong> button in the top menu and enter your <strong>order number</strong> (shown after you submit and in your SMS), or open the tracking link from your text message.
        </p>
        <p class="text-sm text-gray-500 mb-8">
            If you signed in with an account, you can also view all orders under
            <a href="{{ route('spare-parts.orders') }}" class="text-emerald-700 font-medium hover:underline">My orders</a>
            (login required).
        </p>
        <a href="{{ route('spare-parts.index') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
            Back to spare parts
        </a>
    </div>
@endsection
