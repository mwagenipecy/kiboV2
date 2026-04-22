<?php

namespace App\Http\Controllers;

use App\Models\Agent;
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
                return redirect()->route('admin.dashboard');

            case 'lender':
                return redirect()->route('lender.dashboard');

            case 'agent':
                $agentType = Agent::where('user_id', $user->id)->value('agent_type');

                if ($agentType === 'lubricant_shop') {
                    return redirect()->route('admin.dashboard');
                }

                // Spare part / garage agents continue to orders workspace
                return redirect()->route('admin.spare-part-orders');

            default:
                // Customers and any unknown roles go to main site
                return redirect()->route('cars.index');
        }
    }
}


