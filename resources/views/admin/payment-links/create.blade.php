@extends('layouts.admin')

@section('title', 'Manual bill / Generate link - Admin')

@section('content')
    <div class="w-full max-w-[1600px]">
        @livewire('admin.payment-link-generate')
    </div>
@endsection
