<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersList extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';

    protected $queryString = ['search', 'roleFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function deleteUser($userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            // Prevent deleting yourself
            if ($user->id === auth()->id()) {
                session()->flash('error', 'You cannot delete your own account.');
                return;
            }
            
            $user->delete();
            session()->flash('message', 'User deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->where('role', $this->roleFilter);
            })
            ->with('entity')
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'lenders' => User::where('role', 'lender')->count(),
            'dealers' => User::where('role', 'dealer')->count(),
            'users' => User::where('role', 'user')->count(),
        ];

        return view('livewire.admin.users.users-list', [
            'users' => $users,
            'stats' => $stats,
        ]);
    }
}
