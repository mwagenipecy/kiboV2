@extends('layouts.admin')

@section('title', 'Cash Purchase Orders - Completed')

@section('content')
    @livewire('admin.cash-orders', ['filter' => 'completed'])
@endsection

