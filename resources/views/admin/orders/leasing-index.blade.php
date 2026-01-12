@extends('layouts.admin')

@section('content')
    @livewire('admin.leasing-orders', ['filter' => $filter ?? 'all'])
@endsection

