@extends('layouts.admin')

@section('content')
    @livewire('admin.leasing-cars.leasing-car-view', ['id' => $id])
@endsection

