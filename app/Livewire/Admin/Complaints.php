<?php

namespace App\Livewire\Admin;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Complaints extends Component
{
    public $dateFrom = '';
    public $dateTo = '';
    public $statusFilter = '';
    public $categoryFilter = '';

    public function mount()
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $this->dateFrom = request()->get('date_from', now()->subDays(30)->format('Y-m-d'));
        $this->dateTo = request()->get('date_to', now()->format('Y-m-d'));
        $this->statusFilter = request()->get('status', '');
        $this->categoryFilter = request()->get('category', '');
    }

    public function applyFilters()
    {
        // Livewire will re-render with updated properties
    }

    public function render()
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();

        $baseQuery = Complaint::query()->with(['assignedTo']);

        if ($isAdmin) {
            $from = $this->dateFrom ? $this->dateFrom . ' 00:00:00' : now()->subDays(30)->startOfDay()->toDateTimeString();
            $to = $this->dateTo ? $this->dateTo . ' 23:59:59' : now()->endOfDay()->toDateTimeString();
            $baseQuery->whereBetween('created_at', [$from, $to]);
            if ($this->statusFilter !== '') {
                $baseQuery->where('status', $this->statusFilter);
            }
            if ($this->categoryFilter !== '') {
                $baseQuery->where('category', $this->categoryFilter);
            }
            $complaints = $baseQuery->orderByDesc('created_at')->get();

            // Summary for date range (same range, all statuses)
            $summaryQuery = Complaint::whereBetween('created_at', [$from, $to]);
            $summary = [
                'total' => (clone $summaryQuery)->count(),
                'pending' => (clone $summaryQuery)->pending()->count(),
                'in_progress' => (clone $summaryQuery)->inProgress()->count(),
                'closed' => (clone $summaryQuery)->closed()->count(),
            ];
        } else {
            // Agent: only complaints assigned to me
            $baseQuery->where('assigned_to_user_id', $user->id);
            if ($this->statusFilter !== '') {
                $baseQuery->where('status', $this->statusFilter);
            }
            $complaints = $baseQuery->orderByDesc('created_at')->get();
            $summary = [
                'total' => $complaints->count(),
                'pending' => $complaints->where('status', Complaint::STATUS_PENDING)->count(),
                'in_progress' => $complaints->where('status', Complaint::STATUS_IN_PROGRESS)->count(),
                'closed' => $complaints->where('status', Complaint::STATUS_CLOSED)->count(),
            ];
        }

        $assignableUsers = $isAdmin ? User::whereIn('role', ['admin', 'agent', 'dealer', 'lender'])->orderBy('name')->get(['id', 'name', 'email', 'role']) : collect();

        return view('livewire.admin.complaints', [
            'complaints' => $complaints,
            'summary' => $summary,
            'isAdmin' => $isAdmin,
            'assignableUsers' => $assignableUsers,
        ])->layout('layouts.admin');
    }
}
