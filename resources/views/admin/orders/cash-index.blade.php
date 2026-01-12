@extends('layouts.admin')

@section('title', 'Cash Purchase Orders - All')

@section('content')
    @livewire('admin.cash-orders', ['filter' => 'all'])
@endsection

