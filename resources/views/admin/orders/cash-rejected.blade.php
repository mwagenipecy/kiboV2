@extends('layouts.admin')

@section('title', 'Cash Purchase Orders - Rejected')

@section('content')
    @livewire('admin.cash-orders', ['filter' => 'rejected'])
@endsection

