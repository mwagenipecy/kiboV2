@extends('layouts.customer')

@section('title', 'Track Spare Part Order | Kibo Auto')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-emerald-50/40 via-gray-50 to-gray-50 pb-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-10">
            @livewire('customer.spare-part-order-detail', ['publicToken' => $token, 'allowGuestTrack' => true], key('spare-track-'.$token))
        </div>
    </div>
@endsection
