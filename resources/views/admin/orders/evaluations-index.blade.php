@extends('layouts.admin')

@section('title', 'Evaluation Orders - All')

@section('content')
    @livewire('admin.evaluation-orders', ['filter' => 'all'])
@endsection

