@extends('layouts.admin')

@section('title', 'Cash Purchase Orders - Pending')

@section('content')
    @livewire('admin.cash-orders', ['filter' => 'pending'])
@endsection

