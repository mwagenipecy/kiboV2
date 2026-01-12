@extends('layouts.admin')

@section('content')
    @livewire('admin.leasing-order-detail', ['id' => $orderId])
@endsection

