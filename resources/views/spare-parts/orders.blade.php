@extends('layouts.customer')

@section('title', 'My Spare Parts Orders | Kibo Auto')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        @livewire('customer.spare-part-orders')
    </div>
@endsection

