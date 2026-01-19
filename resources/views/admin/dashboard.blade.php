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
    use App\Enums\OrderStatus;
    use App\Enums\OrderType;
    use App\Enums\VehicleStatus;

    $userRole = $userRole ?? auth()->user()->role ?? 'admin';
    
    // Common data for all roles
    $totalVehicles = Vehicle::count();
    $activeVehicles = Vehicle::where('status', 'available')->count();
    $pendingVehicles = Vehicle::where('status', 'pending')->count();
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
            $loanRequests = Order::where('order_type', OrderType::FINANCING_APPLICATION->value)->count();
            $loanRequestsPending = Order::where('order_type', OrderType::FINANCING_APPLICATION->value)
                ->where('status', OrderStatus::PENDING->value)->count();
            $loanRequestsApproved = Order::where('order_type', OrderType::FINANCING_APPLICATION->value)
                ->where('status', OrderStatus::APPROVED->value)->count();
            break;
        case 'dealer':
            $user = auth()->user();
            $entityId = $user->entity_id ?? null;
            
            if ($entityId) {
                $totalVehicles = Vehicle::where('entity_id', $entityId)->count();
                $activeVehicles = Vehicle::where('entity_id', $entityId)->where('status', VehicleStatus::AVAILABLE)->count();
                $pendingVehicles = Vehicle::where('entity_id', $entityId)->where('status', VehicleStatus::PENDING)->count();
                $soldVehicles = Vehicle::where('entity_id', $entityId)->where('status', VehicleStatus::SOLD)->count();
                
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

