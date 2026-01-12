@extends('layouts.admin')

@section('title', 'Edit Lease')

@section('content')
    @livewire('admin.leasing.lease-form', ['id' => $id])
@endsection

