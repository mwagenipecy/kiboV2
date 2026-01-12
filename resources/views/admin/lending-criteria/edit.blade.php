@extends('layouts.admin')

@section('title', 'Edit Lending Criteria')

@section('content')
    @livewire('admin.lending-criteria.criteria-form', ['id' => $criteriaId])
@endsection

