<div>
    <div class="mb-6">
        <a href="{{ route('admin.leasing-cars.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Leasing Cars
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Car Images Gallery -->
        <div class="aspect-video bg-gray-100">
            @if($car->image_front)
                <img src="{{ asset('storage/' . $car->image_front) }}" alt="{{ $car->title }}" class="w-full h-full object-cover">
            @else
                <div class="flex items-center justify-center h-full text-gray-400">
                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
        </div>

        <div class="p-8">
            <!-- Header -->
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $car->title }}</h1>
                    <p class="text-lg text-gray-600">{{ $car->make->name }} {{ $car->model->name }} ({{ $car->year }})</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 text-sm font-medium rounded-full 
                        {{ $car->status === 'available' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $car->status === 'leased' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $car->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $car->status === 'maintenance' ? 'bg-orange-100 text-orange-800' : '' }}">
                        {{ ucfirst($car->status) }}
                    </span>
                    <a href="{{ route('admin.leasing-cars.edit', $car->id) }}" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                        Edit
                    </a>
                </div>
            </div>

            <!-- Pricing -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Leasing Rates</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Daily Rate</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $car->currency }} {{ number_format($car->daily_rate, 0) }}</p>
                    </div>
                    @if($car->weekly_rate)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Weekly Rate</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $car->currency }} {{ number_format($car->weekly_rate, 0) }}</p>
                    </div>
                    @endif
                    @if($car->monthly_rate)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Monthly Rate</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $car->currency }} {{ number_format($car->monthly_rate, 0) }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Security Deposit</p>
                        <p class="text-xl font-bold text-gray-900">{{ $car->currency }} {{ number_format($car->security_deposit, 0) }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Specifications -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Specifications</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Transmission</dt>
                            <dd class="font-medium">{{ $car->transmission ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Fuel Type</dt>
                            <dd class="font-medium">{{ $car->fuel_type ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Body Type</dt>
                            <dd class="font-medium">{{ $car->body_type ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Seats</dt>
                            <dd class="font-medium">{{ $car->seats ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Color</dt>
                            <dd class="font-medium">{{ $car->color_exterior ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Mileage</dt>
                            <dd class="font-medium">{{ $car->mileage ? number_format($car->mileage) . ' km' : 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Leasing Terms -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Leasing Terms</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Minimum Lease Period</dt>
                            <dd class="font-medium">{{ $car->min_lease_days }} days</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Minimum Driver Age</dt>
                            <dd class="font-medium">{{ $car->min_driver_age }} years</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Insurance</dt>
                            <dd class="font-medium">
                                <span class="{{ $car->insurance_included ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $car->insurance_included ? '✓ Included' : '✗ Not Included' }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Fuel</dt>
                            <dd class="font-medium">
                                <span class="{{ $car->fuel_included ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $car->fuel_included ? '✓ Included' : '✗ Not Included' }}
                                </span>
                            </dd>
                        </div>
                        @if($car->mileage_limit_per_day)
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Daily Mileage Limit</dt>
                            <dd class="font-medium">{{ $car->mileage_limit_per_day }} km/day</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Description -->
            @if($car->description)
            <div class="mt-6 bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                <p class="text-gray-700">{{ $car->description }}</p>
            </div>
            @endif

            <!-- Statistics -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ $car->total_leases }}</p>
                    <p class="text-sm text-gray-600">Total Leases</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ $car->view_count }}</p>
                    <p class="text-sm text-gray-600">Views</p>
                </div>
                @if($car->entity)
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-sm font-semibold text-gray-900">{{ $car->entity->name }}</p>
                    <p class="text-xs text-gray-600">Owner Entity</p>
                </div>
                @endif
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-sm font-semibold text-gray-900">{{ $car->registeredBy->name }}</p>
                    <p class="text-xs text-gray-600">Registered By</p>
                </div>
            </div>
        </div>
    </div>
</div>
