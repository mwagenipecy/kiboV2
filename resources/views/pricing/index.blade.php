@extends('layouts.customer')

@section('title', 'Advertising Prices | Kibo Auto')

@section('content')
    @livewire('customer.pricing-page', ['category' => $category])
@endsection

