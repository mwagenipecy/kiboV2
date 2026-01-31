<?php

namespace App\Livewire\Admin\Registration;

use App\Models\Cfc;
use App\Models\User;
use App\Jobs\SendRegistrationCredentials;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class CfcList extends Component
{
    use WithPagination;

    public $search = '';
    public $cfcToDelete = null;
    public $showDeleteModal = false;
    public $cfcToApprove = null;
    public $showApproveModal = false;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmApprove($cfcId)
    {
        $this->cfcToApprove = $cfcId;
        $this->showApproveModal = true;
    }

    public function approve()
    {
        try {
            $cfc = Cfc::findOrFail($this->cfcToApprove);
            
            if ($cfc->approval_status === 'approved') {
                session()->flash('error', 'CFC is already approved!');
                $this->showApproveModal = false;
                $this->cfcToApprove = null;
                return;
            }

            // Generate random password for login credentials
            $password = Str::random(12);

            // Check if user with this email already exists
            $existingUser = User::where('email', $cfc->email)->first();
            
            if ($existingUser) {
                // User already exists, link it to the CFC
                // Check if user already has a different role
                if ($existingUser->role !== 'cfc' && $existingUser->role !== null) {
                    session()->flash('error', 'A user with this email already exists with a different role (' . ucfirst($existingUser->role) . '). Please use a different email address.');
                    $this->showApproveModal = false;
                    $this->cfcToApprove = null;
                    return;
                }
                
                // Update existing user: reset password and update role if needed
                $existingUser->update([
                    'role' => 'cfc',
                    'password' => Hash::make($password),
                ]);
                
                $user = $existingUser;
            } else {
                // Create new user account
                $user = User::create([
                    'name' => $cfc->name,
                    'email' => $cfc->email,
                    'password' => Hash::make($password),
                    'role' => 'cfc',
                ]);
            }

            // Update CFC with approval info
            $cfc->update([
                'approval_status' => 'approved',
                'user_id' => $user->id,
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            // Send credentials email immediately (synchronously)
            SendRegistrationCredentials::dispatchSync(
                $cfc->email,
                $cfc->name,
                $password,
                'cfc'
            );

            session()->flash('success', 'CFC approved successfully! Login credentials have been sent via email.');
        } catch (QueryException $e) {
            // Handle database integrity constraint violations
            if ($e->getCode() === '23000') {
                if (str_contains($e->getMessage(), 'users_email_unique')) {
                    session()->flash('error', 'A user with this email address already exists. The CFC has been linked to the existing user account.');
                } else {
                    session()->flash('error', 'A database error occurred. Please try again or contact support if the problem persists.');
                }
            } else {
                session()->flash('error', 'An error occurred while approving the CFC. Please try again.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An unexpected error occurred. Please try again or contact support if the problem persists.');
        }

        $this->showApproveModal = false;
        $this->cfcToApprove = null;
    }

    public function reject($cfcId)
    {
        try {
            $cfc = Cfc::findOrFail($cfcId);
            $cfc->update([
                'approval_status' => 'rejected',
                'approved_by' => auth()->id(),
            ]);
            session()->flash('success', 'CFC registration rejected.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function confirmDelete($cfcId)
    {
        $this->cfcToDelete = $cfcId;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $cfc = Cfc::findOrFail($this->cfcToDelete);
            
            // Delete associated user if exists
            if ($cfc->user_id) {
                User::find($cfc->user_id)?->delete();
            }
            
            $cfc->delete();
            session()->flash('success', 'CFC deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete this CFC: ' . $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->cfcToDelete = null;
    }

    public function render()
    {
        $cfcs = Cfc::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                        ->orWhere('registration_number', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.registration.cfc-list', [
            'cfcs' => $cfcs,
        ]);
    }
}
