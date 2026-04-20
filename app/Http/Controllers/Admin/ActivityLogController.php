<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $role = auth()->user()?->role;
        abort_unless($role && $role !== 'customer', 403);

        $filters = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'specific_date' => ['nullable', 'date'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
        ]);

        $baseQuery = LoginActivity::query()
            ->with('user:id,name,email,role');

        if (!empty($filters['user_id'])) {
            $baseQuery->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['specific_date'])) {
            $baseQuery->whereDate('logged_in_at', $filters['specific_date']);
        } else {
            if (!empty($filters['from_date'])) {
                $baseQuery->whereDate('logged_in_at', '>=', $filters['from_date']);
            }

            if (!empty($filters['to_date'])) {
                $baseQuery->whereDate('logged_in_at', '<=', $filters['to_date']);
            }
        }

        $activities = (clone $baseQuery)
            ->orderByDesc('logged_in_at')
            ->paginate(20)
            ->withQueryString();

        $totalLogins = (clone $baseQuery)->count();

        $uniqueUsers = (clone $baseQuery)
            ->distinct('user_id')
            ->count('user_id');

        $lastLoginAt = (clone $baseQuery)->max('logged_in_at');

        $topUsers = (clone $baseQuery)
            ->select('user_id')
            ->selectRaw('COUNT(*) as login_count')
            ->selectRaw('MAX(logged_in_at) as last_login_at')
            ->with('user:id,name,email,role')
            ->groupBy('user_id')
            ->orderByDesc('login_count')
            ->orderByDesc(DB::raw('MAX(logged_in_at)'))
            ->limit(5)
            ->get();

        $filterUsers = User::query()
            ->whereHas('loginActivities')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.activity-log', [
            'activities' => $activities,
            'totalLogins' => $totalLogins,
            'uniqueUsers' => $uniqueUsers,
            'lastLoginAt' => $lastLoginAt,
            'topUsers' => $topUsers,
            'filterUsers' => $filterUsers,
            'filters' => $filters,
        ]);
    }
}
