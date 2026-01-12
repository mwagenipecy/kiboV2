@extends('layouts.admin')

@section('content')
    @livewire('lender.financing-application-detail', ['id' => $applicationId])
@endsection

