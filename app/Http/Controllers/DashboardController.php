<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Central dashboard router: send each role to the right panel.
     */
    public function __invoke()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('cars.index');
        }

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');

            case 'dealer':
                return redirect()->route('dealer.dashboard');

            case 'lender':
                return redirect()->route('lender.dashboard');

            case 'agent':
                // Agents focus on spare parts and related operations
                return redirect()->route('admin.spare-part-orders');

            default:
                // Customers and any unknown roles go to main site
                return redirect()->route('cars.index');
        }
    }
}


