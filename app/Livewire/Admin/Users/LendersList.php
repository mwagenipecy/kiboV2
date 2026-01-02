<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class LendersList extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteUser($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $user->delete();
            session()->flash('message', 'Lender deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete lender: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $lenders = User::where('role', 'lender')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->with('entity')
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => User::where('role', 'lender')->count(),
            'active' => User::where('role', 'lender')->whereHas('entity', function($q) {
                $q->where('status', 'active');
            })->count(),
            'pending' => User::where('role', 'lender')->whereHas('entity', function($q) {
                $q->where('status', 'pending');
            })->count(),
        ];

        return view('livewire.admin.users.lenders-list', [
            'lenders' => $lenders,
            'stats' => $stats,
        ]);
    }
}
