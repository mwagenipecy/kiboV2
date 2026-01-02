<?php

namespace App\Livewire\Admin\Registration;

use App\Models\Cfc;
use App\Models\User;
use App\Jobs\SendRegistrationCredentials;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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

            // Generate random password
            $password = Str::random(12);

            // Create user account
            $user = User::create([
                'name' => $cfc->name,
                'email' => $cfc->email,
                'password' => Hash::make($password),
                'role' => 'cfc',
            ]);

            // Update CFC with approval info
            $cfc->update([
                'approval_status' => 'approved',
                'user_id' => $user->id,
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            // Dispatch queued job to send credentials email
            SendRegistrationCredentials::dispatch(
                $cfc->email,
                $cfc->name,
                $password,
                'cfc'
            );

            session()->flash('success', 'CFC approved successfully! Credentials have been sent via email.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
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
