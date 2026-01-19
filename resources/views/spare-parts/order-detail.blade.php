@extends('layouts.customer')

@section('title', 'Order Details | Kibo Auto')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        @livewire('customer.spare-part-order-detail', ['id' => $id])
    </div>
@endsection

