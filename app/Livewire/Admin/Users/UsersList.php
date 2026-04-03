<?php

namespace App\Livewire\Admin\Users;

use App\Jobs\SendPasswordResetEmail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class UsersList extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';

    public $showAddAdminModal = false;
    public $adminName = '';
    public $adminEmail = '';
    public $adminPhone = '';
    
    public $showResetPasswordModal = false;
    public $resetUserId = null;
    public $confirmPassword = '';
    
    public $showStatusModal = false;
    public $statusUserId = null;
    public $statusAction = '';
    public $statusConfirmPassword = '';

    protected $queryString = ['search', 'roleFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'adminEmail') {
            $this->validateOnly('adminEmail', [
                'adminEmail' => [
                    'required',
                    'email',
                    'max:255',
                    function ($attribute, $value, $fail) {
                        if (User::where('email', $value)->exists()) {
                            $fail('This email is already registered.');
                        }
                    },
                ],
            ]);
        }
    }

    public function openAddAdminModal()
    {
        $this->showAddAdminModal = true;
        $this->adminName = '';
        $this->adminEmail = '';
        $this->adminPhone = '';
    }

    public function closeAddAdminModal()
    {
        $this->showAddAdminModal = false;
        $this->adminName = '';
        $this->adminEmail = '';
        $this->adminPhone = '';
        $this->resetValidation();
    }

    public function createAdmin()
    {
        $this->validate([
            'adminName' => 'required|string|max:255',
            'adminEmail' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (User::where('email', $value)->exists()) {
                        $fail('This email is already registered.');
                    }
                },
            ],
            'adminPhone' => ['nullable', 'string', 'max:20', 'unique:users,phone_number'],
        ]);

        try {
            $password = Str::random(12);
            
            $user = User::create([
                'name' => $this->adminName,
                'email' => $this->adminEmail,
                'phone_number' => $this->adminPhone ?: null,
                'password' => Hash::make($password),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);

            // Send credentials via email
            Mail::send('emails.admin-credentials', [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $password,
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Your Admin Account Credentials - Kibo');
            });

            session()->flash('message', 'Admin user created successfully! Credentials sent to ' . $user->email);
            $this->closeAddAdminModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create admin: ' . $e->getMessage());
        }
    }

    public function openResetPasswordModal($userId)
    {
        $this->showResetPasswordModal = true;
        $this->resetUserId = $userId;
        $this->confirmPassword = '';
    }

    public function closeResetPasswordModal()
    {
        $this->showResetPasswordModal = false;
        $this->resetUserId = null;
        $this->confirmPassword = '';
        $this->resetValidation();
    }

    public function resetUserPassword()
    {
        $this->validate([
            'confirmPassword' => 'required|string|min:8',
        ], [
            'confirmPassword.required' => 'Please confirm your password to proceed.',
        ]);

        try {
            if (!Hash::check($this->confirmPassword, auth()->user()->password)) {
                $this->addError('confirmPassword', 'The password you entered is incorrect.');
                return;
            }

            $user = User::findOrFail($this->resetUserId);
            
            $newPassword = Str::random(12);
            
            $user->update([
                'password' => Hash::make($newPassword),
            ]);

            SendPasswordResetEmail::dispatch($user->name, $user->email, $newPassword, $user->getPhoneNumber());

            session()->flash('message', 'Password reset successfully! New credentials will be sent to ' . $user->email . ($user->getPhoneNumber() ? ' and phone.' : '.'));
            $this->closeResetPasswordModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to reset password: ' . $e->getMessage());
        }
    }

    public function openStatusModal($userId, $action)
    {
        $user = User::find($userId);
        
        if ($user && $user->id === auth()->id()) {
            session()->flash('error', 'You cannot deactivate your own account.');
            return;
        }
        
        $this->showStatusModal = true;
        $this->statusUserId = $userId;
        $this->statusAction = $action;
        $this->statusConfirmPassword = '';
    }

    public function closeStatusModal()
    {
        $this->showStatusModal = false;
        $this->statusUserId = null;
        $this->statusAction = '';
        $this->statusConfirmPassword = '';
        $this->resetValidation();
    }

    public function toggleUserStatus()
    {
        $this->validate([
            'statusConfirmPassword' => 'required|string|min:8',
        ], [
            'statusConfirmPassword.required' => 'Please confirm your password to proceed.',
        ]);

        try {
            if (!Hash::check($this->statusConfirmPassword, auth()->user()->password)) {
                $this->addError('statusConfirmPassword', 'The password you entered is incorrect.');
                return;
            }

            $user = User::findOrFail($this->statusUserId);
            
            if ($user->id === auth()->id()) {
                session()->flash('error', 'You cannot deactivate your own account.');
                $this->closeStatusModal();
                return;
            }
            
            $newStatus = $this->statusAction === 'deactivate' ? 'inactive' : 'active';
            $user->update(['status' => $newStatus]);
            
            $statusText = $newStatus === 'active' ? 'activated' : 'deactivated';
            session()->flash('message', "User {$statusText} successfully!");
            $this->closeStatusModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update user status: ' . $e->getMessage());
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
