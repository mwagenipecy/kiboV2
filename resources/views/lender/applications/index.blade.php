@extends('layouts.admin')

@section('content')
    @livewire('lender.financing-applications', ['filter' => $filter ?? 'pending'])
@endsection

