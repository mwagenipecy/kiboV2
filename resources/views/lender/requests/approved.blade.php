@extends('layouts.lender')

@section('title', 'Approved Loan Requests')

@section('content')
    @livewire('lender.financing-applications', ['filter' => 'approved'])
@endsection

