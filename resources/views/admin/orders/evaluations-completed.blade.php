@extends('layouts.admin')

@section('title', 'Evaluation Orders - Completed')

@section('content')
    @livewire('admin.evaluation-orders', ['filter' => 'completed'])
@endsection

