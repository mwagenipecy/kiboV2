<?php

namespace App\Livewire\Admin\Registration;

use App\Models\Customer;
use App\Models\User;
use App\Jobs\SendRegistrationCredentials;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CustomerList extends Component
{
    use WithPagination;

    public $search = '';
    public $customerToDelete = null;
    public $showDeleteModal = false;
    public $customerToApprove = null;
    public $showApproveModal = false;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmApprove($customerId)
    {
        $this->customerToApprove = $customerId;
        $this->showApproveModal = true;
    }

    public function approve()
    {
        try {
            $customer = Customer::findOrFail($this->customerToApprove);
            
            if ($customer->approval_status === 'approved') {
                session()->flash('error', 'Customer is already approved!');
                $this->showApproveModal = false;
                $this->customerToApprove = null;
                return;
            }

            // Generate random password
            $password = Str::random(12);

            // Create user account
            $user = User::create([
                'name' => $customer->name,
                'email' => $customer->email,
                'password' => Hash::make($password),
                'role' => 'customer',
            ]);

            // Update customer with approval info
            $customer->update([
                'approval_status' => 'approved',
                'user_id' => $user->id,
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            // Dispatch queued job to send credentials email
            SendRegistrationCredentials::dispatch(
                $customer->email,
                $customer->name,
                $password,
                'customer'
            );

            session()->flash('success', 'Customer approved successfully! Credentials have been sent via email.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }

        $this->showApproveModal = false;
        $this->customerToApprove = null;
    }

    public function reject($customerId)
    {
        try {
            $customer = Customer::findOrFail($customerId);
            $customer->update([
                'approval_status' => 'rejected',
                'approved_by' => auth()->id(),
            ]);
            session()->flash('success', 'Customer registration rejected.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function confirmDelete($customerId)
    {
        $this->customerToDelete = $customerId;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $customer = Customer::findOrFail($this->customerToDelete);
            
            // Delete associated user if exists
            if ($customer->user_id) {
                User::find($customer->user_id)?->delete();
            }
            
            $customer->delete();
            session()->flash('success', 'Customer deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete this customer: ' . $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->customerToDelete = null;
    }

    public function render()
    {
        $customers = Customer::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                        ->orWhere('nida_number', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.registration.customer-list', [
            'customers' => $customers,
        ]);
    }
}
