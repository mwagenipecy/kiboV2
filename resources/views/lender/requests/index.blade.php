@extends('layouts.lender')

@section('title', 'Loan Requests')

@section('content')
    @livewire('lender.financing-applications', ['filter' => 'all'])
@endsection

