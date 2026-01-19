@php
    $make = optional($carRequest->make)->name;
    $model = optional($carRequest->model)->name;
@endphp

<p>Hello,</p>

<p>A customer has submitted a new “Find me a car” request.</p>

<ul>
    @if($make)<li><strong>Make:</strong> {{ $make }}</li>@endif
    @if($model)<li><strong>Model:</strong> {{ $model }}</li>@endif
    @if($carRequest->min_year || $carRequest->max_year)
        <li><strong>Year:</strong> {{ $carRequest->min_year ?? 'Any' }} - {{ $carRequest->max_year ?? 'Any' }}</li>
    @endif
    @if($carRequest->min_budget || $carRequest->max_budget)
        <li><strong>Budget:</strong> {{ $carRequest->min_budget ?? 'Any' }} - {{ $carRequest->max_budget ?? 'Any' }}</li>
    @endif
    @if($carRequest->location)<li><strong>Location:</strong> {{ $carRequest->location }}</li>@endif
</ul>

@if($carRequest->notes)
    <p><strong>Notes:</strong> {{ $carRequest->notes }}</p>
@endif

<p>
    Please log into the dealer portal to submit your offer:
    <a href="{{ route('dealer.car-requests') }}">{{ route('dealer.car-requests') }}</a>
</p>

<p>Thanks,<br>Kibo Auto</p>


