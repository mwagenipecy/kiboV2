@extends('layouts.admin')

@section('title', 'Edit Dealer')

@section('content')
    <livewire:admin.registration.edit-dealer :id="$id" />
@endsection

