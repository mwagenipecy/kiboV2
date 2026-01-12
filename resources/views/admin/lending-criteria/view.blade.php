@extends('layouts.admin')

@section('content')
    @livewire('admin.lending-criteria.criteria-view', ['criteriaId' => $criteriaId])
@endsection

