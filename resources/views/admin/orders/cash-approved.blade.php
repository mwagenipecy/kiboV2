@extends('layouts.admin')

@section('title', 'Cash Purchase Orders - Approved')

@section('content')
    @livewire('admin.cash-orders', ['filter' => 'approved'])
@endsection

