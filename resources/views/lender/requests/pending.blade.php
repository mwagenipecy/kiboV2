@extends('layouts.lender')

@section('title', 'Pending Loan Requests')

@section('content')
    @livewire('lender.financing-applications', ['filter' => 'pending'])
@endsection

