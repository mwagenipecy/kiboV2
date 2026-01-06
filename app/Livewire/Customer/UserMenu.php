<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserMenu extends Component
{
    public $showDropdown = false;

    protected $listeners = ['closeDropdown' => 'closeDropdown'];

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function closeDropdown()
    {
        $this->showDropdown = false;
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        Session::invalidate();
        Session::regenerateToken();
        
        return $this->redirect(route('cars.index'), navigate: true);
    }

    public function getUserProperty()
    {
        return Auth::user();
    }

    public function getUserInitialsProperty()
    {
        $user = $this->user;
        if (!$user) {
            return 'U';
        }

        $name = $user->name ?? '';
        $parts = explode(' ', $name);
        
        if (count($parts) >= 2) {
            return strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
        }
        
        return strtoupper(substr($name, 0, 2)) ?: 'U';
    }

    public function render()
    {
        return view('livewire.customer.user-menu');
    }
}

