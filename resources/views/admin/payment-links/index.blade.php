@extends('layouts.admin')

@section('title', 'Payment Links - Admin')

@section('content')
    <div class="w-full max-w-[1600px]">
        @livewire('admin.payment-links-list', ['section' => $section ?? 'overview'])
    </div>
@endsection
