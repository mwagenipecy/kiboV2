<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use App\Models\Vehicle;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        // Get statistics
        $totalVehicles = Vehicle::count();
        $activeVehicles = Vehicle::where('status', 'available')->count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', OrderStatus::PENDING->value)->count();
        
        // Calculate revenue from paid orders
        $revenue = Order::where('payment_completed', true)->sum('fee');
        
        // Get recent orders
        $recentOrders = Order::with(['vehicle.make', 'vehicle.model', 'user'])
            ->latest()
            ->take(5)
            ->get();
        
        // Get valuation orders that are paid but not completed
        $valuationOrders = Order::with(['vehicle.make', 'vehicle.model', 'user'])
            ->where('order_type', OrderType::VALUATION_REPORT->value)
            ->where('payment_completed', true)
            ->whereIn('status', [OrderStatus::PENDING->value, OrderStatus::PROCESSING->value, OrderStatus::APPROVED->value])
            ->latest()
            ->get();

        return view('livewire.admin.dashboard', [
            'totalVehicles' => $totalVehicles,
            'activeVehicles' => $activeVehicles,
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'revenue' => $revenue,
            'recentOrders' => $recentOrders,
            'valuationOrders' => $valuationOrders,
        ]);
    }
}

