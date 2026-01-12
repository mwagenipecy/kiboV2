@extends('layouts.admin')

@section('title', 'View Lease')

@section('content')
    @livewire('admin.leasing.lease-view', ['id' => $id])
@endsection

