<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function sales(Request $request): StreamedResponse
    {
        $orders = Order::with(['user', 'vehicle'])
            ->orderByDesc('created_at')
            ->limit(5000)
            ->get();

        $fileName = 'sales-report-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = static function () use ($orders) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Order ID',
                'Type',
                'Status',
                'User',
                'Vehicle',
                'Fee',
                'Payment Completed',
                'Created At',
            ]);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->id,
                    $order->order_type instanceof \App\Enums\OrderType ? $order->order_type->value : (string) $order->order_type,
                    $order->status instanceof \App\Enums\OrderStatus ? $order->status->value : (string) $order->status,
                    $order->user?->name ?? 'N/A',
                    $order->vehicle ? ($order->vehicle->make?->name . ' ' . $order->vehicle->model?->name) : 'N/A',
                    $order->fee ?? '',
                    $order->payment_completed ? 'Yes' : 'No',
                    $order->created_at?->format('Y-m-d H:i:s') ?? '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function vehicles(Request $request): StreamedResponse
    {
        $vehicles = Vehicle::with(['make', 'model', 'entity'])
            ->orderByDesc('created_at')
            ->limit(5000)
            ->get();

        $fileName = 'vehicle-report-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = static function () use ($vehicles) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Vehicle ID',
                'Title',
                'Make',
                'Model',
                'Status',
                'Price',
                'Dealer/Entity',
                'Created At',
            ]);

            foreach ($vehicles as $vehicle) {
                fputcsv($handle, [
                    $vehicle->id,
                    $vehicle->title ?? '',
                    $vehicle->make?->name ?? '',
                    $vehicle->model?->name ?? '',
                    $vehicle->status instanceof \App\Enums\VehicleStatus ? $vehicle->status->value : (string) $vehicle->status,
                    $vehicle->price ?? '',
                    $vehicle->entity?->name ?? '',
                    $vehicle->created_at?->format('Y-m-d H:i:s') ?? '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function users(Request $request): StreamedResponse
    {
        $users = User::orderByDesc('created_at')
            ->limit(5000)
            ->get();

        $fileName = 'user-report-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = static function () use ($users) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'User ID',
                'Name',
                'Email',
                'Role',
                'Created At',
            ]);

            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name ?? '',
                    $user->email ?? '',
                    $user->role ?? '',
                    $user->created_at?->format('Y-m-d H:i:s') ?? '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}


