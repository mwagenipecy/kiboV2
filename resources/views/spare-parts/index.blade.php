@extends('layouts.customer')

@section('title', 'Spare Parts | Kibo Auto')

@section('content')
    <x-customer.page-hero slug="spare_parts" variant="centered" />

    <!-- Sourcing Form -->
    <section class="bg-[#f6f8f7] border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @livewire('customer.spare-part-sourcing')
        </div>
    </section>
@endsection


