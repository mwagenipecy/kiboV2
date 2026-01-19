@extends('layouts.lender')

@section('title', 'Rejected Loan Requests')

@section('content')
    @livewire('lender.financing-applications', ['filter' => 'rejected'])
@endsection

