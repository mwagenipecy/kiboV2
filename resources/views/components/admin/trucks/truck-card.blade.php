{{-- Truck Card Component --}}
@props(['truck'])

@php
    $allImages = [];
    if($truck->image_front) {
        $allImages[] = $truck->image_front;
    }
    if($truck->image_side) {
        $allImages[] = $truck->image_side;
    }
    if($truck->image_back) {
        $allImages[] = $truck->image_back;
    }
    if($truck->other_images && is_array($truck->other_images)) {
        $allImages = array_merge($allImages, $truck->other_images);
    }
    $imageCount = count($allImages);
    
    // Status badge classes
    $statusClasses = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'awaiting_approval' => 'bg-orange-100 text-orange-800',
        'approved' => 'bg-green-100 text-green-800',
        'hold' => 'bg-blue-100 text-blue-800',
        'sold' => 'bg-purple-100 text-purple-800',
        'removed' => 'bg-gray-100 text-gray-800',
    ];
    $statusClass = $statusClasses[$truck->status->value] ?? 'bg-gray-100 text-gray-800';
@endphp

<div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col">
    {{-- Image Carousel --}}
    <div class="relative aspect-[4/3] bg-gray-100 group" data-carousel="truck-{{ $truck->id }}">
        @if($imageCount > 0)
            @foreach($allImages as $index => $image)
            <a href="{{ route('admin.trucks.view', $truck->id) }}" class="carousel-image absolute inset-0 {{ $index === 0 ? '' : 'hidden' }}" data-index="{{ $index }}">
                <img src="{{ asset('storage/' . $image) }}" alt="{{ $truck->title }}" class="w-full h-full object-cover">
            </a>
            @endforeach
        @else
            <a href="{{ route('admin.trucks.view', $truck->id) }}" class="absolute inset-0">
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </a>
        @endif

        {{-- Navigation Arrows --}}
        @if($imageCount > 1)
        <button onclick="navigateCarousel(event, 'truck-{{ $truck->id }}', -1)" class="carousel-nav absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity z-10">
            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <button onclick="navigateCarousel(event, 'truck-{{ $truck->id }}', 1)" class="carousel-nav absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity z-10">
            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        @endif

        {{-- Badges --}}
        <div class="absolute top-2 left-2 flex flex-col gap-1.5 z-10">
            {{-- Condition Badge --}}
            @if($truck->condition)
            <div class="bg-white px-3 py-1 rounded text-xs font-semibold text-gray-900 shadow-sm">
                {{ ucfirst($truck->condition) }}
            </div>
            @endif
            
            {{-- Status Badge (Admin) --}}
            <div class="px-3 py-1 rounded text-xs font-semibold shadow-sm {{ $statusClass }}">
                {{ ucfirst(str_replace('_', ' ', $truck->status->value)) }}
            </div>
        </div>

        {{-- Image counter --}}
        @if($imageCount > 0)
        <div class="absolute bottom-3 right-3 bg-gray-900/80 text-white px-2 py-1 rounded text-xs font-medium z-10">
            <span class="current-image">1</span>/{{ $imageCount }}
        </div>
        @endif
    </div>

    {{-- Content --}}
    <a href="{{ route('admin.trucks.view', $truck->id) }}" class="p-4 flex flex-col flex-grow">
        <h3 class="text-base font-bold text-gray-900 mb-1">{{ $truck->make->name ?? '' }} {{ $truck->model->name ?? '' }}</h3>
        <p class="text-sm text-gray-700 mb-1 line-clamp-2">{{ $truck->title }}</p>
        <p class="text-xs text-gray-600 mb-3">{{ $truck->year }} • {{ number_format($truck->mileage ?? 0) }} km</p>

        {{-- Badges --}}
        <div class="flex flex-wrap gap-1.5 mb-4">
            @if($truck->truck_type)
            <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700">
                {{ $truck->truck_type }}
            </span>
            @endif
            @if($truck->transmission)
            <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                {{ ucfirst($truck->transmission) }}
            </span>
            @endif
            @if($truck->fuel_type)
            <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                {{ ucfirst($truck->fuel_type) }}
            </span>
            @endif
            @if($truck->body_type)
            <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                {{ $truck->body_type }}
            </span>
            @endif
        </div>

        {{-- Truck-Specific Info --}}
        @if($truck->cargo_capacity_kg || $truck->towing_capacity_kg)
        <div class="text-xs text-gray-600 mb-3 space-y-1">
            @if($truck->cargo_capacity_kg)
            <div>Cargo: {{ number_format($truck->cargo_capacity_kg, 0) }} kg</div>
            @endif
            @if($truck->towing_capacity_kg)
            <div>Towing: {{ number_format($truck->towing_capacity_kg, 0) }} kg</div>
            @endif
        </div>
        @endif

        {{-- Price and Location --}}
        <div class="mt-auto">
            <div class="text-2xl font-bold text-gray-900 mb-2">
                {{ $truck->currency }} {{ number_format($truck->price, 0) }}
            </div>
            @if($truck->entity)
            <div class="flex items-center gap-1 text-gray-600 text-xs mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span>{{ $truck->entity->name }}</span>
            </div>
            @endif
        </div>
    </a>

    {{-- Admin Action Buttons --}}
    <div class="px-4 pb-4 space-y-2">
        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('admin.trucks.view', $truck->id) }}" class="px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors text-center">
                View
            </a>
            <a href="{{ route('admin.trucks.edit', $truck->id) }}" class="px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors text-center">
                Edit
            </a>
        </div>
    </div>
</div>

