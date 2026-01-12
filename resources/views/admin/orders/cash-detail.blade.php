@extends('layouts.admin')

@section('title', 'Cash Purchase Order Detail')

@section('content')
    @livewire('admin.cash-order-detail', ['id' => $orderId])
@endsection

