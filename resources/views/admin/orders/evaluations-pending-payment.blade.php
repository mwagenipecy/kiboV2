@extends('layouts.admin')

@section('title', 'Evaluation Orders - Pending Payment')

@section('content')
    @livewire('admin.evaluation-orders', ['filter' => 'pending-payment'])
@endsection

