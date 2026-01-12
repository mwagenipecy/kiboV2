@extends('layouts.admin')

@section('content')
    @livewire('admin.financing-order-detail', ['id' => $orderId])
@endsection

