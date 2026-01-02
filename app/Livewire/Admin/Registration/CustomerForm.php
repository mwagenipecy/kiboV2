<?php

namespace App\Livewire\Admin\Registration;

use App\Models\Customer;
use Livewire\Component;

class CustomerForm extends Component
{
    public $customerId = null;
    public $name = '';
    public $email = '';
    public $phoneNumber = '';
    public $nidaNumber = '';
    public $address = '';
    public $status = 'active';

    public function mount($id = null)
    {
        if ($id) {
            $customer = Customer::findOrFail($id);
            $this->customerId = $customer->id;
            $this->name = $customer->name;
            $this->email = $customer->email;
            $this->phoneNumber = $customer->phone_number;
            $this->nidaNumber = $customer->nida_number;
            $this->address = $customer->address ?? '';
            $this->status = $customer->status;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                $this->customerId 
                    ? 'unique:customers,email,' . $this->customerId 
                    : 'unique:customers,email'
            ],
            'phoneNumber' => 'required|string|max:20',
            'nidaNumber' => [
                'required',
                'string',
                'max:50',
                $this->customerId 
                    ? 'unique:customers,nida_number,' . $this->customerId 
                    : 'unique:customers,nida_number'
            ],
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ], [
            'phoneNumber.required' => 'Phone number is required.',
            'nidaNumber.required' => 'NIDA number is required.',
            'nidaNumber.unique' => 'This NIDA number is already registered.',
        ]);

        try {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'phone_number' => $this->phoneNumber,
                'nida_number' => $this->nidaNumber,
                'address' => $this->address,
                'status' => $this->status,
            ];

            if ($this->customerId) {
                $customer = Customer::findOrFail($this->customerId);
                $customer->update($data);
                session()->flash('success', 'Customer updated successfully!');
            } else {
                Customer::create($data);
                session()->flash('success', 'Customer created successfully!');
            }

            return redirect()->route('admin.registration.customers');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('admin.registration.customers');
    }

    public function render()
    {
        return view('livewire.admin.registration.customer-form');
    }
}
