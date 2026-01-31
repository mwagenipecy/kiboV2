@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
@php
    use App\Models\Vehicle;
    use App\Models\Order;
    use App\Models\User;
    use App\Models\CarRequest;
    use App\Models\DealerCarOffer;
    use App\Models\SparePartOrder;
    use App\Models\LendingCriteria;
    use App\Enums\OrderStatus;
    use App\Enums\OrderType;
    use App\Enums\VehicleStatus;

    $userRole = $userRole ?? auth()->user()->role ?? 'admin';
    
    // Common data for all roles
    $totalVehicles = Vehicle::count();
    $activeVehicles = Vehicle::where('status', VehicleStatus::APPROVED->value)->count();
    $pendingVehicles = Vehicle::where('status', VehicleStatus::PENDING->value)->count();
    $totalOrders = Order::count();
    $pendingOrders = Order::where('status', OrderStatus::PENDING->value)->count();
    $completedOrders = Order::where('status', OrderStatus::COMPLETED->value)->count();
    $revenue = Order::where('payment_completed', true)->sum('fee');
    $dealerCount = User::where('role', 'dealer')->count();
    $customerCount = User::where('role', 'customer')->count();
    
    // Role-specific data
    switch($userRole) {
        case 'agent':
            $sparePartOrders = SparePartOrder::count();
            $sparePartOrdersPending = SparePartOrder::where('status', 'pending')->count();
            $sparePartOrdersInProgress = SparePartOrder::where('status', 'in_progress')->count();
            $sparePartOrdersCompleted = SparePartOrder::where('status', 'completed')->count();
            break;
        case 'lender':
            $user = auth()->user();
            $lenderEntityId = $user->entity_id ?? null;
            
            if ($lenderEntityId) {
                // Financing requests that come to this lender (where lender_entity_id matches)
                $loanRequests = Order::where('order_type', OrderType::FINANCING_APPLICATION->value)
                    ->whereJsonContains('order_data->lender_entity_id', $lenderEntityId)
                    ->count();
                
                $loanRequestsPending = Order::where('order_type', OrderType::FINANCING_APPLICATION->value)
                    ->whereJsonContains('order_data->lender_entity_id', $lenderEntityId)
                    ->where(function($q) {
                        $q->where('status', OrderStatus::PENDING->value)
                          ->orWhere(function($q2) {
                              $q2->where('status', OrderStatus::APPROVED->value)
                                 ->where(function($q3) {
                                     $q3->whereJsonContains('order_data->lender_approval', 'pending')
                                        ->orWhereNull('order_data->lender_approval');
                                 });
                          });
                    })
                    ->count();
                
                $loanRequestsApproved = Order::where('order_type', OrderType::FINANCING_APPLICATION->value)
                    ->whereJsonContains('order_data->lender_entity_id', $lenderEntityId)
                    ->whereJsonContains('order_data->lender_approval', 'approved')
                    ->count();
                
                $loanRequestsRejected = Order::where('order_type', OrderType::FINANCING_APPLICATION->value)
                    ->whereJsonContains('order_data->lender_entity_id', $lenderEntityId)
                    ->whereJsonContains('order_data->lender_approval', 'rejected')
                    ->count();
                
                // Get lending criteria statistics
                $totalCriteria = LendingCriteria::where('entity_id', $lenderEntityId)->count();
                $activeCriteria = LendingCriteria::where('entity_id', $lenderEntityId)
                    ->where('is_active', true)
                    ->count();
                
                // Get criteria usage (how many times each criteria was selected)
                $criteriaUsage = Order::where('order_type', OrderType::FINANCING_APPLICATION->value)
                    ->whereJsonContains('order_data->lender_entity_id', $lenderEntityId)
                    ->get()
                    ->groupBy('order_data.lending_criteria_id')
                    ->map(function($orders) {
                        return $orders->count();
                    });
                
                $mostUsedCriteria = null;
                $mostUsedCriteriaCount = 0;
                if ($criteriaUsage->isNotEmpty()) {
                    $mostUsedCriteriaId = $criteriaUsage->keys()->first();
                    $mostUsedCriteriaCount = $criteriaUsage->first();
                    $mostUsedCriteria = LendingCriteria::find($mostUsedCriteriaId);
                }
                
                // Recent requests
                $recentRequests = Order::where('order_type', OrderType::FINANCING_APPLICATION->value)
                    ->whereJsonContains('order_data->lender_entity_id', $lenderEntityId)
                    ->with(['vehicle.make', 'vehicle.model', 'user'])
                    ->latest()
                    ->limit(5)
                    ->get();
            } else {
                $loanRequests = 0;
                $loanRequestsPending = 0;
                $loanRequestsApproved = 0;
                $loanRequestsRejected = 0;
                $totalCriteria = 0;
                $activeCriteria = 0;
                $mostUsedCriteria = null;
                $mostUsedCriteriaCount = 0;
                $recentRequests = collect();
            }
            break;
        case 'dealer':
            $user = auth()->user();
            $entityId = $user->entity_id ?? null;
            
            if ($entityId) {
                $totalVehicles = Vehicle::where('entity_id', $entityId)->count();
                $activeVehicles = Vehicle::where('entity_id', $entityId)->where('status', VehicleStatus::APPROVED->value)->count();
                $pendingVehicles = Vehicle::where('entity_id', $entityId)->where('status', VehicleStatus::PENDING->value)->count();
                $soldVehicles = Vehicle::where('entity_id', $entityId)->where('status', VehicleStatus::SOLD->value)->count();
                
                $openCarRequests = CarRequest::where('status', 'open')->count();
                $myOffers = DealerCarOffer::where('entity_id', $entityId)->count();
                $myAcceptedOffers = DealerCarOffer::where('entity_id', $entityId)->where('status', 'accepted')->count();
                
                $totalOrders = Order::whereHas('vehicle', function($q) use ($entityId) {
                    $q->where('entity_id', $entityId);
                })->count();
                
                $pendingOrders = Order::whereHas('vehicle', function($q) use ($entityId) {
                    $q->where('entity_id', $entityId);
                })->where('status', OrderStatus::PENDING->value)->count();
                
                $recentVehicles = Vehicle::where('entity_id', $entityId)
                    ->with(['make', 'model'])
                    ->latest()
                    ->limit(5)
                    ->get();
                
                $recentCarRequests = CarRequest::where('status', 'open')
                    ->with(['make', 'model'])
                    ->latest()
                    ->limit(5)
                    ->get();
            }
            break;
        case 'admin':
        default:
            $carRequestsOpen = CarRequest::where('status', 'open')->count();
            $carRequestsClosed = CarRequest::where('status', 'closed')->count();
            $carRequestOffers = DealerCarOffer::count();
            $recentVehicles = Vehicle::with(['make', 'model'])->latest()->limit(5)->get();
            $recentOrders = Order::with('user')->latest()->limit(5)->get();
            $recentCarRequests = CarRequest::with(['make', 'model'])->latest()->limit(5)->get();
            break;
    }
@endphp

@switch($userRole)
    @case('agent')
        @include('admin.dashboard.agent')
        @break
    
    @case('lender')
        @include('admin.dashboard.lender')
        @break
    
    @case('dealer')
        @include('admin.dashboard.dealer')
        @break
    
    @case('admin')
    @default
        @include('admin.dashboard.admin')
        @break
@endswitch
@endsection

