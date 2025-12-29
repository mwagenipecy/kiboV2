@extends('layouts.admin')

@section('title', 'Edit Lender')

@section('content')
    <livewire:admin.registration.edit-lender :id="$id" />
@endsection

