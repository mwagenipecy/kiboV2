@extends('layouts.admin')

@section('title', 'Evaluation Order Details')

@section('content')
    @livewire('admin.evaluation-order-detail', ['orderId' => $orderId])
@endsection

