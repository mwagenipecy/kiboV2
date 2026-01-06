@extends('layouts.admin')

@section('title', 'Evaluation Orders - Paid')

@section('content')
    @livewire('admin.evaluation-orders', ['filter' => 'paid'])
@endsection

