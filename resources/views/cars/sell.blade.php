@extends('layouts.customer')

@section('title', 'Sell Your Car | Kibo Auto')

@section('content')
    <!-- Sell Options Component -->
    <x-customer.sell-options />

    <!-- Seller Reviews Component -->
    <x-customer.seller-reviews />

    <!-- Place Advert Component -->
    <x-customer.place-advert />

    <!-- Sell Guides Component -->
    <x-customer.sell-guides />

    <!-- Sell FAQ Component -->
    <x-customer.sell-faq />
@endsection

