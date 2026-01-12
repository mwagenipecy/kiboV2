@extends('layouts.admin')

@section('content')
    @livewire('admin.financing-orders', ['filter' => 'all'])
@endsection

