<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class DealersList extends Component
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
            session()->flash('message', 'Dealer deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete dealer: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $dealers = User::where('role', 'dealer')
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
            'total' => User::where('role', 'dealer')->count(),
            'active' => User::where('role', 'dealer')->whereHas('entity', function($q) {
                $q->where('status', 'active');
            })->count(),
            'pending' => User::where('role', 'dealer')->whereHas('entity', function($q) {
                $q->where('status', 'pending');
            })->count(),
        ];

        return view('livewire.admin.users.dealers-list', [
            'dealers' => $dealers,
            'stats' => $stats,
        ]);
    }
}
